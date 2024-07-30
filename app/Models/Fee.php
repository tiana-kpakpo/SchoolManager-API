<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Fee extends Model
{
    use HasFactory;

    protected $fillable = ['amount', 'department'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
