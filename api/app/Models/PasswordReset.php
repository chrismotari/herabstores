<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;

class PasswordReset extends Model
{
    use HasFactory, UUID;

    protected $guarded = [];

    const UPDATED_AT = null;

    protected $fillable = [
        "id",
        'email',
        'token',
        'created_at'
    ];

    protected $table = 'password_reset_tokens';
}
