<?php
$host = '127.0.0.1';
$db   = 'infixedu';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
     echo "--- CORE DATA AUDIT ---\n";
     
     $coreTables = ['sm_students', 'sm_staffs', 'sm_classes', 'sm_sections', 'sm_subjects', 'sm_menus'];
     foreach ($coreTables as $table) {
         $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
         echo str_pad($table, 20) . ": " . $stmt->fetch()['count'] . " records\n";
     }

     echo "\n--- CUSTOM MODULES DATA AUDIT ---\n";
     $customTables = [
         'sm_transfer_certificates', 'sm_medical_records', 'sm_vaccination_records', 
         'sm_book_banks', 'sm_book_bank_issues', 'sm_thirukkurals', 
         'sm_vendors', 'sm_purchase_orders', 'sm_vendor_payments',
         'sm_hostels', 'sm_hostel_rooms', 'sm_hostel_allocations', 'sm_hostel_fees'
     ];
     foreach ($customTables as $table) {
         try {
             $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
             echo str_pad($table, 25) . ": " . $stmt->fetch()['count'] . " records\n";
         } catch (Exception $e) {
             echo str_pad($table, 25) . ": MISSING TABLE\n";
         }
     }

     echo "\n--- SIDEBAR CONNECTIVITY CHECK ---\n";
     $routes = ['tc-list', 'medical-records', 'vaccination-records', 'book-bank', 'thirukkural', 'vendor-list', 'hostel-list'];
     foreach ($routes as $route) {
         $stmt = $pdo->prepare("SELECT id FROM sm_menus WHERE route = ?");
         $stmt->execute([$route]);
         $res = $stmt->fetch();
         echo str_pad($route, 25) . ": " . ($res ? "FOUND (ID: {$res['id']})" : "NOT FOUND") . "\n";
     }

} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
