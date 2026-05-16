<?php
/**
 * ERPV1 API Bridge
 * This file allows the PhotoProcessor frontend to connect to the ERP database.
 */

// --- CONFIGURATION ---
$db_host = '127.0.0.1';
$db_name = 'infixedu';
$db_user = 'root';
$db_pass = '';

// --- PHP RUNTIME OVERRIDES ---
@ini_set('upload_max_filesize', '10240M');
@ini_set('post_max_size', '10500M');
@ini_set('max_execution_time', '3600');
@ini_set('memory_limit', '512M');

// --- CORS & HEADERS ---
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// --- DATABASE CONNECTION ---
try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed", "details" => $e->getMessage()]);
    exit();
}

// --- ROUTING ---
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$parts = explode('/', trim($path, '/'));
$resource = end($parts);
$id = isset($_GET['id']) ? $_GET['id'] : null;
$input = json_decode(file_get_contents('php://input'), true);

switch ($resource) {
    case 'students':
    case 'records':
        handleStudents($method, $id, $input, $pdo);
        break;
        
    case 'upload':
        handleUpload($_FILES, $_POST, $pdo);
        break;

    case 'stats':
        handleStats($pdo);
        break;

    default:
        http_response_code(404);
        echo json_encode(["error" => "Endpoint not found: $resource"]);
        break;
}

function handleStudents($method, $id, $data, $pdo) {
    if ($method === 'GET') {
        if ($id) {
            $stmt = $pdo->prepare("SELECT id, full_name as student_name, admission_no as roll_number, student_photo as photo_url FROM sm_students WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode($stmt->fetch());
        } else {
            $stmt = $pdo->query("SELECT id, full_name as student_name, admission_no as roll_number, student_photo as photo_url FROM sm_students LIMIT 100");
            echo json_encode($stmt->fetchAll());
        }
    } elseif ($method === 'PUT') {
        if ($id && isset($data['photoUrl'])) {
            $stmt = $pdo->prepare("UPDATE sm_students SET student_photo = ? WHERE id = ?");
            $stmt->execute([$data['photoUrl'], $id]);
            echo json_encode(["success" => true]);
        }
    }
}

function handleUpload($files, $post, $pdo) {
    if (!isset($files['file'])) {
        http_response_code(400);
        echo json_encode(["error" => "No file received"]);
        return;
    }

    $file = $files['file'];
    $uploadDir = 'public/uploads/student/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fileName = uniqid() . '.' . $ext;
    $targetPath = $uploadDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        $publicUrl = 'uploads/student/' . $fileName;
        echo json_encode([
            "success" => true,
            "url" => $publicUrl,
            "path" => $targetPath
        ]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Failed to move uploaded file"]);
    }
}

function handleStats($pdo) {
    $students = $pdo->query("SELECT COUNT(*) FROM sm_students")->fetchColumn();
    echo json_encode(["totalStudents" => (int)$students]);
}
