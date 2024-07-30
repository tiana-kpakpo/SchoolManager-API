<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = ['assignment_id', 'student_id', 'file_path'];

    public function assignment():BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    public function student():BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function grade():HasOne
    {
        return $this->hasOne(Grade::class);
    }
}
