<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;
class Resume extends Model
{
    use HasFactory, UUID;
    protected $fillable = [
        'id',
        'label',
        'url',
        "user_id",
        "is_master_copy",
    ];

    public function user(){
        return $this->belongsTo(User::class, 'id');
    }
    public function job_applications(){
        return $this->hasMany(JobApplication::class);
    }
}
