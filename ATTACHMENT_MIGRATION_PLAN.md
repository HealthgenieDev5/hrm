# Employee Attachments Migration Plan

## Executive Summary

This document outlines the complete plan to migrate legacy employee attachments from the JSON-based `employees.attachment` column to the new normalized `employee_attachments` table structure.

---

## Current State Analysis

### Legacy System (OLD)
- **Storage:** `employees` table, `attachment` column (JSON encoded)
- **Structure:** Nested array with 12 predefined document types
- **File Location:** `writable/uploads/YYYY/MM/`
- **Limitations:**
  - Fixed document types only
  - Complex nested JSON structure
  - Difficult to query/report
  - No metadata tracking (uploaded_by, file_size)

### New System
- **Storage:** Dedicated `employee_attachments` table
- **Structure:** One row per attachment with full metadata
- **Features:**
  - Flexible document types (user-defined titles)
  - Soft deletes support
  - Full audit trail (created_at, updated_at, uploaded_by)
  - Easy querying and reporting
  - Scalable for unlimited attachments

---

## Migration Strategy

### Option 1: Complete Migration (Recommended)
**Approach:** Migrate all legacy attachments to new table and deprecate JSON column

**Pros:**
- Clean, single source of truth
- Eliminates technical debt
- Simplifies future maintenance
- Better data integrity

**Cons:**
- Requires careful data validation
- Need rollback plan
- Must update all dependent code

### Option 2: Hybrid Approach (Current State)
**Approach:** Keep both systems running in parallel

**Pros:**
- Zero risk to existing data
- Gradual transition possible
- Easy rollback

**Cons:**
- Dual maintenance burden
- Confusion about which system to use
- Increased complexity

**RECOMMENDATION:** Proceed with **Option 1** - Complete Migration

---

## Migration Components

### 1. Data Migration Script

**Purpose:** Extract attachments from JSON column and insert into new table

**File:** `app/Commands/MigrateEmployeeAttachments.php`

**Logic:**
```php
foreach (all employees with attachments) {
    $attachments_json = json_decode($employee->attachment, true);

    // Process each document type
    $document_types = [
        'avatar' => 'Employee Avatar',
        'pan' => 'PAN Card',
        'adhar' => 'Aadhar Card',
        'passport' => 'Passport',
        'bank_account' => 'Bank Account Details',
        'kye_documents' => 'KYE Documents',
        'family_details' => 'Family Details',
        'loan_documents' => 'Loan Documents',
        'educational_documents' => 'Educational Documents',
        'relieving_documents' => 'Relieving Documents',
        'misc_documents' => 'Miscellaneous Documents',
        'pdc_cheque' => 'PDC Cheque'
    ];

    foreach ($document_types as $key => $title) {
        if (isset($attachments_json[$key])) {
            // Extract file path(s)
            $files = extractFiles($attachments_json[$key]);

            foreach ($files as $file_info) {
                // Insert into employee_attachments table
                $attachment_record = [
                    'employee_id' => $employee->id,
                    'title' => $title,
                    'file_path' => $file_info['path'],
                    'file_name' => basename($file_info['path']),
                    'file_extension' => pathinfo($file_info['path'], PATHINFO_EXTENSION),
                    'file_size' => filesize(WRITEPATH . $file_info['path']),
                    'uploaded_by' => null, // Unknown for legacy data
                    'created_at' => $employee->created_at ?? date('Y-m-d H:i:s')
                ];

                EmployeeAttachmentModel->insert($attachment_record);
            }
        }
    }
}
```

**Special Cases to Handle:**
1. **Aadhar:** Has `front` and `back` fields - create 2 records
   - Title: "Aadhar Card - Front" and "Aadhar Card - Back"
2. **Document Numbers:** Store in metadata or remarks field
3. **Missing Files:** Validate file existence before migration
4. **Duplicate Detection:** Check if already migrated

---

### 2. Database Changes

#### Step 1: Add Migration Tracking Column
```sql
ALTER TABLE employees
ADD COLUMN attachments_migrated TINYINT(1) DEFAULT 0 COMMENT 'Flag if attachments migrated to new table';

ADD COLUMN attachments_migrated_at DATETIME NULL COMMENT 'Timestamp of migration';
```

