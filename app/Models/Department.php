<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Department extends Model
{
    use HasFactory;

   protected $fillable = ['name', 'code', 'faculty_id'];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function fees()
    {
        return $this->hasOne(Fee::class);
    }

    public function faculty() :BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }
   
}
