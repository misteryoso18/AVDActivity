<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Degree extends Model
{
    protected $fillable = [
        'title',
    ];

    /**
     * Get the students for the degree.
     */
    public function students() {
        return $this->hasMany(Student::class, 'degree_id');
    }
}