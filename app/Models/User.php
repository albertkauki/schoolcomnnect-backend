<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'status',
        'phone_number',
        'gender',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relationship: Subjects assigned to this user.
     * We add a check to make sure this only makes sense for teachers.
     */
 public function subjects()
{
    return $this->belongsToMany(Subject::class, 'subject_user')
                ->withPivot('school_class_id', 'academic_year')
                ->withTimestamps();
}

    /**
     * Helper to check if the user is a teacher
     */
    public function isTeacher()
    {
        return $this->role === 'class_teacher';
    }

    /**
     * Helper to check if the user is an admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
