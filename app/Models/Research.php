<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Research extends Model
{
    use HasFactory;

    // Explicitly define the table name (if it's not the plural form of the model)
    protected $table = 'researches';  

    // Fillable attributes to protect against mass-assignment vulnerabilities
    protected $fillable = [
        'title',
        'description',
        'budget',
        'leader_id',  
        'type',
        'funding_type_id',
        'deadline',
        'project_duration',
        'status',
        'certificate_of_utilization',
        'special_order',
        'approved_date', 
        'terminal_file', 
        'approved_file',
        'proposal_file',
        'funded_by',
        'school_year_id',
        'semester',
    ];

    /**
     * A research belongs to a program.
     */
    public function programs()
{
    return $this->belongsToMany(Program::class, 'program_research', 'research_id', 'program_id');
}

    /**
     * A research has one leader, who is a researcher.
     */
    public function leader()
    {
        return $this->belongsTo(Researcher::class, 'leader_id');
    }

    /**
     * A research has many researchers (members), many-to-many relationship.
     * The pivot table is 'researcher_research'.
     */
    public function researchers()
    {
        return $this->belongsToMany(Researcher::class, 'researcher_research', 'research_id', 'researcher_id');
    }


    /**
     * A research belongs to a funding type.
     */
    public function fundingType()
    {
        return $this->belongsTo(FundingType::class, 'funding_type_id');
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function sdgs()
    {
        return $this->belongsToMany(SDG::class, 'research_sdg', 'research_id', 'sdg_id');
    }

    // Define many-to-many relationship with DOST6P
    public function dost6ps()
{
    return $this->belongsToMany(DOST6P::class, 'research_dost_6p', 'research_id', 'dost_6p_id');
}
public function members() {
    return $this->belongsToMany(Researcher::class, 'researcher_research')
                ->withPivot('role') // Include pivot fields if needed
                ->where('role', 'Member'); // Fetch only members
}
}
