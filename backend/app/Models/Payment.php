<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $guarded = []; // Allow mass assignment for all fields  

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