#### Step 2: Add Remarks/Metadata Column to employee_attachments
```sql
ALTER TABLE employee_attachments
ADD COLUMN remarks TEXT NULL COMMENT 'Additional notes or document numbers'
AFTER file_size;
```

#### Step 3: Add Legacy Reference Column
```sql
ALTER TABLE employee_attachments
ADD COLUMN legacy_document_type VARCHAR(50) NULL COMMENT 'Original document type from JSON'
AFTER employee_id;
```

---

### 3. Code Updates Required

#### A. EmployeeAttachmentModel Enhancement
**File:** `app/Models/EmployeeAttachmentModel.php`

**Add Methods:**
```php
public function getByDocumentType($employeeId, $legacyType)
{
    return $this->where('employee_id', $employeeId)
                ->where('legacy_document_type', $legacyType)
                ->findAll();
}

public function getGroupedByType($employeeId)
{
    return $this->where('employee_id', $employeeId)
                ->orderBy('legacy_document_type', 'ASC')
                ->orderBy('created_at', 'DESC')
                ->findAll();
}
```

#### B. Employee Edit Controller Refactoring
**File:** `app/Controllers/Master/Employee/Edit.php`

**Changes Needed:**

1. **Remove Legacy Attachment Processing (Lines 631-838)**
   - Delete all old JSON-based attachment handling
   - Keep only new `employee_attachments` table operations

2. **Update index() method:**
   ```php
   // Remove lines 122-159 (legacy JSON decoding)
   // Keep only:
   $data['employee_attachments'] = $EmployeeAttachmentModel->getEmployeeAttachments($id);
   ```

3. **Simplify update() method:**
   - Remove all individual attachment fields (pan, adhar, avatar, etc.)
   - Keep unified additional_attachments handling (lines 840-894)

#### C. View Updates
**File:** `app/Views/Master/EmployeeEdit.php`

**Changes:**

1. **Remove Legacy Sections:**
   - Remove individual fields for PAN, Aadhar, Passport, Bank Account, etc.
   - Keep single unified "Additional Attachments" section

2. **Enhanced Display:**
   - Group attachments by `legacy_document_type` for organization
   - Show legacy document type as badge/tag
   - Allow editing titles and re-uploading

---

### 4. Migration Command Implementation

**Create File:** `app/Commands/MigrateEmployeeAttachments.php`

