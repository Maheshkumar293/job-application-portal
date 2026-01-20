<?php

namespace App\Controllers;

use App\Models\JobApplicationModel;
use App\Models\AcademicQualificationModel;
use App\Models\CandidateSkillModel;
use App\Models\RoleModel;

class Candidate extends BaseController
{
    public function apply()
    {
        $roles = (new RoleModel())->getActiveRoles();
        return view('candidate/apply', compact('roles'));
    }

    public function submit()
    {
        $userId = session('user_id');

        if (!$userId) {
            return redirect()->to('login');
        }

        $appModel = new JobApplicationModel();

        // 🔒 PREVENT DUPLICATE APPLICATION
        if ($appModel->where('user_id', $userId)->first()) {
            return redirect()->to('candidate/apply')
                ->with('already_applied', true);
        }
        // ✅ VALIDATE ROLE EXISTS (CRITICAL FIX)
        $roleId = $this->request->getPost('role_id');
        $roleModel = new RoleModel();
        $validRole = $roleModel->find($roleId); // Check if role exists

        if (!$validRole) {
            return redirect()->back()
                ->withInput()
                ->with('errors', ['role_id' => 'Invalid role selected']);
        }
        // ✅ VALIDATION
        $rules = [
            'full_name' => 'required|min_length[3]',
            'email' => 'required|valid_email',
            'mobile' => 'required|min_length[10]',
            'dob' => 'required',
            'gender' => 'required',
            'address' => 'required',
            'relocate' => 'required',
            'experience' => 'required',
            'employment_status' => 'required',
            'role_id' => 'required|is_not_unique[roles.id]', // Add this
            'resume' => 'uploaded[resume]|ext_in[resume,pdf,doc,docx]|max_size[resume,2048]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // 📄 RESUME UPLOAD
        $resume = $this->request->getFile('resume');
        $resumeName = $resume->getRandomName();
        $resume->move(WRITEPATH . 'uploads/resumes', $resumeName);

        // =============================
        // MAIN APPLICATION INSERT
        // =============================
        $appId = $appModel->insert([
            'user_id' => $userId,
            'role_id' => $roleId, // Now guaranteed valid (1-6)
            'full_name' => $this->request->getPost('full_name'),
            'email' => $this->request->getPost('email'),
            'mobile' => $this->request->getPost('mobile'),
            'dob' => $this->request->getPost('dob'),
            'gender' => $this->request->getPost('gender'),
            'nationality' => $this->request->getPost('nationality'),
            'address' => $this->request->getPost('address'),
            'relocate' => $this->request->getPost('relocate'),
            'experience' => $this->request->getPost('experience'),
            'employment_status' => $this->request->getPost('employment_status'),
            'previous_org' => $this->request->getPost('previous_org'),
            'job_title' => $this->request->getPost('job_title'),
            'summary' => $this->request->getPost('summary'),
            'resume_path' => 'uploads/resumes/' . $resumeName
        ]);

        // =============================
        // ACADEMIC QUALIFICATIONS
        // =============================
        $qualModel = new AcademicQualificationModel();

        $qualifications = $this->request->getPost('qualification');
        $institutions = $this->request->getPost('institution');
        $years = $this->request->getPost('year');
        $cgpas = $this->request->getPost('cgpa');

        if (is_array($qualifications)) {
            foreach ($qualifications as $i => $q) {
                if (empty($q))
                    continue;

                $qualModel->insert([
                    'application_id' => $appId,
                    'qualification' => $q,
                    'institution' => $institutions[$i] ?? '',
                    'graduation_year' => $years[$i] ?? '',
                    'cgpa' => $cgpas[$i] ?? ''
                ]);
            }
        }

        // =============================
        // SKILLS
        // =============================
        $skillModel = new CandidateSkillModel();
        $skills = $this->request->getPost('skills');

        if (is_array($skills)) {
            foreach ($skills as $skill) {
                $skillModel->insert([
                    'application_id' => $appId,
                    'skill' => $skill
                ]);
            }
        }
        // =============================
        // EMAIL CONFIRMATION
        // =============================
        $emailService = \Config\Services::email();

        $emailService->setTo($this->request->getPost('email'));
        $emailService->setSubject('Application Submitted Successfully');

        $emailBody = view('emails/application_confirmation', [
            'name' => $this->request->getPost('full_name')
        ]);

        $emailService->setMessage($emailBody);

        // Send without blocking the flow
        try {
            $emailService->send();
        } catch (\Throwable $e) {
            log_message('error', 'Email failed: ' . $e->getMessage());
        }

        return redirect()->to('candidate/apply')
            ->with('success', 'Application submitted successfully');
    }
}
