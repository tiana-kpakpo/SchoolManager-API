<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'due_date'];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'assignment_course');
    }

    public function submissions():HasMany
    {
        return $this->hasMany(Submission::class);
    }
}
