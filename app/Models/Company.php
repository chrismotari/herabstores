<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;
class Company extends Model
{
    use HasFactory, UUID;

    protected $fillable = [
        "id",
        'name',
        'contact_person_name',
        'description',
        'phone',
        'email',
        'address',
        'city',
        "state_id",
        'zip_code',
        "country_id",
        'social_handles',
    ];
    public function state(){
        return $this->belongsTo(State::class);
    }
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function jobs(){
        return $this->hasMany(Job::class);
    }
}
