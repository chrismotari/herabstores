<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Image fields hold either a full URL or a path on the public disk.
 */
class MediaUrl
{
    public static function for(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        return Str::startsWith($value, ['http://', 'https://']) ? $value : asset('storage/'.ltrim($value, '/'));
    }
}
