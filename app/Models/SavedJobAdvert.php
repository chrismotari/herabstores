<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;

class SavedJobAdvert extends Model
{
    use HasFactory, UUID;

    protected $fillable = [
        'user_id',
        'job_advert_id',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'id', 'user_id'); // (foreign, local)
    }

    public function job_advert(){
        return $this->belongsTo(JobAdvert::class, 'id', 'job_advert_id'); // (foreign, local)
    }
}
