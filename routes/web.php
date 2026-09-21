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

// توجيه الزائر تلقائياً لصفحة الطلاب مباشرة للتجربة بسهولة
Route::get('/', function () {
    return redirect()->route('students.index');
});

// ==========================================
// 1. مسار إعداد المستخدمين وتأكيدهم آلياً
// ==========================================
Route::get('/setup-users', function () {
    User::updateOrCreate(
        ['email' => 'admin@gmail.com'],
        [
            'name' => 'Admin User',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'email_verified_at' => now(), // إضافة تاريخ التأكيد لتجنب خطأ verified
        ]
    );

    User::updateOrCreate(
        ['email' => 'teacher@gmail.com'],
        [
            'name' => 'Teacher User',
            'password' => Hash::make('12345678'),
            'role' => 'teacher',
            'email_verified_at' => now(),
        ]
    );

    User::updateOrCreate(
        ['email' => 'student@gmail.com'],
        [
            'name' => 'Student User',
            'password' => Hash::make('12345678'),
            'role' => 'student',
            'email_verified_at' => now(),
        ]
    );

    return 'تم إنشاء وتأكيد جميع الحسابات بنجاح! كلمة المرور هي: 12345678';
});

// ==========================================
// 2. لوحات التحكم الرئيسية
// ==========================================
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/admin/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('admin.dashboard');

Route::get('/teacher/dashboard', function () {
    return view('teachers.dashboard');
})->middleware(['auth'])->name('teacher.dashboard');

Route::get('/student/dashboard', function () {
    return view('students.dashboard');
})->middleware(['auth'])->name('student.dashboard');

// ==========================================
// 3. مسارات النظام المحمية بـ Auth فقط
// ==========================================
Route::middleware(['auth'])->group(function () {
    
    Route::get('/attendances/report', [AttendanceController::class, 'report'])->name('attendances.report');

    // إدارة الملف الشخصي
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // تصدير وطباعة
    Route::get('students/export', [StudentController::class, 'export'])->name('students.export');
    Route::get('payments/{payment}/print', [PaymentController::class, 'print'])->name('payments.print');

    // باقي مسارات النظام
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
Route::post('/teacher/logout', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])
    ->name('teacher.logout');

require __DIR__.'/auth.php';