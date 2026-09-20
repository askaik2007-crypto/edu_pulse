<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\CourseClassController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentDashboardController;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

// توجيه الزائر تلقائياً لوحة التحكم أو صفحة تسجيل الدخول
Route::get('/', function () {
    return redirect()->route('login');
});

// ==========================================
// لوحات التحكم الرئيسية بناءً على الـ Roles
// ==========================================

// 1. لوحة تحكم الأدمن
Route::get('/admin/dashboard', [AdminController::class, 'index'])
    ->middleware(['auth'])
    ->name('admin.dashboard');

// 2. لوحة تحكم المعلم
Route::get('/teacher/dashboard', [TeacherController::class, 'dashboard'])
    ->middleware(['auth'])
    ->name('teacher.dashboard');

// 3. لوحة تحكم الطالب
Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('student.dashboard');

// 4. لوحة التحكم العامة
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// ==========================================
// مسارات النظام العامة والخاصة بالأدمن (Admin Panel)
// ==========================================
Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/attendances/report', [AttendanceController::class, 'report'])->name('attendances.report');

    // إدارة الملف الشخصي
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // مسارات خاصة إضافية
    Route::get('students/export', [StudentController::class, 'export'])->name('students.export');
    Route::get('payments/{payment}/print', [PaymentController::class, 'print'])->name('payments.print');

    // مسارات النظام الأساسية (Resources)
    Route::resource('students', StudentController::class);
    Route::resource('courses', CourseController::class);
    Route::resource('teachers', TeacherController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('class-rooms', ClassRoomController::class);
    Route::resource('course-classes', CourseClassController::class);
    Route::resource('enrollments', EnrollmentController::class);
    Route::resource('attendances', AttendanceController::class);
    Route::resource('payments', PaymentController::class);
});

require __DIR__.'/auth.php';

// مسار إعداد المستخدمين الأولي للاختبار
Route::get('/setup-users', function () {
    User::updateOrCreate(
        ['email' => 'admin@gmail.com'],
        [
            'name' => 'Admin User',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
        ]
    );

    User::updateOrCreate(
        ['email' => 'teacher@gmail.com'],
        [
            'name' => 'Teacher User',
            'password' => Hash::make('12345678'),
            'role' => 'teacher',
        ]
    );

    User::updateOrCreate(
        ['email' => 'student@gmail.com'],
        [
            'name' => 'Student User',
            'password' => Hash::make('12345678'),
            'role' => 'student',
        ]
    );

    return 'تم إنشاء جميع الحسابات بنجاح! كلمة المرور هي: 12345678';
});