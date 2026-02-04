<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Unit model (already done partially)
class Unit extends Model
{
    use HasFactory;

    protected $fillable = ['property_id', 'unit_number', 'size', 'rent', 'status'];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }
}

