<?php

namespace App\Models;

use App\Traits\UUID;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
// use Spatie\Permission\Models\Role;
// use Spatie\Permission\Models\Permission;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasName
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable, UUID;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    /** Match the column defaults so new (unsaved or not reloaded) models behave the same. */
    protected $attributes = [
        'is_active' => true,
        'is_admin' => false,
    ];

    protected $fillable = [
        'fname',
        'mname',
        'lname',
        'gender',
        'phone',
        'alt_phone',
        'email',
        'alt_email',
        'address',
        'city',
        'estate_id',
        'delivery_zone_id',
        'country_id',
        'photo_url',
        'dob',
        'status',
        'is_admin',
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

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getCartAttribute()
    {
        return $this->carts()->first();
    }

    public function getMainRoleAttribute()
    {
        return $this->roles->first();
    }

    /**
     * Filament admin (/admin): active admins only.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return (bool) $this->is_admin && (bool) $this->is_active;
    }

    public function getFilamentName(): string
    {
        return trim("{$this->fname} {$this->lname}") ?: $this->email;
    }
}
