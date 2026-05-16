<?php
$host = '127.0.0.1';
$db   = 'infixedu';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$pdo = new PDO($dsn, $user, $pass);

$stmt = $pdo->prepare("
    SELECT id, name, route, module FROM permissions 
    WHERE custom_menu_id IS NULL 
      AND is_saas = 0 
      AND parent_route IS NULL 
      AND (permission_section != 1 OR permission_section IS NULL)
      AND route IS NOT NULL AND route != ''
      AND menu_status = 1
      AND is_admin = 1
      AND role_id IS NULL
");
$stmt->execute();
$perms = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Found " . count($perms) . " permissions matching condition\n";
foreach($perms as $p) {
    echo "- ID: {$p['id']}, Name: {$p['name']}, Route: {$p['route']}\n";
}

echo "\nDashboard permissions without conditions:\n";
$stmt = $pdo->prepare("SELECT id, name, route, module, custom_menu_id, is_saas, parent_route, permission_section, route, menu_status, is_admin, role_id FROM permissions WHERE name LIKE '%Dashboard%' LIMIT 1");
$stmt->execute();
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

echo "\nWhatsapp permissions without conditions:\n";
$stmt = $pdo->prepare("SELECT id, name, route, module, custom_menu_id, is_saas, parent_route, permission_section, route, menu_status, is_admin, role_id FROM permissions WHERE name LIKE '%Whatsapp%' LIMIT 1");
$stmt->execute();
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

echo "\nStudent module permissions without conditions:\n";
$stmt = $pdo->prepare("SELECT id, name, route, module, custom_menu_id, is_saas, parent_route, permission_section, route, menu_status, is_admin, role_id FROM permissions WHERE name LIKE '%Student%' LIMIT 1");
$stmt->execute();
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
