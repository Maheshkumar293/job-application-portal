-- =====================================================
-- DATABASE
-- =====================================================
CREATE DATABASE IF NOT EXISTS job_application_portal
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE job_application_portal;

-- =====================================================
-- USERS (Candidates & Admins)
-- =====================================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('candidate','admin') NOT NULL DEFAULT 'candidate',
    status ENUM('active','blocked') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =====================================================
-- JOB APPLICATIONS (1 per Candidate)
-- =====================================================
CREATE TABLE job_applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,

    -- Step 1: Basic Information
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    mobile VARCHAR(20) NOT NULL,
    dob DATE NOT NULL,
    gender ENUM('Male','Female','Other') NOT NULL,
    address TEXT NOT NULL,
    nationality VARCHAR(100) NOT NULL,
    relocate ENUM('Yes','No') NOT NULL,

    -- Step 3: Experience & Resume
    experience VARCHAR(50),
    employment_status ENUM('Employed','Unemployed','Fresher') NOT NULL,
    previous_org VARCHAR(150),
    job_title VARCHAR(150),
    summary TEXT,
    resume_path VARCHAR(255) NOT NULL,

    -- Workflow
    application_status ENUM(
        'submitted',
        'under_review',
        'shortlisted',
        'rejected',
        'selected'
    ) NOT NULL DEFAULT 'submitted',

    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY uniq_user_application (user_id),

    CONSTRAINT fk_application_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- ACADEMIC QUALIFICATIONS (1-to-Many)
-- =====================================================
CREATE TABLE academic_qualifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    application_id INT NOT NULL,

    qualification VARCHAR(150) NOT NULL,
    institution VARCHAR(200) NOT NULL,
    graduation_year YEAR NOT NULL,
    cgpa VARCHAR(10) NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_qualification_application
        FOREIGN KEY (application_id)
        REFERENCES job_applications(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- CANDIDATE SKILLS (1-to-Many)
-- =====================================================
CREATE TABLE candidate_skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    application_id INT NOT NULL,
    skill VARCHAR(100) NOT NULL,

    CONSTRAINT fk_skill_application
        FOREIGN KEY (application_id)
        REFERENCES job_applications(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- INDEXES (PERFORMANCE READY)
-- =====================================================
CREATE INDEX idx_user_role ON users(role);
CREATE INDEX idx_user_status ON users(status);

CREATE INDEX idx_application_user ON job_applications(user_id);
CREATE INDEX idx_application_status ON job_applications(application_status);

CREATE INDEX idx_qualification_application ON academic_qualifications(application_id);

CREATE INDEX idx_skill_application ON candidate_skills(application_id);
CREATE INDEX idx_skill_name ON candidate_skills(skill);

-- =====================================================
-- OPTIONAL: ADMIN USER
-- =====================================================
-- INSERT INTO users (name, email, password_hash, role)
-- VALUES ('Admin', 'admin@jobportal.com', '<PASSWORD_HASH>', 'admin');
