<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workplace extends Model
{
    use HasFactory;

    // Define the table name
    protected $table = 'workplaces';

    // Mass assignable fields
    protected $fillable = [
        'researcher_id',
        'name',
        'position',
        'year',
    ];

    // Define the relationship with the researcher
    public function researcher()
    {
        return $this->belongsTo(Researcher::class);
    }
}
