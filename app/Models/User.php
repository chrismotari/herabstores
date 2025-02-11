<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Spatie\Permission\Models\Role;
// use Spatie\Permission\Models\Permission;
use App\Traits\UUID;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use  HasFactory, Notifiable, UUID, HasRoles;

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
        'estate',
        "country_id",
        'photo_url',
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
    
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function favourites()
    {
        return $this->hasMany(Favourite::class);
    }

    public function getMainRoleAttribute()
    {
        return $this->roles->first();
    }

   
}
