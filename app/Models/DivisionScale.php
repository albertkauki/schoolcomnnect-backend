<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DivisionScale extends Model
{
    protected $fillable = [
        'level', 
        'division', 
        'min_points', 
        'max_points', 
        'description'
    ];
}