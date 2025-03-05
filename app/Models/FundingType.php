<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundingType extends Model
{
    use HasFactory;

    protected $table = 'funding_types';  

    
    protected $fillable = [
        'type',  
    ];

    
    public function researches()
    {
        return $this->hasMany(Research::class);
    }
}
