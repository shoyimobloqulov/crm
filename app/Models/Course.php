<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    /**
     * Get the students enrolled in the course.
     */
    public function students()
    {
        return $this->belongsToMany(Student::class)->withPivot('status');
    }
}
