-- Create announcements table
CREATE TABLE IF NOT EXISTS `announcements` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `message` TEXT NOT NULL,
    `type` ENUM('info','warning','success','danger') NOT NULL DEFAULT 'info',
    `priority` ENUM('low','medium','high','critical') NOT NULL DEFAULT 'medium',
    `target_type` ENUM('all','department','designation','specific') NOT NULL DEFAULT 'all' COMMENT 'Who should see this announcement',
    `target_ids` TEXT NULL COMMENT 'Comma-separated IDs for department/designation/employee targets',
    `start_date` DATETIME NULL,
    `end_date` DATETIME NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `requires_acknowledgment` TINYINT(1) NOT NULL DEFAULT 1,
    `show_once` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Show only once per user or every time they login',
    `created_by` INT(11) UNSIGNED NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `is_active` (`is_active`),
    KEY `target_type` (`target_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create announcement acknowledgments table
CREATE TABLE IF NOT EXISTS `announcement_acknowledgments` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `announcement_id` INT(11) UNSIGNED NOT NULL,
    `employee_id` INT(11) UNSIGNED NOT NULL,
    `acknowledged_at` DATETIME NOT NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    PRIMARY KEY (`id`),
    KEY `announcement_employee` (`announcement_id`, `employee_id`),
    CONSTRAINT `fk_announcement_ack_announcement`
        FOREIGN KEY (`announcement_id`)
        REFERENCES `announcements` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert the Sandwich Rule announcement
INSERT INTO `announcements` (
    `title`,
    `message`,
    `type`,
    `priority`,
    `target_type`,
    `target_ids`,
    `start_date`,
    `end_date`,
    `is_active`,
    `requires_acknowledgment`,
    `show_once`,
    `created_by`,
    `created_at`,
    `updated_at`
) VALUES (
    'Important: Sandwich Rule Update - Religious Holidays (RH)',
    '<div style="font-size: 16px; line-height: 1.6;">
        <p><strong>Dear Team,</strong></p>
        <p>This is to inform all employees that, effective <strong>1st December 2025</strong>, the <strong>Sandwich Rule</strong> will also apply to <strong>Religious Holidays (RH)</strong>.</p>
        <p>The rule shall be implemented in the same manner as for Sundays and other declared holidays.</p>
        <p><strong>What this means:</strong></p>
        <ul>
            <li>If you are absent on the days immediately before and after a Religious Holiday (RH), the RH will also be counted as leave</li>
            <li>This policy is being applied uniformly across the organization</li>
            <li>Please plan your leave requests accordingly</li>
        </ul>
        <p>For any queries, please contact the HR department.</p>
        <p><em>HR Department<br>Healthgenie</em></p>
    </div>',
    'warning',
    'high',
    'all',
    NULL,
    NOW(),
    DATE_ADD(NOW(), INTERVAL 30 DAY),
    1,
    1,
    1,
    1,
    NOW(),
    NOW()
);
