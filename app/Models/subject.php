<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['name', 'subject_code', 'level', 'category'];

    /**
     * Relationship: A subject can belong to many combinations (e.g., Physics is in PCM and PGM)
     */
    public function combinations()
    {
        return $this->belongsToMany(Combination::class)
                    ->withPivot('type')
                    ->withTimestamps();
    }

    /**
     * Relationship: A subject is assigned to many students
     */
    public function students()
    {
        return $this->belongsToMany(Student::class, 'subject_student')
                    ->withTimestamps();
    }

    /**
     * Relationship: A subject is taught in many school classes (via teacher assignments)
     */
    public function schoolClasses()
    {
        return $this->belongsToMany(SchoolClass::class, 'subject_user')
                    ->withPivot('user_id', 'academic_year')
                    ->withTimestamps();
    }

    /**
     * Relationship: Teachers assigned to teach this subject
     */
    public function teachers()
    {
        return $this->belongsToMany(User::class, 'subject_user')
                    ->withPivot('school_class_id', 'academic_year')
                    ->withTimestamps();
    }

    // Helper to get only O-Level subjects
    public function scopeOLevel($query)
    {
        return $query->where('level', 'O-Level');
    }

    // Helper to get only A-Level subjects
    public function scopeALevel($query)
    {
        return $query->where('level', 'A-Level');
    }
}