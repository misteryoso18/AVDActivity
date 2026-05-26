<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    protected $fillable = [
        'fname', 
        'mname', 
        'lname', 
        'email',
        'contact_no', 
        'degree_id',
        'user_account_id'
    ];

    /**
     * Get the user account for this student.
     */
    public function userAccount(): BelongsTo
    {
        return $this->belongsTo(UserAccount::class, 'user_account_id');
    }

    /**
     * Get the degree for the student.
     */
    public function degree(): BelongsTo
    {
        return $this->belongsTo(Degree::class, 'degree_id');
    }

    /**
     * Get the courses for the student.
     */
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course__students', 'student_id', 'course_id');
    }
}