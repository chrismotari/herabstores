<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;
class Appointment extends Model
{
    use HasFactory, UUID;

    protected $fillable = [
        "name",
        "job_application_id",
        "appointment_at",
        "is_selected",
        "description",
        "created_by_id",
    ];

    public function job_application(): BelongsTo{
        return $this->belongsTo(JobApplication::class, 'id', "job_application_id");
    }
    public function created_by(): BelongsTo{
        return $this->belongsTo(User::class, 'id', "created_by_id");
    }
}
