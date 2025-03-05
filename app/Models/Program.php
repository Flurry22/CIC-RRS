<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $table = 'programs';  // Explicitly define the table name (if needed)

    // Fillable attributes to protect against mass-assignment vulnerabilities
    protected $fillable = [
        'name',
        'level',
    ];

    /**
     * A program has many researches.
     */
    public function researches()
    {
        return $this->hasMany(Research::class);
    }
    public function researchers()
    {
        return $this->belongsToMany(Researcher::class, 'program_researcher');
    }
}
