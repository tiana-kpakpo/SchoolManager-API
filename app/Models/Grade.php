<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = ['submission_id', 'grade'];

    public function submission():BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }
    
}
