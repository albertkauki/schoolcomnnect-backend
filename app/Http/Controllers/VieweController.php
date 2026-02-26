<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class VieweController extends Controller
{
    public function showDashboard()
    {
        $user = auth()->user();
        if ($user && ($user->role ?? '') === 'class_teacher') {
            return view('dashboard.teacher');
        }
        return view('dashboard/welcome');
    }

    public function showStudents()
    {
        return view('students/students');
    }

   
   
    public function showTeachers()
    {
        return view('teachers/teachers');
    }

    public function showRules()
    {
        return view('rules/rules');
    }
    public function showLogin()
    {
        return view('auth/login');
    }
    public function showSubjects()
    {
        return view('subjects/subjects');
    }
    public function showClasses()
    {
        return view('classes/classes');
    }

    
}
