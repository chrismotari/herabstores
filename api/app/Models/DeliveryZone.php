<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryZone extends Model
{
    use HasFactory, UUID;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'delivery_fees',
        'estate_id',
        'county_id',
        'is_active',
    ];

    protected $casts = ['is_active' => 'boolean', 'delivery_fees' => 'decimal:2'];

    public function orders()
    {
        return $this->hasMany(Order::class, 'delivery_zone');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function estate()
    {
        return $this->belongsTo(Estate::class);
    }

    public function county()
    {
        return $this->belongsTo(County::class);
    }

    public function getCoverageAttribute()
    {
        if ($this->estate) {
            return $this->estate->name.' (Estate)';
        } elseif ($this->county) {
            return $this->county->name.' (County)';
        }

        return 'No Coverage';
    }
}
