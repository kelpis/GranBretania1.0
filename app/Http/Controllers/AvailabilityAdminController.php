<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ManageSlotRequest;
use App\Models\AvailabilitySlot;
use App\Models\ClassBooking;
use Illuminate\Http\Request;

//CONTROLADOR FRANJAS HORARIAS ADMIN

class AvailabilityAdminController extends Controller
{
    public function index()
    {
        $slots = AvailabilitySlot::orderBy('date')->orderBy('start_time')->paginate(30);
        return view('admin.availability', compact('slots'));
    }

    // Crear o actualizar (upsert) un slot puntual
    public function store(ManageSlotRequest $request)
    {
        $data = $request->validated();

        // upsert por (date, start, end)Si esa franja exacta existe la edit.Si no existe la crea
        $slot = AvailabilitySlot::firstOrNew([
            'date' => $data['date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
        ]);

        // si se quiere bloquear y hay reserva confirmada (en ese rango), evitarlo
            if ($data['status'] === 'blocked') {
            // comprobar bookings cuyo class_time cae dentro del rango [start_time, end_time)
            // Tratar como conflicto tanto reservas con status='confirmed' como aquellas ya pagadas (paid = true)
            $hasConfirmed = ClassBooking::where('class_date', $data['date'])
                ->where('class_time', '>=', $data['start_time'])
                ->where('class_time', '<', $data['end_time'])
                ->where(function ($q) {
                    $q->where('status', 'confirmed')
                      ->orWhere('paid', true);
                })
                ->exists();

            if ($hasConfirmed) {
                return back()->with('error', 'No se puede bloquear: existe una reserva confirmada o ya pagada en esa franja.');
            }
        }
        //Guardar
        $slot->status = $data['status'];
        $slot->save();

        return back()->with('ok', 'Franja guardada.');
    }

    // Generador en lote (laborables, por rango de fechas y horas)
    public function generate(Request $request)
    {
        // Validación básica de fechas y estado. Las horas se validan más abajo
        // dependiendo de si se está generando franjas horarias o bloqueando días completos.
        $request->validate([
            'from_date' => ['required', 'date', 'after_or_equal:today'],
            'to_date'   => ['required', 'date', 'after_or_equal:from_date'],
            'status'    => ['required', 'in:available,blocked'],
            'full_day'  => ['nullable', 'boolean'], // si true -> bloquea días enteros (00:00-24:00)
        ]);
        //Convertimos fechas en objetos Carbon para facilitar calculos
        
        $from = \Carbon\Carbon::parse($request->from_date);
        $to   = \Carbon\Carbon::parse($request->to_date);
        //Almacena franjas
        $count = 0;
        //Lista textual de franjas creadas para mostrar
        $created = [];

        // Si no se envían horas, asumimos bloqueo de días completos.
        $isFullDay = $request->boolean('full_day') || !$request->has('start_hour');

        // Validación de horas sólo si no pedimos días completos
        if (!$isFullDay) {
            $request->validate([
                'start_hour' => ['required', 'integer', 'between:0,23'],
                'end_hour'  => ['required', 'integer', 'between:1,24'],
            ]);
            //Evita rangos como 17 → 15 o 10 → 10 (incorrectos)
            if ((int)$request->end_hour <= (int)$request->start_hour) {
                return back()->with('error', 'La hora de fin debe ser mayor que la hora de inicio.');
            }
        }

        // PRE-CHECK: Recorrer todas las fechas/franjas que se van a crear y recopilar conflictos
        $conflicts = [];
        for ($date = $from->copy(); $date->lte($to); $date->addDay()) {
            if ($date->isWeekend()) continue;

            //Si hay cualquier reserva confirmada en ese día, es un conflicto y NO se generará disponibilidad.
                if ($isFullDay) {
                $hasConfirmedAny = ClassBooking::where('class_date', $date->toDateString())
                    ->where(function ($q) {
                        $q->where('status', 'confirmed')
                          ->orWhere('paid', true);
                    })
                    ->exists();

                if ($hasConfirmedAny) {
                    $conflicts[] = $date->toDateString();
                }
                continue;
            }
            //Revisa cada hora dentro del rango. Si existe una reserva confirmada en esa hora → se añade al array de conflictos.
            for ($h = (int)$request->start_hour; $h < (int)$request->end_hour; $h++) {
                $start = sprintf('%02d:00', $h);

                $hasConfirmedSlot = ClassBooking::where('class_date', $date->toDateString())
                    ->where('class_time', $start)
                    ->where(function ($q) {
                        $q->where('status', 'confirmed')
                          ->orWhere('paid', true);
                    })
                    ->exists();

                if ($hasConfirmedSlot) {
                    $conflicts[] = $date->toDateString() . ' ' . $start;
                }
            }
        }
        // Si no hay conflictos, proceder a generar las franjas
        if (count($conflicts) > 0) {
            $msg = 'No se han creado franjas porque existen reservas confirmadas en las siguientes fechas/franjas: ' . implode(', ', $conflicts) . '.';
            return back()->with('error', $msg)->with('conflicts', $conflicts);
        }

        // Bucle principal: iterar sobre cada fecha en el rango
        for ($date = $from->copy(); $date->lte($to); $date->addDay()) {
            // Saltar fines de semana (sábado y domingo)
            if ($date->isWeekend()) continue;

            // Si se solicita bloqueo de día completo
            if ($isFullDay) {
                // Crear una franja que cubra todo el día (00:00 a 24:00)
                $start = '00:00';
                $end = '24:00';

                // Usar updateOrCreate para crear o actualizar la franja
                AvailabilitySlot::updateOrCreate(
                    ['date' => $date->toDateString(), 'start_time' => $start, 'end_time' => $end],
                    ['status' => $request->status]
                );
                $count++; // Contador de franjas creadas/actualizadas
                $created[] = $date->toDateString() . ' ' . $start . '-' . $end; // Lista para mostrar en UI
                continue; // Pasar a la siguiente fecha
            }

            // Si no es día completo, crear franjas horarias individuales
            for ($h = (int)$request->start_hour; $h < (int)$request->end_hour; $h++) {
                // Formatear hora de inicio y fin (ej. 09:00 - 10:00)
                $start = sprintf('%02d:00', $h);
                $end   = sprintf('%02d:00', $h + 1);

                // Crear o actualizar la franja horaria
                AvailabilitySlot::updateOrCreate(
                    ['date' => $date->toDateString(), 'start_time' => $start, 'end_time' => $end],
                    ['status' => $request->status]
                );
                $count++; // Incrementar contador
                $created[] = $date->toDateString() . ' ' . $start . '-' . $end; // Agregar a lista
            }
        }

        // Devolver mensaje de éxito con detalle de franjas creadas
        $msg = "Generadas/actualizadas {$count} franjas.";
        return back()->with('ok', $msg)->with('generated', $created);
    }

    // Método para alternar rápidamente el estado de una franja (available/blocked)
    public function toggle(AvailabilitySlot $slot)
    {
        // Verificar si hay reservas confirmadas o pagadas en esa franja para evitar bloqueos indebidos
        $hasConfirmed = ClassBooking::where('class_date', $slot->date)
            ->where('class_time', $slot->start_time)
            ->where(function ($q) {
                $q->where('status', 'confirmed')
                  ->orWhere('paid', true);
            })
            ->exists();

        // Si se intenta bloquear y hay reserva confirmada, devolver error
        if ($slot->status === 'available' && $hasConfirmed) {
            return back()->with('error', 'No se puede bloquear: hay una reserva confirmada o ya pagada en esa franja.');
        }

        // Alternar el estado
        $slot->status = $slot->status === 'available' ? 'blocked' : 'available';
        $slot->save();

        return back()->with('ok', 'Franja actualizada.');
    }

    // Método para eliminar una franja (slot) si no afecta reservas confirmadas
    public function destroy(AvailabilitySlot $slot)
    {
        // Verificar si hay reservas confirmadas o pagadas asociadas
        $hasConfirmed = ClassBooking::where('class_date', $slot->date)
            ->where('class_time', $slot->start_time)
            ->where(function ($q) {
                $q->where('status', 'confirmed')
                  ->orWhere('paid', true);
            })
            ->exists();

        // Si hay reservas, no permitir eliminación
        if ($hasConfirmed) {
            return back()->with('error', 'No se puede borrar: hay una reserva confirmada o ya pagada asociada.');
        }

        // Eliminar la franja
        $slot->delete();
        return back()->with('ok', 'Franja eliminada.');
    }
}
