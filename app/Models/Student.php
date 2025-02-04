<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['fio', 'birthdate', 'contact', 'status'];

    /**
     * Get the courses that the student is enrolled in.
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class)->withPivot('status');
    }

    /**
     * Get the payments made by the student.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
