CREATE TABLE IF NOT EXISTS `resignations_revision` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `resignation_id` INT NOT NULL COMMENT 'Reference to original resignation record',
    `revision_data` TEXT NOT NULL COMMENT 'Complete resignation data stored as JSON string',
    `action` ENUM('created', 'updated', 'completed', 'withdrawn') NOT NULL COMMENT 'Type of action performed',
    `action_by` INT NOT NULL COMMENT 'Employee who performed this action',
    `action_note` TEXT NULL COMMENT 'Additional notes about the change',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_resignation_id` (`resignation_id`),
    INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
