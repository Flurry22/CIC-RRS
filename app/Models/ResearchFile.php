<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResearchFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'research_file',
    ];
}
