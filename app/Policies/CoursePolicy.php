<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    public function viewCourses(User $user): bool
    {
        return true;
        // return $user->is_admin || $user->hasRole('student');
    }

  
    public function view(User $user, Course $course): bool
    {
        return $user->is_admin || $user->courses->contains($course);
    }

   
    public function create(User $user): bool
    {
       return $user->is_admin;
    }

   
    public function update(User $user, Course $course): bool
    {
        return $user->is_admin;
    }

 
    public function delete(User $user, Course $course): bool
    {
        return $user->is_admin;
    }

   
    public function restore(User $user, Course $course): bool
    {
        return $user->is_admin;
    }

    
    public function forceDelete(User $user, Course $course): bool
    {
        return $user->is_admin;
    }
}
