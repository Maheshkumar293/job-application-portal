<?php

namespace App\Models;

use CodeIgniter\Model;

class ApplicationAssignmentModel extends Model
{
    protected $table = 'application_staff_assignments';
    protected $allowedFields = [
        'application_id',
        'staff_id',
        'assigned_by'
    ];
}