```php
<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\EmployeeModel;
use App\Models\EmployeeAttachmentModel;

class MigrateEmployeeAttachments extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'migrate:attachments';
    protected $description = 'Migrate employee attachments from JSON to dedicated table';

    public function run(array $params)
    {
        $employeeModel = new EmployeeModel();
        $attachmentModel = new EmployeeAttachmentModel();

        CLI::write('Starting Employee Attachments Migration...', 'yellow');

        // Get all employees with attachments
        $employees = $employeeModel->whereNotNull('attachment')
                                   ->where('attachments_migrated', 0)
                                   ->findAll();

        $total = count($employees);
        $migrated = 0;
        $errors = 0;

        CLI::write("Found {$total} employees with legacy attachments", 'cyan');

        foreach ($employees as $employee) {
            try {
                CLI::write("Processing Employee ID: {$employee['id']} ({$employee['first_name']} {$employee['last_name']})", 'blue');

                $attachments = json_decode($employee['attachment'], true);

                if (!is_array($attachments)) {
                    CLI::write("  Skipping - Invalid JSON", 'red');
                    continue;
                }

                $count = $this->migrateEmployeeAttachments($employee['id'], $attachments, $attachmentModel);

                if ($count > 0) {
                    // Mark as migrated
                    $employeeModel->update($employee['id'], [
                        'attachments_migrated' => 1,
                        'attachments_migrated_at' => date('Y-m-d H:i:s')
                    ]);

                    CLI::write("  ✓ Migrated {$count} attachment(s)", 'green');
                    $migrated++;
                } else {
                    CLI::write("  No attachments to migrate", 'yellow');
                }

            } catch (\Exception $e) {
                CLI::write("  ✗ Error: " . $e->getMessage(), 'red');
                $errors++;
            }
        }

        CLI::write("\n=== Migration Summary ===", 'yellow');
        CLI::write("Total Employees Processed: {$total}", 'cyan');
        CLI::write("Successfully Migrated: {$migrated}", 'green');
        CLI::write("Errors: {$errors}", 'red');
        CLI::write("\nMigration Complete!", 'green');
    }

    private function migrateEmployeeAttachments($employeeId, $attachments, $model)
    {
        $count = 0;

        $documentTypes = [
            'avatar' => ['title' => 'Employee Avatar', 'files' => ['file']],
            'pan' => ['title' => 'PAN Card', 'files' => ['file'], 'number_field' => 'number'],
            'adhar' => ['title' => 'Aadhar Card', 'files' => ['front', 'back'], 'number_field' => 'number'],
            'passport' => ['title' => 'Passport', 'files' => ['file'], 'number_field' => 'number'],
            'bank_account' => ['title' => 'Bank Account Details', 'files' => ['file'], 'number_field' => 'number'],
            'kye_documents' => ['title' => 'KYE Documents', 'files' => ['file'], 'remarks_field' => 'remarks'],
            'family_details' => ['title' => 'Family Details', 'files' => ['file'], 'remarks_field' => 'remarks'],
            'loan_documents' => ['title' => 'Loan Documents', 'files' => ['file'], 'remarks_field' => 'remarks'],
            'educational_documents' => ['title' => 'Educational Documents', 'files' => ['file'], 'remarks_field' => 'remarks'],
            'relieving_documents' => ['title' => 'Relieving Documents', 'files' => ['file'], 'remarks_field' => 'remarks'],
            'misc_documents' => ['title' => 'Miscellaneous Documents', 'files' => ['file'], 'remarks_field' => 'remarks'],
            'pdc_cheque' => ['title' => 'PDC Cheque', 'files' => ['file'], 'remarks_field' => 'remarks']
        ];

        foreach ($documentTypes as $key => $config) {
            if (isset($attachments[$key]) && is_array($attachments[$key])) {
                $docData = $attachments[$key];

                // Build remarks from metadata
                $remarks = [];
                if (isset($config['number_field']) && !empty($docData[$config['number_field']])) {
                    $remarks[] = "Number: " . $docData[$config['number_field']];
                }
                if (isset($config['remarks_field']) && !empty($docData[$config['remarks_field']])) {
                    $remarks[] = $docData[$config['remarks_field']];
                }

                // Handle PDC special fields
                if ($key === 'pdc_cheque') {
                    if (!empty($docData['bank_name_1'])) {
                        $remarks[] = "Bank 1: {$docData['bank_name_1']}, Cheque: {$docData['cheque_number_1']}";
                    }
                    if (!empty($docData['bank_name_2'])) {
                        $remarks[] = "Bank 2: {$docData['bank_name_2']}, Cheque: {$docData['cheque_number_2']}";
                    }
                    if (!empty($docData['bank_name_3'])) {
                        $remarks[] = "Bank 3: {$docData['bank_name_3']}, Cheque: {$docData['cheque_number_3']}";
                    }
                }

                // Process each file
                foreach ($config['files'] as $fileKey) {
                    if (isset($docData[$fileKey]) && !empty($docData[$fileKey])) {
                        $filePath = $docData[$fileKey];

                        // Skip if file doesn't exist
                        $fullPath = WRITEPATH . ltrim($filePath, '/');
                        if (!file_exists($fullPath)) {
                            CLI::write("    Warning: File not found - {$filePath}", 'yellow');
                            continue;
                        }

                        $title = $config['title'];
                        if (count($config['files']) > 1) {
                            $title .= ' - ' . ucfirst($fileKey);
                        }

                        $record = [
                            'employee_id' => $employeeId,
                            'legacy_document_type' => $key,
                            'title' => $title,
                            'file_path' => $filePath,
                            'file_name' => basename($filePath),
                            'file_extension' => pathinfo($filePath, PATHINFO_EXTENSION),
                            'file_size' => filesize($fullPath),
                            'remarks' => !empty($remarks) ? implode('; ', $remarks) : null,
                            'uploaded_by' => null,
                            'created_at' => date('Y-m-d H:i:s')
                        ];

                        $model->insert($record);
                        $count++;

                        CLI::write("    ✓ {$title}", 'green');
                    }
                }
            }
        }

        return $count;
    }
}
```

---

## Implementation Steps

