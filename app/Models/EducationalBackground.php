<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationalBackground extends Model
{
    use HasFactory;

    // Define the table name
    protected $table = 'educational_backgrounds';

    // Mass assignable fields
    protected $fillable = [
        'researcher_id',
        'past_college',
        'course',
        'education_year',
    ];

    // Define the relationship with the researcher
    public function researcher()
    {
        return $this->belongsTo(Researcher::class);
    }
}
