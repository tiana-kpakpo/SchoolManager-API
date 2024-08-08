<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password', 'profile_picture', 'phone', 'address', 'birth_date', 'age',
        'is_admin', 'nationality', 'department', 'qualification', 'student_id', 'year_of_study', 'guardian_contact',
        'date_of_admission', 'outstanding_fees'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function hasPermission($permission)
    {
        return $this->roles()->whereHas('permissions', function ($query) use ($permission) {
            $query->where('name', $permission);
        })->exists();
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department', 'name');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $fees = Fee::where('department', $user->department)->first();
            if ($fees) {
                $user->outstanding_fees = $fees->amount;
            }
        });

        static::updating(function ($user) {
            if ($user->isDirty('department')) {
                $fee = Fee::where('department', $user->department)->first();
                if ($fee) {
                    $user->outstanding_fees = $fee->amount;
                }
            }
        });
    }


    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_student', 'student_id', 'course_id')
            ->withPivot('semester', 'year')
            ->withTimestamps();
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'user_id');
    }


    public function getOutstandingFeesAttribute()
    {
        if ($this->is_admin) {
            return 0;
        }

        $department = $this->department;

        $department = Department::where('name', $this->department)->first();


        if ($department) {
            $fees = Fee::where('department_id', $department->id)->first();
            if ($fees) {
                $initialFees = $fees->amount;
                $paidAmount = $this->payments()->sum('amount');
                return $initialFees - $paidAmount;
            }
        }

        return 0;
    }

    // public function submissions()
    // {
    //     return $this->hasMany(Submission::class);
    // }
}