### Phase 1: Preparation (Week 1)
1. ✅ Review current attachment structure
2. ✅ Create migration plan document (this file)
3. ⬜ Add new database columns:
   - `employees.attachments_migrated`
   - `employees.attachments_migrated_at`
   - `employee_attachments.remarks`
   - `employee_attachments.legacy_document_type`
4. ⬜ Create database migration file
5. ⬜ Backup production database

### Phase 2: Development (Week 2)
1. ⬜ Create migration command (`MigrateEmployeeAttachments.php`)
2. ⬜ Update `EmployeeAttachmentModel` with new methods
3. ⬜ Test migration on development database
4. ⬜ Verify file integrity after migration
5. ⬜ Create rollback script

### Phase 3: Testing (Week 3)
1. ⬜ Run migration on staging environment
2. ⬜ Test all attachment display functionality
3. ⬜ Test upload/download/delete operations
4. ⬜ Verify data accuracy
5. ⬜ Performance testing with large datasets
6. ⬜ User acceptance testing

### Phase 4: Code Cleanup (Week 4)
1. ⬜ Update Employee Edit Controller
   - Remove legacy attachment processing (lines 631-838)
   - Simplify to use only new system
2. ⬜ Update Employee Edit View
   - Remove individual legacy sections
   - Enhance unified attachments section
3. ⬜ Update any reports/exports that use attachments
4. ⬜ Update API endpoints if any

### Phase 5: Production Deployment (Week 5)
1. ⬜ Schedule maintenance window
2. ⬜ Create database backup
3. ⬜ Run migration command on production
4. ⬜ Deploy updated code
5. ⬜ Verify functionality
6. ⬜ Monitor for 48 hours

### Phase 6: Finalization (Week 6)
1. ⬜ Document new attachment system
2. ⬜ Train users on updated interface
3. ⬜ Mark `employees.attachment` column as deprecated
4. ⬜ Schedule column removal for future release

---

## Database Migration SQL

**File:** `app/Database/Migrations/YYYY-MM-DD-HHMMSS_UpdateEmployeeAttachmentsTable.php`

```php
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateEmployeeAttachmentsTable extends Migration
{
    public function up()
    {
        // Add columns to employee_attachments table
        $fields = [
            'legacy_document_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'employee_id',
                'comment' => 'Original document type from legacy JSON structure'
            ],
            'remarks' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'file_size',
                'comment' => 'Additional notes, document numbers, or metadata'
            ]
        ];

        $this->forge->addColumn('employee_attachments', $fields);

        // Add index for faster lookups
        $this->forge->addKey('legacy_document_type', false, false, 'idx_legacy_document_type');
        $this->db->query('CREATE INDEX idx_legacy_document_type ON employee_attachments (legacy_document_type)');

        // Add columns to employees table
        $employeeFields = [
            'attachments_migrated' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'null' => false,
                'comment' => 'Flag indicating if attachments have been migrated to new table'
            ],
            'attachments_migrated_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Timestamp when attachments were migrated'
            ]
        ];

        $this->forge->addColumn('employees', $employeeFields);
    }

    public function down()
    {
        // Remove columns from employee_attachments
        $this->forge->dropColumn('employee_attachments', 'legacy_document_type');
        $this->forge->dropColumn('employee_attachments', 'remarks');

        // Remove index
        $this->db->query('DROP INDEX idx_legacy_document_type ON employee_attachments');

        // Remove columns from employees
        $this->forge->dropColumn('employees', 'attachments_migrated');
        $this->forge->dropColumn('employees', 'attachments_migrated_at');
    }
}
```

---

## Testing Checklist

### Data Integrity Tests
- [ ] All file paths migrated correctly
- [ ] File extensions extracted properly
- [ ] File sizes calculated accurately
- [ ] Document titles match legacy types
- [ ] Remarks contain document numbers
- [ ] No duplicate records created
- [ ] Aadhar front/back split correctly
- [ ] PDC cheque data preserved

### Functional Tests
- [ ] Upload new attachment
- [ ] Download existing attachment
- [ ] Delete attachment (soft delete)
- [ ] View attachment preview
- [ ] Edit attachment title
- [ ] Replace attachment file
- [ ] Filter by document type
- [ ] Search attachments

