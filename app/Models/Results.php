<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Results extends Model
{
    protected $fillable = [
        'student_id',
        'subject_id',
        'examconfiguration_id',
        'score',
        'grade',
        'points',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function examconfiguration()
    {
        return $this->belongsTo(Examconfiguration::class);
    }
}
