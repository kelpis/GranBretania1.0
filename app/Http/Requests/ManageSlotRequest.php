<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// Clase de Form Request para validar solicitudes de gestión de franjas horarias de disponibilidad.
// Se utiliza en el controlador  AvailabilityAdminController para crear o actualizar franjas.

class ManageSlotRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date'       => ['required','date','after_or_equal:today'], // Fecha obligatoria, >= hoy.
            'start_time' => ['required','date_format:H:i','regex:/^(?:[01]\d|2[0-3]):00$/'], // Hora de inicio en punto (HH:00).
            // Hora de fin en punto o 24:00 para fin de día.
            'end_time'   => ['required','regex:/^(?:(?:[01]\d|2[0-3]):00|24:00)$/'],
            'status'     => ['required','in:available,blocked'], // Estado: disponible o bloqueado.
        ];
    }

    // Mensajes personalizados para errores de validación.
    public function messages(): array
    {
        return [
            'start_time.regex' => 'La hora de inicio debe ser en punto (HH:00).',
            'end_time.regex'   => 'La hora de fin debe ser en punto (HH:00) o 24:00 para indicar fin de día.',
        ];
    }

    // Validación adicional después de las reglas básicas.
    // Verifica que la hora de fin sea posterior a la de inicio.
    public function withValidator($validator)
    {
        $validator->after(function ($v) {
            // Si no se han llenado las horas, saltar.
            if (! $this->filled('start_time') || ! $this->filled('end_time')) return;

            $start = $this->input('start_time');
            $end = $this->input('end_time');

            // Función para convertir hora a minutos (24:00 = 1440).
            $toMinutes = fn($t) => ($t === '24:00') ? 24*60 : (intval(substr($t,0,2)) * 60 + intval(substr($t,3,2)));

            $s = $toMinutes($start);
            $e = $toMinutes($end);

            // Si la hora de fin no es posterior, agregar error.
            if ($e <= $s) {
                $v->errors()->add('end_time', 'La hora de fin debe ser posterior a la hora de inicio.');
            }
        });
    }
}
