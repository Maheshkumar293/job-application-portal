<?php

namespace App\Controllers;

use App\Models\JobApplicationModel;

class StaffDashboard extends BaseController
{
    public function index()
    {
        $staffId = session('user_id');

        $apps = (new JobApplicationModel())
            ->select('job_applications.*, u.name AS assigned_by')
            ->join(
                'application_staff_assignments a',
                'a.application_id = job_applications.id'
            )
            ->join(
                'users u',
                'u.id = a.assigned_by',
                'left'
            )
            ->where('a.staff_id', $staffId)
            ->orderBy('a.assigned_at', 'DESC')
            ->findAll();

        return view('staff/dashboard', compact('apps'));
    }
}
