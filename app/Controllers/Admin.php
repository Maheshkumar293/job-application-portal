<?php

namespace App\Controllers;

use App\Models\JobApplicationModel;
use App\Models\CandidateSkillModel;
use App\Models\AcademicQualificationModel;
use App\Models\RoleModel;
class Admin extends BaseController
{
    public function index()
    {
        $req = $this->request;
        $appModel = new JobApplicationModel();
        $builder = $appModel->builder();

        // SEARCH
        if ($s = $req->getGet('search')) {
            $builder->groupStart()
                ->like('full_name', $s)
                ->orLike('email', $s)
                ->orLike('mobile', $s)
                ->groupEnd();
        }

        // STATUS
        if ($st = $req->getGet('status')) {
            $builder->where('application_status', $st);
        }

        // DATE
        if ($req->getGet('from')) {
            $builder->where('DATE(submitted_at) >=', $req->getGet('from'));
        }
        if ($req->getGet('to')) {
            $builder->where('DATE(submitted_at) <=', $req->getGet('to'));
        }

        // SKILL
        if ($req->getGet('skill')) {
            $builder->join(
                'candidate_skills',
                'candidate_skills.application_id = job_applications.id'
            )->where('candidate_skills.skill', $req->getGet('skill'));
        }

        $apps = $builder->orderBy('submitted_at', 'DESC')
            ->get()
            ->getResult(); // OBJECTS

        // KPIs (CORRECT ENUM VALUES)
        $stats = [
            'total' => $appModel->countAll(),
            'submitted' => $appModel->where('application_status', 'submitted')->countAllResults(),
            'under_review' => $appModel->where('application_status', 'under_review')->countAllResults(),
            'shortlisted' => $appModel->where('application_status', 'shortlisted')->countAllResults(),
            'selected' => $appModel->where('application_status', 'selected')->countAllResults(),
            'rejected' => $appModel->where('application_status', 'rejected')->countAllResults(),
        ];

        return view('admin/dashboard', compact('apps', 'stats'));
    }

    /* =========================
       INLINE STATUS UPDATE
    ========================= */
    public function updateStatus()
    {
        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');

        $model = new JobApplicationModel();
        $app = $model->find($id);

        if (!$app) {
            return $this->response->setJSON([
                'success' => false,
                'csrf' => csrf_hash()
            ]);
        }

        $model->update($id, [
            'application_status' => $status
        ]);

        $this->sendStatusMail($app['email'], $app['full_name'], $status);

        return $this->response->setJSON([
            'success' => true,
            'csrf' => csrf_hash()
        ]);
    }


    /* =========================
       MODAL DETAILS
    ========================= */
    /* =========================
   MODAL DETAILS (WITH ROLE)
========================= */
    public function candidateDetails($id)
    {
        $appModel = new JobApplicationModel();
        $skillModel = new CandidateSkillModel();
        $eduModel = new AcademicQualificationModel();

        // 🔹 Get application + role
        $app = $appModel
            ->select('job_applications.*, roles.role_name')
            ->join('roles', 'roles.id = job_applications.role_id', 'left')
            ->where('job_applications.id', $id)
            ->first();

        if (!$app) {
            return $this->response->setJSON(['error' => 'Not found']);
        }

        return $this->response->setJSON([
            'role' => $app['role_name'], // ✅ APPLIED ROLE
            'skills' => $skillModel->where('application_id', $id)->findAll(),
            'edu' => $eduModel->where('application_id', $id)->findAll()
        ]);
    }

/* =========================
   ROLE MANAGEMENT
========================= */
    public function roles()
    {
        $roles = (new RoleModel())->orderBy('created_at', 'DESC')->findAll();
        return view('admin/roles', compact('roles'));
    }

    public function createRole()
    {
        $name = trim($this->request->getPost('role_name'));

        if (!$name) {
            return redirect()->back()->with('error', 'Role name required');
        }

        $roleModel = new RoleModel();

        // prevent duplicates
        if ($roleModel->where('role_name', $name)->first()) {
            return redirect()->back()->with('error', 'Role already exists');
        }

        $roleModel->insert([
            'role_name' => $name,
            'status' => 'active'
        ]);

        return redirect()->back()->with('success', 'Role added');
    }

