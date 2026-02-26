<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradingScale extends Model
{
    // These are the columns we want to allow for mass-assignment
    protected $fillable = [
        'level', 
        'grade', 
        'definition', 
        'min_score', 
        'max_score', 
        'points'
    ];
}