<?php

namespace App\Observers;

use App\Models\Student;

class StudentObserver
{
    
    /**
     * Handle the Student "created" event.
     */
    public function created(Student $student): void
    {
        //
    }

    /**
     * Handle the Student "updated" event.
     */
    public function updated(Student $student): void
    {
        //
    }

    /**
     * Handle the Student "deleted" event.
     */
    public function deleted(Student $student): void
    {
        // حذف الملف نهائياً من الـ Storage عند حذف السجل من قاعدة البيانات
        if ($student->image && Storage::disk('public')->exists($student->image)) {
            Storage::disk('public')->delete($student->image);
        }
    }

    /**
     * Handle the Student "restored" event.
     */
    public function restored(Student $student): void
    
    {
        
    }

    /**
     * Handle the Student "force deleted" event.
     */
    public function forceDeleted(Student $student): void
    {
        //
    }
}