    public function toggleRole()
    {
        $id = $this->request->getPost('id');

        $roleModel = new RoleModel();
        $role = $roleModel->find($id);

        if (!$role) {
            return $this->response->setJSON(['success' => false]);
        }

        // 🔒 prevent disabling role already used
        $inUse = (new JobApplicationModel())
            ->where('role_id', $id)
            ->countAllResults();

        if ($inUse && $role['status'] === 'active') {
            return $this->response->setJSON([
                'success' => false,
                'msg' => 'Role already assigned to candidates'
            ]);
        }

        $roleModel->update($id, [
            'status' => $role['status'] === 'active' ? 'inactive' : 'active'
        ]);

        return $this->response->setJSON(['success' => true]);
    }
    /* =========================
       RESUME VIEW
    ========================= */
    public function viewResume($file)
    {
        $path = WRITEPATH . 'uploads/resumes/' . $file;
        if (!is_file($path)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return $this->response
            ->setHeader('Content-Type', mime_content_type($path))
            ->setHeader('Content-Disposition', 'inline; filename="' . $file . '"')
            ->setBody(file_get_contents($path));
    }
    private function getHtmlTemplate($name, $status)
    {
        $colors = [
            'submitted' => '#6c757d',
            'under_review' => '#0dcaf0',
            'shortlisted' => '#ffc107',
            'selected' => '#28a745',
            'rejected' => '#dc3545'
        ];

        $titles = [
            'submitted' => 'Application Received',
            'under_review' => 'Application Under Review',
            'shortlisted' => 'You Have Been Shortlisted',
            'selected' => 'Congratulations! You Are Selected',
            'rejected' => 'Application Update'
        ];

        $messages = [
            'submitted' =>
                "Thank you for applying. We have successfully received your application and our team will review it shortly.",

            'under_review' =>
                "Your application is currently under review by our recruitment team. We appreciate your patience.",

            'shortlisted' =>
                "Great news! Your profile has been shortlisted. You may be contacted for the next stage soon.",

            'selected' =>
                "🎉 We are excited to inform you that you have been selected! Our HR team will reach out with next steps.",

            'rejected' =>
                "Thank you for your interest. After careful consideration, we regret to inform you that your application was not selected at this time."
        ];

        $color = $colors[$status] ?? '#6c757d';

        return <<<HTML
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>{$titles[$status]}</title>
    </head>
    <body style="margin:0;padding:0;background:#f4f6f8;font-family:Arial,sans-serif;">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td align="center" style="padding:30px 10px;">

        <table width="600" cellpadding="0" cellspacing="0"
       style="background:#ffffff;border-radius:14px;overflow:hidden;
              box-shadow:0 12px 30px rgba(0,0,0,0.15);">

            <tr>
                <td style="background:$color;padding:20px;text-align:center;color:#ffffff;">
                    <h1 style="margin:0;font-size:22px;">{$titles[$status]}</h1>
                </td>
            </tr>
            <tr>
                <td style="padding:30px;color:#333333;">
                    <p style="font-size:16px;margin-bottom:12px;">
                        Hello <strong>$name</strong>,
                    </p>
                    <p style="font-size:15px;line-height:1.6;">
                        {$messages[$status]}
                    </p>

            <div style="margin:25px 0;padding:18px;background:#f8f9fa;
            border-left:4px solid $color;border-radius:6px;">
<strong>Status:</strong>
<span style="color:$color;font-weight:bold;text-transform:capitalize;">
$status
</span>
</div>

<p style="font-size:14px;color:#666;">
If you have any questions, feel free to reply to this email.
</p>

<p style="margin-top:25px;font-size:14px;">
Warm regards,<br>
<strong>Recruitment Team</strong>
</p>
</td>
</tr>

<tr>
<td style="background:#f1f1f1;padding:15px;text-align:center;
           font-size:12px;color:#777;">
© <?= date('Y') ?> Job Application Portal • All rights reserved
</td>
</tr>

</table>

</td>
</tr>
</table>
</body>
</html>
HTML;
    }


    /* =========================
       MAIL TEMPLATES
    ========================= */
    private function sendStatusMail($email, $name, $status)
    {
        $subjectMap = [
            'submitted' => '📄 Application Received',
            'under_review' => '🔍 Application Under Review',
            'shortlisted' => '✅ You Are Shortlisted',
            'selected' => '🎉 Congratulations! You Are Selected',
            'rejected' => '📬 Application Update'
        ];

        $message = $this->getHtmlTemplate($name, $status);

        $mail = \Config\Services::email();
        $mail->setTo($email);
        $mail->setSubject($subjectMap[$status] ?? 'Application Status Update');
        $mail->setMessage($message);
        $mail->setMailType('html');
        $mail->send();
    }

}
