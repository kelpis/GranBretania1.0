<?php

namespace App\Utils;

use Illuminate\Support\Str;

class StringUtils
{
    /**
     * Normaliza y convierte un nombre en Title Case, eliminando espacios extra.
     */
    public static function formatName(string $name): string
    {
        $trimmed = preg_replace('/\s+/', ' ', trim($name));
        return Str::title(mb_strtolower($trimmed));
    }

    /**
     * Devuelve las iniciales de un nombre (ej: "Juan Pérez" -> "JP").
     */
    public static function initials(string $name): string
    {
        $parts = preg_split('/\s+/', trim($name));
        $initials = array_map(fn($p) => mb_strtoupper(mb_substr($p, 0, 1)), array_filter($parts));
        return implode('', $initials);
    }
}
