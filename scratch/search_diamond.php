<?php
require_once 'c:/xampp/htdocs/sri/admin/project/api/v1/core/Database.php';
use Api\V1\Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    
    echo "Searching for 'Diamond' in all tables...\n";
    $stmt = $db->query("SHOW TABLES");
    while ($tableRow = $stmt->fetch(PDO::FETCH_NUM)) {
        $table = $tableRow[0];
        $colsStmt = $db->query("SHOW COLUMNS FROM $table");
        while ($colRow = $colsStmt->fetch(PDO::FETCH_ASSOC)) {
            $col = $colRow['Field'];
            $type = $colRow['Type'];
            if (strpos($type, 'char') !== false || strpos($type, 'text') !== false) {
                $searchStmt = $db->prepare("SELECT COUNT(*) FROM $table WHERE `$col` LIKE '%Diamond%'");
                $searchStmt->execute();
                $count = $searchStmt->fetchColumn();
                if ($count > 0) {
                    echo "  Found $count matches in $table.$col\n";
                }
            }
        }
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
