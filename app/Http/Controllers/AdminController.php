<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('dashboard'); // غيّر اسم الفيو حسب الصفحة التي تعرض لوحة الأدمن لديك
    }
}
