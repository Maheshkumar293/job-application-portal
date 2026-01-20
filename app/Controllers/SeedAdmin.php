<?php

namespace App\Controllers;

use App\Models\UserModel;

class SeedAdmin extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        
        // Check if admin already exists
        $existing = $userModel->where('email', 'admin@jobportal1.com')->first();
        if ($existing) {
            return 'Admin already exists with ID: ' . $existing['id'];
        }
        
        $insertId = $userModel->insert([
            'name' => 'System Admin2',
            'email' => 'admin@jobportal1.com',
            'password_hash' => password_hash('Admin@123', PASSWORD_DEFAULT),
            'role' => 'admin',
            'status' => 'active'
        ]);
        
        if ($insertId) {
            return 'Admin created successfully with ID: ' . $insertId;
        } else {
            return 'Failed to create admin. Check UserModel.';
        }
    }
}
