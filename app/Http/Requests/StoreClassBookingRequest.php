<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Recaptcha;
use Illuminate\Validation\Rule;
use App\Models\ClassBooking;
use Illuminate\Contracts\Validation\Validator;
use Carbon\Carbon;

//Request para validar solicitudes de creación de reservas de clases.


class StoreClassBookingRequest extends FormRequest
{
    // Autorización: solo usuarios autenticados pueden crear reservas.
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    // Sanitiza datos antes de validarlos: limpia teléfono y notas de tags HTML y espacios.
    protected function prepareForValidation()
    {
        $this->merge([
            'phone' => $this->phone ? trim(strip_tags($this->phone)) : null,
            'notes' => $this->notes ? trim(strip_tags($this->notes)) : null,
        ]);
    }

    // Reglas de validación básicas para los campos de la reserva.
    public function rules(): array
    {
        return [
            'class_date' => ['required', 'date', 'after_or_equal:today'], // Fecha obligatoria, >= hoy.
            'class_time' => ['required', 'date_format:H:i'], // Hora obligatoria en formato HH:MM.
            // Teléfono opcional con regex para caracteres permitidos.
            'phone'      => ['nullable', 'string', 'max:30', 'regex:/^[0-9+\s\-()]+$/'],
            'notes'      => ['nullable', 'string', 'max:255'], // Notas opcionales.
            'gdpr'       => ['accepted'], // Aceptación de GDPR obligatoria.
            // Validación de reCAPTCHA v3 con umbral 0.5 y acción 'booking'.
            'g-recaptcha-response' => ['required', new Recaptcha(0.5, 'booking')],
        ];
    }

    // Validaciones adicionales después de las reglas básicas.
    protected function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $allData = $validator->getData();
            $data = [
                'class_date' => $allData['class_date'] ?? null,
                'class_time' => $allData['class_time'] ?? null,
            ];

            // Si faltan fecha o hora, saltar validaciones adicionales.
            if (empty($data['class_date']) || empty($data['class_time'])) {
                return;
            }

            // Verificar que la franja no esté ocupada por reservas pagadas o holds activos de otros usuarios.
            $currentUserId = $this->user() ? $this->user()->id : null;

            $exists = ClassBooking::where('class_date', $data['class_date'])
                ->where('class_time', $data['class_time'])
                ->whereNotIn('status', ['cancelled', 'rejected'])
                ->where(function ($q) use ($currentUserId) {
                    // Reservas pagadas siempre ocupan la franja.
                    $q->where('paid', true)
                      // O holds activos que no pertenecen al usuario actual.
                      ->orWhere(function ($q2) use ($currentUserId) {
                          $q2->whereNotNull('reserved_until')
                             ->where('reserved_until', '>', now())
                             ->where(function ($q3) use ($currentUserId) {
                                 $q3->whereNull('user_id')
                                    ->orWhere('user_id', '!=', $currentUserId);
                             });
                      });
                })
                ->exists();

            if ($exists) {
                $validator->errors()->add('class_time', 'Lo sentimos — esa franja ya está ocupada.');
            }

            // Validación: no permitir reservas en fines de semana.
            try {
                $dt = Carbon::parse($data['class_date']);
                $dow = $dt->dayOfWeek; // 0 = domingo, 6 = sábado.
                if ($dow === Carbon::SATURDAY || $dow === Carbon::SUNDAY) {
                    $validator->errors()->add('class_date', 'No es posible reservar en fines de semana. Por favor elige un día laborable.');
                }
            } catch (\Throwable $e) {
                // Si no se puede parsear, dejar que la regla 'date' reporte el error.
            }

            // Validación: exigir al menos 5 horas de antelación para la clase.
            try {
                $time = substr($data['class_time'], 0, 5);
                $classDateTime = Carbon::parse($data['class_date'] . ' ' . $time);
                $now = Carbon::now();
                $minutesUntil = $now->diffInMinutes($classDateTime, false);

                if ($minutesUntil < 300) { // Menos de 5 horas (300 minutos).
                    $validator->errors()->add('class_time', 'Debes reservar con al menos 5 horas de antelación.');
                }
            } catch (\Throwable $e) {
                // Si falla el parseo, no añadir este error específico.
            }
        });
    }

    // Mensajes personalizados para errores de validación.
    public function messages(): array
    {
        return [
            'availability_slot_id.required' => 'Selecciona una franja disponible.',
            'availability_slot_id.exists'   => 'La franja no está disponible o incumple las reglas (L–V, 09:00–21:00).',
            'class_date.required' => 'Selecciona una fecha válida.',
            'class_date.date' => 'La fecha no tiene un formato válido.',
            'phone.regex' => 'El teléfono solo puede contener dígitos, espacios, +, paréntesis y guiones.',
            'phone.max' => 'El teléfono es demasiado largo.',
            'g-recaptcha-response.required' => 'Por favor completa el reCAPTCHA antes de enviar el formulario.',
        ];
    }
}
