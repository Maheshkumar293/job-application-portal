<?php

namespace App\Models;

use CodeIgniter\Model;

class CandidateSkillModel extends Model
{
    protected $table = 'candidate_skills';
    protected $allowedFields = [
        'application_id',
        'skill'
    ];
}
