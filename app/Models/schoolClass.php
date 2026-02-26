<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
 protected $table = 'school_classes';
    protected $fillable = ['level', 'form', 'stream', 'combination_id'];

    public function combination() {
        return $this->belongsTo(Combination::class);
    }

    protected static function boot() {
        parent::boot();
        static::saving(function ($model) {
            $model->generateName();
        });
    }

    public function generateName() {
        $parts = ["Form {$this->form}"];

        if ($this->level === 'A-Level' && $this->combination_id) {
            // Ensure combination relation is loaded to get the name
            $parts[] = $this->combination ? $this->combination->name : '';
        }

        if (!empty($this->stream)) {
            $parts[] = strtoupper($this->stream);
        }

        // array_filter removes empty strings, implode joins with one space
        $this->name = implode(' ', array_filter($parts));
    }
    public function teachers()
{
    // This allows you to do $class->teachers to see everyone assigned here
    return $this->belongsToMany(User::class, 'subject_user')
                ->withPivot('subject_id', 'academic_year')
                ->withTimestamps();
}
}