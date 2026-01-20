<?php if (session()->getFlashdata('already_applied')): ?>
    <div class="toast-container position-fixed top-0 end-0 p-4">
        <div class="toast show align-items-center text-bg-warning border-0 shadow-lg">
            <div class="d-flex">
                <div class="toast-body">
                    ⚠️ You have already submitted your application.<br>
                    Our team will contact you soon.
                </div>
                <button class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Job Application</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= base_url('public/assets/css/application.css') ?>" rel="stylesheet">
</head>

<body>

    <!-- LOGOUT -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index:1050;">
        <a href="<?= base_url('logout') ?>" class="btn btn-outline-light btn-sm shadow">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="success-pop-overlay">
            <div class="success-pop">
                <div class="success-ring"></div>
                <div class="success-icon">✓</div>

                <h3>Application Submitted</h3>
                <p class="success-msg"><?= esc(session()->getFlashdata('success')) ?></p>
                <p class="email-msg">📧 A confirmation email has been sent to your registered email address.</p>

                <button class="success-btn" onclick="closeSuccessPop(this)">Continue</button>
            </div>
        </div>
    <?php endif; ?>

    <div class="app-card">

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $err): ?>
                        <li><?= esc($err) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('candidate/submit') ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <!-- STEPPER -->
            <div class="stepper mb-4">
                <div class="step-item active">
                    <div class="step-circle">1</div><span>Basic</span>
                </div>
                <div class="step-line"></div>
                <div class="step-item">
                    <div class="step-circle">2</div><span>Education</span>
                </div>
                <div class="step-line"></div>
                <div class="step-item">
                    <div class="step-circle">3</div><span>Professional</span>
                </div>
            </div>

            <!-- ================= STEP 1 ================= -->
            <div class="step active">
                <h4 class="mb-3">Basic Information</h4>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Full Name</label>
                    <input name="full_name" class="form-control" placeholder="Enter full name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email Address</label>
                    <input name="email" type="email" class="form-control" placeholder="example@email.com" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Mobile Number</label>
                    <input name="mobile" class="form-control" placeholder="10-digit mobile number" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Date of Birth</label>
                    <input name="dob" type="date" class="form-control" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Gender</label>
                        <select name="gender" class="form-select" required>
                            <option value="">Select Gender</option>
                            <option>Male</option>
                            <option>Female</option>
                            <option>Other</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Nationality</label>
                        <select name="nationality" class="form-select" required>
                            <option value="">Select Nationality</option>
                            <option>Indian</option>
                            <option>Canadian</option>
                            <option>British</option>
                            <option>Australian</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Applying For Role</label>
                    <select name="role_id" class="form-select" required>
                        <option value="">Select Role</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= $role['id'] ?>">
                                <?= esc($role['role_name']) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Address</label>
                    <textarea name="address" class="form-control" rows="2" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Willing to Relocate?</label>
                    <div class="d-flex gap-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="relocate" value="Yes" required>
                            <label class="form-check-label">Yes</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="relocate" value="No" required>
                            <label class="form-check-label">No</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================= STEP 2 ================= -->
            <div class="step">
                <h4 class="mb-3">Academic Qualifications</h4>

                <div id="qualificationContainer">
                    <div class="row g-2 qualification mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Qualification</label>
                            <input name="qualification[]" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Institution</label>
                            <input name="institution[]" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Year</label>
                            <input name="year[]" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">CGPA</label>
                            <input name="cgpa[]" class="form-control" required>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="button" class="btn btn-danger remove">×</button>
                        </div>
                    </div>
                </div>

                <button type="button" id="addQualification" class="btn btn-secondary btn-sm mb-3">
                    + Add Qualification
                </button><br>

                <label class="form-label fw-semibold">Skills</label><br>
                <input type="checkbox" name="skills[]" value="PHP"> PHP
                <input type="checkbox" name="skills[]" value="JS"> JavaScript
                <input type="checkbox" name="skills[]" value="MySQL"> MySQL
            </div>

            <!-- ================= STEP 3 ================= -->
            <div class="step">
                <h4 class="mb-3">Professional Details</h4>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Total Experience</label>
                    <input name="experience" class="form-control" placeholder="e.g. 2 Years" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Employment Status</label>
                    <select name="employment_status" class="form-select" required>
                        <option value="">Select Status</option>
                        <option>Fresher</option>
                        <option>Employed</option>
                        <option>Unemployed</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Previous Organization</label>
                    <input name="previous_org" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Job Title</label>
                    <input name="job_title" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Professional Summary</label>
                    <textarea name="summary" class="form-control" rows="2"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Upload Resume</label>
                    <input type="file" name="resume" class="form-control" required>
                </div>

                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" id="declaration" required>
                    <label class="form-check-label fw-semibold" for="declaration">
                        I hereby declare that the information provided above is true and correct.
                    </label>
                </div>
            </div>

            <!-- NAV -->
            <div class="d-flex justify-content-between mt-4">
                <button type="button" id="prevBtn" class="btn btn-secondary">Previous</button>
                <button type="button" id="nextBtn" class="btn btn-primary">Next</button>
                <button type="submit" id="submitBtn" class="btn btn-success d-none">Submit</button>
            </div>

        </form>
    </div>

    <script src="<?= base_url('public/assets/js/application.js') ?>"></script>
    <script>
        function closeSuccessPop(btn) {
            const overlay = btn.closest('.success-pop-overlay');
            overlay.classList.add('hide');
            setTimeout(() => overlay.remove(), 400);
        }
        setTimeout(() => {
            const pop = document.querySelector('.success-pop-overlay');
            if (pop) pop.classList.add('hide');
        }, 6000);
    </script>

</body>

</html>