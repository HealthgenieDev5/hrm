-- Update rc_job_listing status column to include partially_closed
ALTER TABLE `rc_job_listing`
MODIFY COLUMN `status` ENUM(
    'active',
    'open',
    'in progress',
    'inactive',
    'pending',
    'draft',
    'partially_closed',
    'closed',
    'rejected'
) DEFAULT 'active';

-- Create rc_job_closure_approvals table
CREATE TABLE IF NOT EXISTS `rc_job_closure_approvals` (
  `id` int NOT NULL AUTO_INCREMENT,
  `job_listing_id` int NOT NULL,

  -- HR Executive Stage 1 Fields
  `selected_candidate_id` int DEFAULT NULL,
  `replacement_of_employee_id` int DEFAULT NULL,
  `hr_assessment_notes` text,
  `hr_approved_by` int DEFAULT NULL,
  `hr_approved_at` datetime DEFAULT NULL,

  -- Manager Stage 2 Fields - Assessment
  `strengths` text,
  `weaknesses` text,
  `current_team_size` int DEFAULT NULL,
  `best_performer_id` int DEFAULT NULL,
  `worst_performer_id` int DEFAULT NULL,

  -- Manager Stage 2 Fields - Performance Management
  `need_replacement` enum('yes','no') DEFAULT NULL,
  `replacement_details` text,

  -- Manager Stage 2 Fields - Future Planning
  `keep_posting_open` enum('yes','no') DEFAULT NULL,
  `keep_posting_reason` text,

  -- Manager Stage 2 Fields - Comments & Closure
  `manager_comments` text,
  `manager_approved_by` int DEFAULT NULL,
  `manager_approved_at` datetime DEFAULT NULL,

  -- System Fields
  `current_step` enum('pending_manager_closure','completed') DEFAULT 'pending_manager_closure',
  `final_closure_date` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  -- Indexes
  PRIMARY KEY (`id`),
  KEY `idx_job_listing_id` (`job_listing_id`),
  KEY `idx_selected_candidate` (`selected_candidate_id`),
  KEY `idx_replacement_employee` (`replacement_of_employee_id`),
  KEY `idx_best_performer` (`best_performer_id`),
  KEY `idx_worst_performer` (`worst_performer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;