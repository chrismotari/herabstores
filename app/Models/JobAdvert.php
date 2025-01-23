<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;
use Illuminate\Database\Eloquent\Casts\Attribute;

class JobAdvert extends Model
{
    use HasFactory, UUID;
    protected $fillable = [
        "company_id",
        'name',
        "job_category_id",
        "profession_id", 
        "contract_type_id", 
        "country_id",  
        'city',
        'min_salary',
        'max_salary',
        'about_job',
        'responsibilities',
        'jd',
        'qualifications',
        'tags',
        'application_deadline',
        'flyer_url',
        "user_id",
    ];


    protected function tags(): Attribute
    {
        return Attribute::make(
           get: fn ($value) => $value ? json_decode($value, true) : [],
            set: fn ($value) => json_encode($value),
        );
    } 
    
    protected function responsibilities(): Attribute
    {
        return Attribute::make(
           get: fn ($value) => $value ? json_decode($value, true) : [],
            set: fn ($value) => json_encode($value),
        );
    } 

    protected function qualifications(): Attribute
    {
        return Attribute::make(
           get: fn ($value) => $value ? json_decode($value, true) : [],
            set: fn ($value) => json_encode($value),
        );
    } 

    protected function jd(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? json_decode($value, true) : [],
            set: fn ($value) => json_encode($value),
        );
    } 

    public function job_category()
    {
        return $this->belongsTo(JobCategory::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function profession()
    
    {
        return $this->belongsTo(Profession::class);
    }

    public function contract_type()
    {
        return $this->belongsTo(ContractType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function appointments()
    {
        return $this->hasManyThrough(JobApplication::class, Appointment::class);
    }

    public function getLocationAttribute(){
        $country = $this->country ? $this->country->name : "";
        return ucwords(" {$this->city} {$country}");
    }

    public function getSalaryAttribute(){
        return "{$this->min_salary} - {$this->max_salary}";
    }
}
