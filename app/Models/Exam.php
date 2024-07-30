<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'exam_date'];


    public function courses()
    {
        return $this->belongsToMany(Course::class, 'exam_course');
    }
}
