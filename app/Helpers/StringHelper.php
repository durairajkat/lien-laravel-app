<?php

namespace App\Helpers;

class StringHelper
{
    public static function normalizeString($name): string
    {
        $value = strtolower($name);
        $value = str_replace(['-', ' '], '', $value);
        $value = preg_replace('/[^a-z0-9]/', '', $value);
        return trim($value);
    }
}
