<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'gender',
        'birth_date',
        'image',
        'status',
    ];

    /**
     * علاقة الطالب بالتسجيلات
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * علاقة الطالب بالمدفوعات
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * علاقة الطالب بسجلات الحضور
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}