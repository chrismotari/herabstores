<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;
class Profession extends Model
{
    use HasFactory, UUID;

    protected $fillable = [
        'name',
        'description',
    ];

    public function jobs(){
        return $this->hasMany(Job::class, 'profession_id', 'id');
    }
}
