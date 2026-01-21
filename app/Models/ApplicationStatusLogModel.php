<?php

namespace App\Models;

use CodeIgniter\Model;

class ApplicationStatusLogModel extends Model
{
    protected $table = 'application_status_logs';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'application_id',
        'old_status',
        'new_status',
        'changed_by',
        'changed_by_role',
        'created_at'
    ];

    protected $useTimestamps = false;

    protected $returnType = 'array';
}
