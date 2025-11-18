#!/usr/bin/env php
<?php
/**
 * TestLink - Fix Missing testcase_relations Table
 *
 * This script diagnoses and fixes the missing testcase_relations table issue
 *
 * Usage: php fix_missing_table.php
 */

// Include TestLink configuration
$configFile = __DIR__ . '/config.inc.php';
if (!file_exists($configFile)) {
    die("ERROR: config.inc.php not found. Please run this script from the TestLink root directory.\n");
}

require_once($configFile);
require_once('lib/functions/database.class.php');

echo "============================================================================\n";
echo " TestLink - Missing testcase_relations Table Diagnostic & Fix Tool\n";
echo "============================================================================\n\n";

// Connect to database
try {
    $db = new database(DB_TYPE);
    $connection = $db->connect(DSN, DB_USER, DB_PASS);

    if (!$connection) {
        die("ERROR: Could not connect to database.\n");
    }

    echo "[OK] Successfully connected to database\n";

    // Check table prefix
    $prefix = defined('DB_TABLE_PREFIX') ? DB_TABLE_PREFIX : '';
    $tableName = $prefix . 'testcase_relations';

    echo "[INFO] Using table prefix: '" . ($prefix ? $prefix : '(none)') . "'\n";
    echo "[INFO] Full table name: '$tableName'\n\n";

    // Check if table exists
    echo "Checking if table exists...\n";
    $sql = "SHOW TABLES LIKE '$tableName'";
    $result = $db->exec_query($sql);

    if ($result && $db->num_rows($result) > 0) {
        echo "[OK] Table '$tableName' already exists!\n";
        echo "\nVerifying table structure...\n";

        $sql = "DESCRIBE $tableName";
        $result = $db->exec_query($sql);

        if ($result) {
            echo "\nTable structure:\n";
            echo str_repeat("-", 80) . "\n";
            printf("%-20s %-20s %-10s %-10s\n", "Field", "Type", "Null", "Key");
            echo str_repeat("-", 80) . "\n";

            while ($row = $db->fetch_array($result)) {
                printf("%-20s %-20s %-10s %-10s\n",
                    $row['Field'],
                    $row['Type'],
                    $row['Null'],
                    $row['Key']
                );
            }
            echo str_repeat("-", 80) . "\n";
        }

        // Count records
        $sql = "SELECT COUNT(*) as cnt FROM $tableName";
        $result = $db->exec_query($sql);
        if ($result) {
            $row = $db->fetch_array($result);
            echo "\n[INFO] Table contains " . $row['cnt'] . " relationship(s)\n";
        }

        echo "\n[SUCCESS] Table exists and is accessible.\n";
        echo "If you're still getting errors, please check:\n";
        echo "  1. Database user permissions\n";
        echo "  2. TestLink configuration (config.inc.php)\n";
        echo "  3. PHP error logs for more details\n";

    } else {
        echo "[ERROR] Table '$tableName' does NOT exist!\n\n";
        echo "Would you like to create it now? (yes/no): ";

        $handle = fopen("php://stdin", "r");
        $line = fgets($handle);
        $response = trim(strtolower($line));
        fclose($handle);

        if ($response === 'yes' || $response === 'y') {
            echo "\nCreating table '$tableName'...\n";

            $createSQL = "CREATE TABLE $tableName (
              `id` int(10) unsigned NOT NULL auto_increment,
              `source_id` int(10) unsigned NOT NULL,
              `destination_id` int(10) unsigned NOT NULL,
              `relation_type` smallint(5) unsigned NOT NULL default '1',
              `author_id` int(10) unsigned default NULL,
              `creation_ts` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (`id`)
            ) DEFAULT CHARSET=utf8 COMMENT='Test case relationships'";

            $result = $db->exec_query($createSQL);

            if ($result !== false) {
                echo "[SUCCESS] Table created successfully!\n\n";

                // Create indexes
                echo "Creating indexes for better performance...\n";

                $indexes = array(
                    "CREATE INDEX idx_testcase_relations_source ON $tableName(source_id)",
                    "CREATE INDEX idx_testcase_relations_destination ON $tableName(destination_id)",
                    "CREATE INDEX idx_testcase_relations_type ON $tableName(relation_type)"
                );

                foreach ($indexes as $indexSQL) {
                    $db->exec_query($indexSQL);
                }

                echo "[SUCCESS] Indexes created!\n\n";
                echo "The testcase_relations table has been created successfully.\n";
                echo "You should now be able to print documents without errors.\n";
            } else {
                echo "[ERROR] Failed to create table!\n";
                echo "Error: " . $db->error_msg() . "\n";
                echo "\nPlease try running the SQL script manually:\n";
                echo "  mysql -u your_user -p your_database < create_testcase_relations_table.sql\n";
            }
        } else {
            echo "\nTable creation cancelled.\n";
            echo "\nTo create the table manually, run:\n";
            echo "  mysql -u " . DB_USER . " -p " . DB_NAME . " < create_testcase_relations_table.sql\n";
            echo "\nOr import create_testcase_relations_table.sql via phpMyAdmin\n";
        }
    }

} catch (Exception $e) {
    echo "[ERROR] " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n============================================================================\n";
echo " Diagnostic Complete\n";
echo "============================================================================\n";
?>
