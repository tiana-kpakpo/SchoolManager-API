<?php

namespace App\Policies;

use App\Models\Course;
use Illuminate\Auth\Access\Response;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{

    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
        // return $user->is_admin;
    }

    public function view(User $user, User $model): bool
    {
        return $user->is_admin || $user->id === $model->id;
    }

  
    public function create(User $user): bool
    {
        return $user->is_admin;
    }

  
    
    public function update(User $user, User $model): bool
    {
        return $user->is_admin && $user->id !== $model->id;
    }

    
    public function delete(User $user, User $model): bool
    {
        return $user->is_admin && $user->id !== $model->id;
    }

  
    public function restore(User $user, User $model): bool
    {
        return $user->is_admin;
    }

   
    public function forceDelete(User $user, User $model): bool
    {
        return $user->is_admin && $user->id !== $model->id;
    }

    public function viewCourse(User $user, Course $course)
    {
        return $user->is_lecturer && $user->courses->contains($course);
    }

    public function gradeAssignments(User $user, Course $course)
    {
        return $user->is_lecturer && $user->courses->contains($course);
    }
}
