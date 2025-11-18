-- ============================================================================
-- SQL Script to Create Missing testcase_relations Table
-- ============================================================================
-- This table is used to store relationships between test cases in TestLink
-- (e.g., dependencies, prerequisites, related test cases, etc.)
--
-- This feature was introduced in TestLink 1.9.12
-- ============================================================================

-- Drop table if it exists (optional - comment out if you want to be safe)
-- DROP TABLE IF EXISTS testcase_relations;

-- Create the testcase_relations table
CREATE TABLE testcase_relations (
  `id` int(10) unsigned NOT NULL auto_increment,
  `source_id` int(10) unsigned NOT NULL,
  `destination_id` int(10) unsigned NOT NULL,
  `relation_type` smallint(5) unsigned NOT NULL default '1',
  `author_id` int(10) unsigned default NULL,
  `creation_ts` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8 COMMENT='Test case relationships - links between test cases';

-- Optional: Add indexes for better performance
CREATE INDEX idx_testcase_relations_source ON testcase_relations(source_id);
CREATE INDEX idx_testcase_relations_destination ON testcase_relations(destination_id);
CREATE INDEX idx_testcase_relations_type ON testcase_relations(relation_type);

-- ============================================================================
-- IMPORTANT NOTES:
-- ============================================================================
-- 1. If your TestLink installation uses a table prefix, you need to add it
--    For example, if your prefix is "tl_", change "testcase_relations" to "tl_testcase_relations"
--    Check your config.inc.php file for: $tlCfg->table_prefix
--
-- 2. Make sure to backup your database before running this script:
--    mysqldump -u your_user -p your_database > backup_before_fix.sql
--
-- 3. To run this script:
--    mysql -u your_user -p your_database < create_testcase_relations_table.sql
--
--    OR via phpMyAdmin: Import > Select this file > Go
--
-- ============================================================================

-- Verify the table was created successfully
SELECT 'testcase_relations table created successfully!' AS Status;
SHOW CREATE TABLE testcase_relations;
