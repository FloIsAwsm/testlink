# Fix for Missing testcase_relations Table Error

## Problem Description

When trying to print documents in TestLink, you encountered this error:

```
DB Access Error - debug_print_backtrace() OUTPUT START
#0 database.class.php(792): database->exec_query()
#1 testcase.class.php(6824): database->get_recordset()
#2 testcase->getRelations('7203')
```

**Root Cause**: The `testcase_relations` table is missing from your database.

This table was introduced in TestLink 1.9.12 to support test case relationships (linking test cases together as dependencies, related items, etc.).

---

## Solution Options

### Option 1: Automated Fix (Recommended)

Run the provided PHP diagnostic script:

```bash
cd /path/to/testlink
php fix_missing_table.php
```

This script will:
1. Check if the table exists
2. Verify your database configuration
3. Offer to create the table automatically
4. Create necessary indexes
5. Verify the fix

**Advantages**: Automatically handles table prefixes and validates the fix.

---

### Option 2: Manual SQL Script

If you prefer to run SQL manually or the PHP script doesn't work:

#### Via MySQL Command Line:

```bash
# First, backup your database!
mysqldump -u your_user -p your_database > backup_before_fix.sql

# Then apply the fix
mysql -u your_user -p your_database < create_testcase_relations_table.sql
```

#### Via phpMyAdmin:

1. Open phpMyAdmin
2. Select your TestLink database
3. Click "Import" tab
4. Choose file: `create_testcase_relations_table.sql`
5. Click "Go"

#### Via MySQL Workbench:

1. Open MySQL Workbench
2. Connect to your database
3. File → Run SQL Script
4. Select: `create_testcase_relations_table.sql`
5. Execute

---

### Option 3: Direct SQL Execution

If you need to create the table directly via SQL console:

```sql
-- If your installation uses a table prefix (check config.inc.php),
-- replace 'testcase_relations' with 'your_prefix_testcase_relations'

CREATE TABLE testcase_relations (
  `id` int(10) unsigned NOT NULL auto_increment,
  `source_id` int(10) unsigned NOT NULL,
  `destination_id` int(10) unsigned NOT NULL,
  `relation_type` smallint(5) unsigned NOT NULL default '1',
  `author_id` int(10) unsigned default NULL,
  `creation_ts` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 COMMENT='Test case relationships';

-- Recommended: Add indexes for performance
CREATE INDEX idx_testcase_relations_source ON testcase_relations(source_id);
CREATE INDEX idx_testcase_relations_destination ON testcase_relations(destination_id);
CREATE INDEX idx_testcase_relations_type ON testcase_relations(relation_type);
```

---

## Important: Check Table Prefix

Your TestLink installation may use a table prefix. To check:

1. Open `config.inc.php`
2. Look for: `$tlCfg->table_prefix` or `DB_TABLE_PREFIX`
3. If you have a prefix (e.g., `tl_`), you must use it:
   - Instead of: `testcase_relations`
   - Use: `tl_testcase_relations`

**The PHP script (`fix_missing_table.php`) handles this automatically.**

---

## Verification

After creating the table, verify it exists:

```sql
-- Check table exists
SHOW TABLES LIKE '%testcase_relations%';

-- Verify structure
DESCRIBE testcase_relations;

-- Check indexes
SHOW INDEX FROM testcase_relations;
```

Expected output should show:
- Columns: id, source_id, destination_id, relation_type, author_id, creation_ts
- Primary key on `id`
- Indexes on source_id, destination_id, and relation_type

---

## Why Was This Table Missing?

The `testcase_relations` table was introduced in TestLink 1.9.12 (circa 2014).

**Possible reasons for missing table:**

1. **Incomplete upgrade**: You upgraded from an older version, but migration scripts weren't run
2. **Manual installation**: Database was created manually without running all schema scripts
3. **Partial database restore**: A backup was restored that didn't include this table
4. **Database corruption**: The table was accidentally dropped or corrupted

---

## After the Fix

Once the table is created:

1. **Clear any caches**: Restart your web server if applicable
2. **Test the fix**: Try printing a test plan or test case document again
3. **Verify functionality**: The error should be gone

The table will start empty (0 relationships), which is normal. It will populate as users create test case relationships through the TestLink interface.

---

## Troubleshooting

### Error: "Table already exists"
- The table was already created
- Run the verification SQL to check its structure
- You may have permission issues instead

### Error: "Access denied"
- Your database user lacks CREATE TABLE permission
- Ask your DBA to grant privileges:
  ```sql
  GRANT CREATE ON database_name.* TO 'testlink_user'@'localhost';
  ```

### Error: "Syntax error"
- Check your MySQL version (should be 5.x or later)
- Ensure you're using the correct table prefix
- Try removing backticks (`) if using older MySQL

### Still getting errors after creating table?
1. Check database user permissions: `SHOW GRANTS;`
2. Verify table was created: `SHOW TABLES LIKE '%testcase_relations%';`
3. Check TestLink logs in: `C:\xampp\htdocs\testlink\logs`
4. Enable debug mode to see actual SQL errors

---

## Files Created

1. **create_testcase_relations_table.sql** - SQL script to create the table
2. **fix_missing_table.php** - Automated diagnostic and fix tool
3. **FIX_MISSING_TABLE_README.md** - This documentation file

---

## Support

If you continue to experience issues after following this guide:

1. Check the TestLink forums: http://forum.testlink.org/
2. Review TestLink documentation: http://testlink.org/
3. Check the GitHub issues: https://github.com/TestLinkOpenSourceTRMS/testlink-code/issues

---

## Technical Details

**Table Structure:**
- Based on similar `req_relations` table pattern in TestLink
- Follows TestLink naming conventions and data types
- Compatible with MySQL 5.x, 8.x, and MariaDB

**Column Details:**
- `id`: Auto-increment primary key
- `source_id`: ID of the source test case
- `destination_id`: ID of the destination/related test case
- `relation_type`: Type of relationship (1 = default)
- `author_id`: User who created the relationship
- `creation_ts`: Timestamp when relationship was created

**Performance:**
- Indexes on source_id, destination_id for fast lookups
- Index on relation_type for filtering by relationship type
- Timestamp auto-populated on insertion

---

Good luck! The table should be created successfully and your print functionality should work.