### Performance Tests
- [ ] Load time with 100+ attachments per employee
- [ ] Migration time for 1000+ employees
- [ ] Database query optimization
- [ ] File serving response time

### UI/UX Tests
- [ ] Attachment list displays correctly
- [ ] Legacy document types shown as badges
- [ ] File size displayed in human-readable format
- [ ] Upload progress indicator works
- [ ] Error messages are clear
- [ ] Mobile responsive display

---

## Rollback Plan

### If Migration Fails
1. Stop migration command immediately
2. Note last successfully migrated employee ID
3. Restore from backup if necessary
4. Fix issues in migration script
5. Resume from last checkpoint

### If Production Issues After Deployment
1. Revert code to previous version
2. Legacy JSON system still intact - no data loss
3. Set `attachments_migrated = 0` for affected employees
4. Investigate and fix issues
5. Re-run migration after fixes

### Rollback SQL
```sql
-- Reset migration flags
UPDATE employees SET attachments_migrated = 0, attachments_migrated_at = NULL;

-- Optionally delete migrated records
DELETE FROM employee_attachments WHERE legacy_document_type IS NOT NULL;

-- Or truncate entire table if needed
TRUNCATE TABLE employee_attachments;
```

---

## Risk Assessment

| Risk | Impact | Probability | Mitigation |
|------|--------|-------------|------------|
| File path mismatch | High | Low | Validate all file paths before migration |
| Data loss during migration | Critical | Very Low | Full database backup + dry run testing |
| Performance degradation | Medium | Low | Index optimization + query tuning |
| User confusion with new UI | Medium | Medium | User training + documentation |
| Missing file errors | Low | Medium | File existence check in migration script |
| Duplicate attachments | Low | Low | Check existing records before insert |

---

## Success Metrics

- [ ] 100% of legacy attachments migrated successfully
- [ ] Zero data loss or corruption
- [ ] File access time < 2 seconds
- [ ] Migration completes within scheduled window
- [ ] No P1/P2 bugs reported within 1 week post-deployment
- [ ] User satisfaction score > 8/10

---

## Additional Features to Consider

### Post-Migration Enhancements
1. **Attachment Versioning:** Keep history of document updates
2. **Expiry Dates:** Alert for document renewals (passport, visa, etc.)
3. **Approval Workflow:** Manager approval for sensitive documents
4. **Bulk Download:** ZIP download of all employee documents
5. **Document Templates:** Pre-defined document types dropdown
6. **Advanced Search:** Full-text search across attachment titles and remarks
7. **Storage Analytics:** Dashboard showing storage usage per employee/department

---

## Support and Troubleshooting

### Common Issues

**Issue 1: "File not found" errors during migration**
- **Cause:** File moved or deleted from writable/uploads
- **Solution:** Check file existence before migration, log missing files

**Issue 2: Migration hangs on large datasets**
- **Cause:** Memory exhaustion
- **Solution:** Process in batches, increase PHP memory_limit

**Issue 3: Duplicate attachments after re-running migration**
- **Cause:** Migration flag not set properly
- **Solution:** Check attachments_migrated flag before processing

### Monitoring

```bash
# Watch migration progress
tail -f writable/logs/log-$(date +%Y-%m-%d).log

# Check migration status
php spark db:query "SELECT
    COUNT(*) as total_employees,
    SUM(attachments_migrated) as migrated_count,
    SUM(attachments_migrated = 0) as pending_count
FROM employees
WHERE attachment IS NOT NULL"

# Count migrated attachments
php spark db:query "SELECT
    legacy_document_type,
    COUNT(*) as count
FROM employee_attachments
WHERE legacy_document_type IS NOT NULL
GROUP BY legacy_document_type"
```

---

## Conclusion

This migration plan provides a comprehensive roadmap to modernize the employee attachments system. Following these steps will result in a cleaner, more scalable, and maintainable attachment management system while preserving all existing data.

**Next Steps:**
1. Review and approve this plan
2. Schedule development timeline
3. Begin Phase 1 implementation
4. Set up regular progress check-ins

**Questions or Concerns:** Document any concerns or questions before proceeding with implementation.

---

**Document Version:** 1.0
**Created:** 2025-12-29
**Author:** Migration Planning Team
**Status:** Draft - Awaiting Approval
