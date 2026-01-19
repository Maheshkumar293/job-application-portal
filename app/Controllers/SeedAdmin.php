<?php

namespace App\Controllers;

use App\Models\UserModel;

class SeedAdmin extends BaseController
{
    public function index()
    {
        (new UserModel())->insert([
            'name' => 'System Admin2',
            'email' => 'admin@jobportal1.com',
            'password_hash' => password_hash('Admin@123', PASSWORD_DEFAULT),
            'role' => 'admin',
            'status' => 'active'
        ]);

        return 'Admin created';
    }
}
