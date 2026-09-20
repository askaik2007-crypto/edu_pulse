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

Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('student.dashboard');

// توجيه الزائر تلقائياً لوحة التحكم أو صفحة تسجيل الدخول
Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/attendances/report', [AttendanceController::class, 'report'])->name('attendances.report');

// صفحة لوحة التحكم المحمية بتسجيل الدخول للأدمن
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ==========================================
// 1. مسارات لوحة تحكم المعلمين (Teacher Portal)
// ==========================================
Route::prefix('teacher')->name('teacher.')->group(function () {
    // صفحة تسجيل الدخول الخاصة بالمعلم
    Route::get('/login', [TeacherController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [TeacherController::class, 'login'])->name('login.submit');
    
    // مسارات المعلم المحمية
    Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [TeacherController::class, 'logout'])->name('logout');
});

// ==========================================
// 2. مسارات النظام العامة والخاصة بالأدمن (Admin Panel)
// ==========================================
Route::middleware(['auth', 'verified'])->group(function () {
    
    // إدارة الملف الشخصي
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // مسارات خاصة إضافية (تصدير وطباعة - يجب أن تكون دائماً قبل الـ Resource)
    Route::get('students/export', [StudentController::class, 'export'])->name('students.export');
    Route::get('payments/{payment}/print', [PaymentController::class, 'print'])->name('payments.print');

    // مسارات النظام الأساسية (Resources)
    Route::resource('students', StudentController::class);
    Route::resource('courses', CourseController::class);
    Route::resource('teachers', TeacherController::class); // يولد تلقائياً teachers.store
    Route::resource('categories', CategoryController::class);
    Route::resource('class-rooms', ClassRoomController::class);
    Route::resource('course-classes', CourseClassController::class);
    Route::resource('enrollments', EnrollmentController::class);
    Route::resource('attendances', AttendanceController::class);
    Route::resource('payments', PaymentController::class);
});

Route::get('/admin/dashboard', [AdminController::class, 'index'])
    ->middleware(['auth'])
    ->name('admin.dashboard');    

require __DIR__.'/auth.php';