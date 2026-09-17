<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class StudentController extends Controller
{
    /**
     * تصدير الطلاب إلى ملف Excel حقيقي (XML) يتوزع في أعمدة مستقلة
     */
    public function export(Request $request)
    {
        $fileName = 'students_' . date('Y-m-d') . '.xls';
        $students = Student::latest()->get();

        $headers = [
            "Content-Type"        => "application/vnd.ms-excel; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($students) {
            echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
            echo '<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><style>table { border-collapse: collapse; } th, td { border: 1px solid #ddd; padding: 8px; text-align: right; }</style></head>';
            echo '<body>';
            echo '<table>';
            
            // صف العناوين
            echo '<thead><tr style="background-color: #f2f2f2; font-weight: bold;">';
            echo '<th>ID</th>';
            echo '<th>اسم الطالب</th>';
            echo '<th>البريد الإلكتروني</th>';
            echo '<th>رقم الهاتف</th>';
            echo '<th>تاريخ التسجيل</th>';
            echo '</tr></thead>';

            // البيانات
            echo '<tbody>';
            foreach ($students as $student) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($student->id) . '</td>';
                echo '<td>' . htmlspecialchars($student->name) . '</td>';
                echo '<td>' . htmlspecialchars($student->email ?? 'غير متوفر') . '</td>';
                echo '<td>' . htmlspecialchars($student->phone ?? 'غير متوفر') . '</td>';
                echo '<td>' . ($student->created_at ? $student->created_at->format('Y-m-d') : '') . '</td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
            echo '</body>';
            echo '</html>';
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * عرض قائمة الطلاب
     */
    public function index()
    {
        $students = Student::latest()->get();
        return view('students.index', compact('students'));
        $student = Auth::user();    
    }

    /**
     * عرض صفحة إنشاء طالب جديد
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * حفظ البيانات عند إضافة طالب جديد (مع الرفع الآمن للملفات)
     */
    public function store(Request $request)
        {

    //     dd([
    //     'all_request' => $request->all(),
    //     'has_file' => $request->hasFile('image'),
    //     'file_object' => $request->file('image')
    // ]); 
    
            // 1. التحقق من صحة المدخلات والملف
            $request->validate([
                'name'       => 'required|string|max:255',
                'email'      => 'required|email|unique:students,email',
                'phone'      => 'nullable|string',
                'birth_date' => 'nullable|date',
                'gender'     => 'required|in:male,female',
                'image'      => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
            ]);

            $path = null;

            // 2. معالجة وحفظ الملف بشكل آمن
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $file = $request->file('image');
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('uploads/students', $filename, 'public');
            } else {
                return back()->withErrors(['image' => 'يجب رفع ملف صحيح أو صورة صالحة.'])->withInput();
            }

            // 3. حفظ البيانات في قاعدة البيانات
            Student::create([
                'name'       => $request->name,
                'email'      => $request->email,
                'phone'      => $request->phone,
                'birth_date' => $request->birth_date,
                'gender'     => $request->gender,
                'image'      => $path,
            ]);

            return redirect()->route('students.index')->with('success', 'تم إضافة الطالب وحفظ الملف بنجاح.');
        }

    /**
     * عرض تفاصيل طالب معين
     */
    public function show(Student $student)
    {
        return view('students.show', compact('student'));
    }

    /**
     * عرض صفحة تعديل بيانات الطالب
     */
    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    /**
     * تحديث بيانات الطالب في قاعدة البيانات (مع التنظيف الآلي للملف القديم)
     */
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:students,email,' . $student->getKey(),
            'phone'      => 'nullable|string|max:20',
            'gender'     => 'nullable|in:male,female',
            'birth_date' => 'nullable|date',
            'image'      => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
        ]);

        try {
            $path = $student->image; // الاحتفاظ بالصورة القديمة افتراضياً

            if ($request->hasFile('image')) {
                // حذف الملف القديم إذا وجد
                if ($student->image && Storage::disk('public')->exists($student->image)) {
                    Storage::disk('public')->delete($student->image);
                }

                $file = $request->file('image');
                $filename = \Illuminate\Support\Str::uuid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('uploads/students', $filename, 'public');
            }

            $student->update([
                'name'       => $request->name,
                'email'      => $request->email,
                'phone'      => $request->phone,
                'gender'     => $request->gender ?? $student->gender,
                'birth_date' => $request->birth_date ?? $student->birth_date,
                'image'      => $path,
            ]);

            Log::info('Student updated successfully', [
                'student_id' => $student->getKey(),
                'user_id'    => auth()->id()
            ]);

            return redirect()->route('students.index')->with('success', 'تم تحديث بيانات الطالب بنجاح!');

        } catch (Throwable $e) {
            Log::error('Failed to update student', [
                'student_id' => $student->getKey(),
                'error'      => $e->getMessage(),
                'user_id'    => auth()->id()
            ]);

            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث بيانات الطالب')->withInput();
        }
    }

    /**
     * حذف الطالب من قاعدة البيانات
     */
    public function destroy(Student $student)
    {
        try {
            $studentId = $student->getKey();
            $student->delete(); // الـ Observer سيتكفل بحذف الملف المرفق تلقائياً

            Log::info('Student deleted successfully', [
                'deleted_student_id' => $studentId,
                'user_id'            => auth()->id()
            ]);

            return redirect()->route('students.index')->with('success', 'تم حذف الطالب بنجاح!');

        } catch (Throwable $e) {
            Log::error('Failed to delete student', [
                'student_id' => $student->getKey(),
                'error'      => $e->getMessage(),
                'user_id'    => auth()->id()
            ]);

            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف الطالب');
        }
    }
}