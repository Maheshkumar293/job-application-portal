<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $table      = 'roles';
    protected $primaryKey = 'id';

    protected $allowedFields = ['role_name', 'status'];

    public function getActiveRoles()
    {
        return $this->where('status', 'active')
                    ->orderBy('role_name', 'ASC')
                    ->findAll();
    }
}
