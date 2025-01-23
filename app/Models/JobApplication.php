<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class JobApplication extends Model
{
    use HasFactory, UUID;

    protected static function booted()
    {
        static::addGlobalScope('excludeDrafts', function (EloquentBuilder $builder) {
            $builder->where('is_draft', false);
        });
    }
    
    protected $fillable = [
        "job_advert_id",
        "applicant_id",
        "resume_id",
        "cover_letter",
        "additional_info",
        "status_id",
        "progress_tracker",
        "is_draft",
    ];

    protected $casts = [
        'additional_info' => 'array',
        'progress_tracker' => 'array',
    ];

    public function applicant(){
        return $this->belongsTo(User::class, 'id');
    }
    public function resume(){
        return $this->belongsTo(Resume::class, 'id');
    }
    public function status(){
        return $this->belongsTo(JobApplicationStatus::class, 'id');
    }
    public function job_advert(){
        return $this->belongsTo(JobAdvert::class, 'id');
    }
    public function appointments(){
        return $this->hasMany(JobApplication::class, 'job_application_id', 'id');
    }
}
