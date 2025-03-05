<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SDG extends Model
{
    use HasFactory;

    protected $table = 'sdgs';

    // Define the relationship to the researches (many-to-many)
    public function researches()
    {
        return $this->belongsToMany(Researcher::class, 'research_sdg', 'sdg_id', 'research_id');
    }
}
