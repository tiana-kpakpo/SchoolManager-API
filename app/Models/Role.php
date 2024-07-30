<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = ['name'];

    const IS_ADMIN = 'admin';
    const IS_LECTURER = 'lecturer';
    const IS_STUDENT = 'student';


    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user');
    }
}
