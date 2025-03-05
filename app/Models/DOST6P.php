<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DOST6P extends Model
{
    use HasFactory;

    protected $table = 'dost_6ps';
    // Define the relationship to the researches (many-to-many)
    public function researches()
    {
        return $this->belongsToMany(Research::class, 'research_dost_6p', 'dost_6p_id', 'research_id');
    }
}
