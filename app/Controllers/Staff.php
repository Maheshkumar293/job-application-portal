<?php

namespace App\Controllers;

use App\Models\UserModel;

class Staff extends BaseController
{
    /* =========================
       STAFF LIST PAGE
    ========================= */
    public function index()
    {
        $staffs = (new UserModel())
            ->where('role', 'staff')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('admin/staffs', compact('staffs'));
    }

    /* =========================
       CREATE STAFF
    ========================= */
    public function create()
    {
        $rules = [
            'name'     => 'required|min_length[3]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        (new UserModel())->insert([
            'name'          => trim($this->request->getPost('name')),
            'email'         => strtolower(trim($this->request->getPost('email'))),
            'password_hash' => password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            ),
            'role'   => 'staff',      // 🔥 STAFF ROLE
            'status' => 'active'
        ]);

        return redirect()->back()->with('success', 'Staff account created');
    }

    /* =========================
       BLOCK STAFF (SOFT DELETE)
    ========================= */
    public function block($id)
    {
        (new UserModel())
            ->where('id', $id)
            ->where('role', 'staff')
            ->set(['status' => 'blocked'])
            ->update();

        return redirect()->back()->with('success', 'Staff account blocked');
    }
    public function UnBlock($id)
    {
        (new UserModel())
            ->where('id', $id)
            ->where('role', 'staff')
            ->set(['status' => 'active'])
            ->update();

        return redirect()->back()->with('success', 'Staff account unblocked');
    }
}
