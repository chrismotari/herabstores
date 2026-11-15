<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ref extends Model
{
    use UUID;
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ref) {
            if (!$ref->id) {
                $ref->id = Str::uuid(); 
            }
            $ref->ref_code = self::generateNHexa();
        });
    }

    private static function generateNHexa($length = 5)
    {
        do {
            $code = substr(bin2hex(random_bytes(ceil($length / 2))), 0, $length);
        } while (self::where('ref_code', $code)->exists()); // Ensure uniqueness

        return $code;
    }

    protected $fillable = [
        'ref_code',
        'user_id',
        'description',
        'is_active',
        'expires_at',
        'used_at',
        'canceled_at',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
        
    }
}
