<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class examconfiguration extends Model
{
    protected $fillable = ['name', 'slug', 'term', 'academic_year', 'weight', 'is_active'];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($examConfig) {
            $examConfig->slug = \Str::slug($examConfig->name);
        });
    }
}
