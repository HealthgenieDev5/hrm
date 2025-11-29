-- Create the job closure approvals table
CREATE TABLE IF NOT EXISTS `rc_job_closure_approvals` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `job_listing_id` INT NOT NULL,
    `selected_candidate_id` INT NULL,
    `replacement_of_employee_id` INT NULL,
    `hr_assessment_notes` TEXT NULL,
    `hr_approved_by` INT NULL,
    `hr_approved_at` DATETIME NULL,
    `team_strengths` TEXT NULL,
    `team_weaknesses` TEXT NULL,
    `keep_posting_open` ENUM('yes', 'no') NULL,
    `keep_posting_reason` TEXT NULL,
    `current_team_size` INT NULL,
    `best_performer_id` INT NULL,
    `worst_performer_id` INT NULL,
    `need_replacement` ENUM('yes', 'no') NULL,
    `replacement_details` TEXT NULL,
    `manager_comments` TEXT NULL,
    `manager_approved_by` INT NULL,
    `manager_approved_at` DATETIME NULL,
    `current_step` ENUM('hr_approval', 'manager_feedback', 'completed') DEFAULT 'hr_approval',
    `final_closure_date` DATETIME NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX `idx_job_listing_id` (`job_listing_id`),
    INDEX `idx_selected_candidate` (`selected_candidate_id`),
    INDEX `idx_replacement_employee` (`replacement_of_employee_id`),
    INDEX `idx_best_performer` (`best_performer_id`),
    INDEX `idx_worst_performer` (`worst_performer_id`)
) DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci;

-- Update the job listing status enum to include new status values
ALTER TABLE `rc_job_listing`
MODIFY COLUMN `status` ENUM('active','in progress','inactive','pending','draft','closed','pending_manager_feedback','partially_closed') NOT NULL DEFAULT 'active';


