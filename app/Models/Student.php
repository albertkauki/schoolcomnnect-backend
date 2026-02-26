<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Student extends Model
{
    protected $fillable = [
        'first_name', 'middle_name', 'last_name', 'gender', 'date_of_birth',
        'prem_number', 'necta_index_number', 'school_class_id', // Use ID now
        'parent_name', 'parent_phone', 'status', 'registration_number',
    ];

    // Relationship to school class
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    // Relationship to subjects (many-to-many)
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_student')
                    ->withTimestamps();
    }

    public function results()
    {
        return $this->hasMany(Results::class, 'student_id');
    }

    protected static function booted()
    {
        static::creating(function ($student) {
            // Load the class details for registration number generation
            $class = SchoolClass::find($student->school_class_id);
            
            $prefix = 'ARC';
            $form = $class->form;
            $stream = strtoupper($class->stream ?? 'A'); // Default to A if no stream
            $year = Carbon::now()->format('Y');

            // Find the last student in THIS specific class ID for THIS year
            $lastStudent = static::where('school_class_id', $student->school_class_id)
                ->whereYear('created_at', $year)
                ->orderBy('registration_number', 'desc')
                ->first();

            if ($lastStudent) {
                // ARC/1/A/005/2026 -> extract 005
                $parts = explode('/', $lastStudent->registration_number);
                $lastNumber = isset($parts[3]) ? (int)$parts[3] : 0;
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            // Generate the clean Registration Number
            $student->registration_number = sprintf(
                "%s/%s/%s/%03d/%s",
                $prefix, $form, $stream, $nextNumber, $year
            );
        });
    }
}
