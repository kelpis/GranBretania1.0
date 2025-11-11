@props(['value'])

{{-- Dejar la clase de color fuera del componente para que la vista que lo use
     pueda controlar el color (por ejemplo: text-beige2). Mantener tipografía y
     tamaño por defecto aquí. --}}
<label {{ $attributes->merge(['class' => 'block font-medium text-sm']) }}>
    {{ $value ?? $slot }}
</label>
