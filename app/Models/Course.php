<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'description', 'department_id', 'year', 'semester', 'semester_id'];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }


    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_student', 'course_id', 'student_id')
            ->withPivot('semester', 'year')
            ->withTimestamps();
    }
    
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_lecturer', 'lecturer_id', 'course_id')
                    ->withPivot('semester', 'year')
                    ->withTimestamps();
    }
    
}
