<?php

namespace App\Models;

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
     * تصدير الطلاب إلى ملف Excel حقيقي (XML)
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
            echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:excel" xmlns="http://www.w3.org/TR/REC-html40">';
            echo '<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><style>table { border-collapse: collapse; } th, td { border: 1px solid #ddd; padding: 8px; text-align: right; }</style></head>';
            echo '<body>';
            echo '<table>';
            
            echo '<thead><tr style="background-color: #f2f2f2; font-weight: bold;">';
            echo '<th>ID</th>';
            echo '<th>اسم الطالب</th>';
            echo '<th>البريد الإلكتروني</th>';
            echo '<th>رقم الهاتف</th>';
            echo '<th>تاريخ التسجيل</th>';
            echo '</tr></thead>';

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
        $students = Student::latest()->paginate(10);
        return view('students.index', compact('students'));
    }

    /**
     * عرض صفحة إنشاء طالب جديد
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * حفظ البيانات عند إضافة طالب جديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:students,email',
            'phone'      => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'gender'     => 'required|in:male,female',
            'image'      => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        $path = null;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('uploads/students', $filename, 'public');
        }

        Student::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'birth_date' => $request->birth_date,
            'gender'     => $request->gender,
            'image'      => $path,
        ]);

        return redirect()->route('students.index')->with('success', 'تم إضافة الطالب وحفظ البيانات بنجاح.');
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
     * تحديث بيانات الطالب
     */
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:students,email,' . $student->id,
            'phone'      => 'nullable|string|max:20',
            'gender'     => 'nullable|in:male,female',
            'birth_date' => 'nullable|date',
            'image'      => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        try {
            $path = $student->image;

            if ($request->hasFile('image')) {
                if ($student->image && Storage::disk('public')->exists($student->image)) {
                    Storage::disk('public')->delete($student->image);
                }

                $file = $request->file('image');
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
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

            Log::info('Student updated successfully', ['student_id' => $student->id, 'user_id' => auth()->id()]);

            return redirect()->route('students.index')->with('success', 'تم تحديث بيانات الطالب بنجاح!');

        } catch (Throwable $e) {
            Log::error('Failed to update student', ['student_id' => $student->id, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث بيانات الطالب')->withInput();
        }
    }

    /**
     * حذف الطالب
     */
    public function destroy(Student $student)
    {
        try {
            if ($student->image && Storage::disk('public')->exists($student->image)) {
                Storage::disk('public')->delete($student->image);
            }

            $student->delete();

            Log::info('Student deleted successfully', ['deleted_student_id' => $student->id, 'user_id' => auth()->id()]);

            return redirect()->route('students.index')->with('success', 'تم حذف الطالب بنجاح!');

        } catch (Throwable $e) {
            Log::error('Failed to delete student', ['student_id' => $student->id, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف الطالب');
        }
    }
}