<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_year',
        'first_sem_start',
        'first_sem_end',
        'second_sem_start',
        'second_sem_end',
        'off_sem_start',
        'off_sem_end',
    ];

    public function researches()
    {
        return $this->hasMany(Research::class);
    }

    public function researchers()
    {
        return $this->belongsToMany(Researcher::class, 'researcher_status')
                    ->withPivot('active')
                    ->withTimestamps();
    }
}
