<?php

namespace App\Models;

use CodeIgniter\Model;

class AcademicQualificationModel extends Model
{
    protected $table = 'academic_qualifications';
    protected $allowedFields = [
        'application_id',
        'qualification',
        'institution',
        'graduation_year',
        'cgpa'
    ];
}
