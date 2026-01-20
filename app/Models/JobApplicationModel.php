<?php

namespace App\Models;

use CodeIgniter\Model;

class JobApplicationModel extends Model
{
    protected $table = 'job_applications';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id',
        'role_id',
        'full_name',
        'email',
        'mobile',
        'dob',
        'gender',
        'address',
        'nationality',
        'relocate',
        'experience',
        'employment_status',
        'previous_org',
        'job_title',
        'summary',
        'resume_path',
        'application_status'
    ];

    protected $useTimestamps = false;
}
