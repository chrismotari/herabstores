<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\UUID;

class User extends Authenticatable
{
    use  HasFactory, Notifiable, UUID;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fname',
        'mname',
        'lname',
        'phone',
        'alt_phone',
        'email',
        'alt_email',
        'address',
        'city',
        "state_id",
        'zip_code',
        "country_id",
        'photo_url',
        "job_category_id",
        "profession_id",
        "contract_type_id",
        'dob',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'dob' => 'date',
        'password' => 'hashed',
    ];

    protected $appends = ['name'];

    public function getNameAttribute()
    {
        return ucwords("{$this->fname} {$this->mname} {$this->lname}");
    }

    public function getResumeAttribute(){
        return $this->resumes->where('is_master_copy', true)->first();
    }

    public function job_category()
    {
        return $this->belongsTo(JobCategory::class);
    }

    public function resumes()
    {
        return $this->belongsTo(Resume::class);
    }

    public function profession()
    {
        return $this->belongsTo(Profession::class);
    }

    public function contract_type()
    {
        return $this->belongsTo(ContractType::class);
    }
    
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function saved_jobs()
    {
        return $this->hasMany(SavedJob::class);
    }

}
