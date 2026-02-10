-- Create resignation_hod_response table
-- This table tracks HOD acknowledgments and manager notifications for resignations

CREATE TABLE IF NOT EXISTS `resignation_hod_response` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `resignation_id` INT(11) UNSIGNED NOT NULL,
    `employee_id` INT(11) UNSIGNED NOT NULL,
    `hod_id` INT(11) UNSIGNED NOT NULL,
    `hod_response` ENUM('pending','too_early','accept','rejected') DEFAULT 'pending',
    `hod_response_date` DATETIME NULL,
    `hod_rejection_reason` TEXT NULL,
    `manager_id` INT(11) UNSIGNED NULL,
    `manager_viewed` ENUM('pending','viewed') DEFAULT 'pending' NULL,
    `manager_viewed_date` DATETIME NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

