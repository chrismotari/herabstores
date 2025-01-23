<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplicationStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'created_by_id',
        'description',
    ];

    public function jobs(){
        return $this->hasMany(Job::class, 'status_id', 'id');
    }
    public function created_by(){
        return $this->belongsTo(User::class, 'id', 'created_by_id'); // (foreign, local)
    }
}
