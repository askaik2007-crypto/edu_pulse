<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index()
    {
        // جلب الطالب الحالي المسجل دخوله فقط
        $student = Auth::user();
        return view('students.dashboard', compact('student'));
    }
}
