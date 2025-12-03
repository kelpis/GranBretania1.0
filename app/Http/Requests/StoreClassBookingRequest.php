<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Recaptcha;
use Illuminate\Validation\Rule;
use App\Models\ClassBooking;
use Illuminate\Contracts\Validation\Validator;
use Carbon\Carbon;

class StoreClassBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Reservas solo para usuarios autenticados
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'class_date' => ['required', 'date', 'after_or_equal:today'],
            'class_time' => ['required', 'date_format:H:i'],
            // Nombre/email/phone son gestionados por el `User` (login obligatorio)
            // Validar teléfono opcionalmente si se envía desde el formulario
            'phone'      => ['nullable', 'string', 'max:30', 'regex:/^[0-9+\s\-()]+$/'],
            // datos mínimos de reserva
            'notes'      => ['nullable', 'string', 'max:255'],
            'gdpr'       => ['accepted'],
            //Validar reCAPTCHA v3 con un umbral conservador (0.5) y con la acción esperada 'booking
            'g-recaptcha-response' => ['required', new Recaptcha(0.5, 'booking')],
        ];
    }
    //Validaciones adicionales
    protected function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $allData = $validator->getData();
            $data = [
                'class_date' => $allData['class_date'] ?? null,
                'class_time' => $allData['class_time'] ?? null,
            ];

            if (empty($data['class_date']) || empty($data['class_time'])) {
                return;
            }

            // Evitar franja ocupada: reservar si ya hay booking pagado o con hold activo
            // Permitir reintento si la reserva pertenece al mismo usuario
            $currentUserId = $this->user() ? $this->user()->id : null;

            $exists = ClassBooking::where('class_date', $data['class_date'])
                ->where('class_time', $data['class_time'])
                ->whereNotIn('status', ['cancelled', 'rejected'])
                ->where(function ($q) use ($currentUserId) {
                    $q->where(function ($q1) use ($currentUserId) {
                        $q1->where('paid', true)
                           ->where(function ($q2) use ($currentUserId) {
                               $q2->whereNull('user_id')
                                  ->orWhere('user_id', '!=', $currentUserId);
                           });
                    })
                    ->orWhere(function ($q3) use ($currentUserId) {
                        $q3->whereNotNull('reserved_until')
                           ->where('reserved_until', '>', now())
                           ->where(function ($q4) use ($currentUserId) {
                               $q4->whereNull('user_id')
                                  ->orWhere('user_id', '!=', $currentUserId);
                           });
                    });
                })
                ->exists();

            if ($exists) {
                $validator->errors()->add('class_time', 'Lo sentimos — esa franja ya está ocupada.');
            }

            // Validación: no permitir fines de semana
            try {
                $dt = Carbon::parse($data['class_date']);
                $dow = $dt->dayOfWeek; // 0 = domingo, 6 = sábado
                if ($dow === Carbon::SATURDAY || $dow === Carbon::SUNDAY) {
                    $validator->errors()->add('class_date', 'No es posible reservar en fines de semana. Por favor elige un día laborable.');
                }
            } catch (\Throwable $e) {
                // si no se puede parsear, dejar que la regla 'date' reporte el error
            }
            // Validación: exigir al menos 5 horas de antelación para la clase
            try {
                $time = substr($data['class_time'], 0, 5);
                $classDateTime = Carbon::parse($data['class_date'] . ' ' . $time);
                $now = Carbon::now();
                $minutesUntil = $now->diffInMinutes($classDateTime, false);

                if ($minutesUntil < 300) { // menos de 5 horas
                    $validator->errors()->add('class_time', 'Debes reservar con al menos 5 horas de antelación.');
                }
            } catch (\Throwable $e) {
                // si falla el parseo, no añadimos este error específico
            }
        });
    }

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
