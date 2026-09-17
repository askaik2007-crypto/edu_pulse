<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'name',
        'specialization',
        'email',
        'phone',
        'role',    
        'password',
    ];

    public function classes()
    {
        return $this->hasMany(CourseClass::class);
    }
}
