<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estate extends Model
{
    use HasFactory, UUID;

    protected $fillable = [
        'name',
        'description',
        'county_id',
        'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function county()
    {
        return $this->belongsTo(County::class);
    }
}
