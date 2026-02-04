<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Property extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address', 'owner_id'];

    // Relationship: Property has many Units
    public function units()
    {
        return $this->hasMany(Unit::class);
    }
}
