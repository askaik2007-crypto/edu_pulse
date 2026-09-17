<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::latest()->get();
        return view('teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('teachers.create');
    }

   public function store(Request $request)
    {
        // 1. التحقق من البيانات لمنع التكرار قبل الوصول للداتابيز
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'email'          => 'required|email|unique:teachers,email', // يمنع تكرار الإيميل
            'phone'          => 'nullable|string',
        ]);

        // 2. الحفظ في قاعدة البيانات
        \App\Models\Teacher::create($validated);

        // 3. العودة مع رسالة نجاح
        return redirect()->route('teachers.index')->with('success', 'تم إضافة المحاضر بنجاح!');    }

    public function edit(Teacher $teacher)
    {
        return view('teachers.edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email,' . $teacher->id,
            'phone' => 'nullable|string|max:20',
            'specialization' => 'required|string|max:255',
        ]);

        $teacher->update($request->all());

        return redirect()->route('teachers.index')->with('success', 'تم تعديل بيانات المحاضر بنجاح!');
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->delete();
        return redirect()->route('teachers.index')->with('success', 'تم حذف المحاضر بنجاح!');
    }
    // --- الدوال الخاصة بلوحة تحكم المحاضر المستقلة ---

    // 1. عرض صفحة تسجيل الدخول للمحاضر
    public function showLoginForm()
    {
        return view('teachers.login');
    }

    public function login(Request $request)
    {
    // 1. التحقق من البيانات المدخلة
    $request->validate([
        'email' => 'required|email',
        'phone' => 'required',
    ]);

    // 2. البحث عن المعلم في قاعدة البيانات
    $teacher = Teacher::where('email', $request->email)
                      ->where('phone', $request->phone)
                      ->first();

    // 3. إذا لم يتم العثور على المعلم، نرجعه مع رسالة خطأ
    if (!$teacher) {
        return back()->withErrors(['email' => 'بيانات الدخول غير صحيحة.'])->withInput();
    }

    // 4. إذا وجدناه، نحفظ ID المعلم في الجلسة ونحوه للوحة التحكم
    session(['teacher_id' => $teacher->id]);{
        return redirect()->route('teacher.dashboard');
        return redirect()->route('teacher.dashboard');
    }
    
    
    
    
    // 4. إذا كانت البيانات خطأ، نرجعه مع رسالة خطأ
    return back()->withErrors(['email' => 'بيانات الدخول غير صحيحة.'])->withInput();
    
    // حفظ ID المعلم في الجلسة (Session) خاصة به لوحده
    // session(['teacher_id' => $teacher->id]);
    
    // return redirect()->route('teacher.dashboard');
    
    
    }
    
    // 3. عرض لوحة تحكم المحاضر الخاصة به
    public function dashboard()
    {
        $teacherId = session('teacher_id');
        if (!$teacherId) {
            return redirect()->route('teacher.login');
        }

        $teacher = Teacher::findOrFail($teacherId);
        
        // يمكننا جلب الشُعب أو الدورات المرتبطة بهذا المعلم لاحقاً
        return view('teachers.dashboard', compact('teacher'));

        return view('');
    }

    // 4. تسجيل خروج المحاضر
    public function logout()
    {
        session()->forget('teacher_id');
        return redirect()->route('teacher.login');
    }
    
}