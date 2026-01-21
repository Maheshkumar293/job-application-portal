<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    /* ---------------- REGISTER FORM ---------------- */
    public function register()
    {
        return view('auth/register');
    }

    /* ---------------- REGISTER STORE ---------------- */
    public function store()
    {
        $rules = [
            'name' => 'required|min_length[3]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();

        $insert = $userModel->insert([
            'name' => trim($this->request->getPost('name')),
            'email' => strtolower(trim($this->request->getPost('email'))),
            'password_hash' => password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            ),
            'role' => 'candidate',
            'status' => 'active'
        ]);

        if (!$insert) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Database insert failed');
        }

        return redirect()->to(base_url('login'))
            ->with('success', 'Registration successful. Please login.');
    }

    /* ---------------- LOGIN FORM ---------------- */
    public function login()
    {
        return view('auth/login');
    }

    /* ---------------- LOGIN PROCESS ---------------- */
    public function authenticate()
    {
        $email = strtolower(trim($this->request->getPost('email')));
        $password = $this->request->getPost('password');

        $user = (new UserModel())
            ->where('email', $email)
            ->where('status', 'active')
            ->first();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Invalid email or password');
        }

        session()->set([
            'user_id' => $user['id'],
            'role' => $user['role']
        ]);

        return redirect()->to(
            match ($user['role']) {
                'admin' => base_url('admin/dashboard'),
                'staff' => base_url('staff/dashboard'),
                default => base_url('candidate/apply'),
            }
        );

    }

    /* ---------------- LOGOUT ---------------- */
    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'));
    }
}
