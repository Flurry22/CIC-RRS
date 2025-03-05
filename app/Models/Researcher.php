<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Researcher extends Authenticatable
{
    use Notifiable;
    use HasFactory;

    protected $fillable = [
        'name',
        'first_name', 'last_name',
        'position',
        'email',
        'password',
        'profile_picture',
        'bio',
        'skills',
    ];
   
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['email_verified_at' => 'datetime','skills' => 'array',];

  
    // Relationship for leading researches
    public function researchesAsLeader()
    {
        return $this->hasMany(Research::class, 'leader_id');
    }

    // Many-to-many relationship for being part of multiple researches
    public function researches()
    {
        return $this->belongsToMany(Research::class, 'researcher_research', 'researcher_id', 'research_id');
    }

    public function programs()
    {
        return $this->belongsToMany(Program::class, 'program_researcher');
    }
}
