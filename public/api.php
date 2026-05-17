<?php
/**
 * ERPV1 Student Management Dashboard API
 * Comprehensive API for all 10 dashboard modules
 */

$db_host = '127.0.0.1';
$db_name = 'infixedu';
$db_user = 'root';
$db_pass = '';

// Load database configurations dynamically from Laravel's .env
$env_path = dirname(__DIR__) . '/.env';
if (file_exists($env_path)) {
    $lines = file($env_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $name = trim($parts[0]);
            $value = trim(trim($parts[1]), '"\'');
            if ($name === 'DB_HOST') $db_host = $value;
            if ($name === 'DB_DATABASE') $db_name = $value;
            if ($name === 'DB_USERNAME') $db_user = $value;
            if ($name === 'DB_PASSWORD') $db_pass = $value;
        }
    }
}

@ini_set('max_execution_time', '3600');
@ini_set('memory_limit', '512M');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { http_response_code(200); exit(); }

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "DB connection failed", "details" => $e->getMessage()]);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$parts = array_values(array_filter(explode('/', trim($path, '/'))));
// Find resource after api.php
$apiIdx = array_search('api.php', $parts);
$resource = isset($parts[$apiIdx+1]) ? $parts[$apiIdx+1] : '';
$subResource = isset($parts[$apiIdx+2]) ? $parts[$apiIdx+2] : '';
$id = isset($_GET['id']) ? intval($_GET['id']) : null;
$input = json_decode(file_get_contents('php://input'), true);

switch ($resource) {
    case 'students': handleStudents($method, $id, $input, $pdo); break;
    case 'student': handleStudentDetail($subResource, $id, $input, $pdo); break;
    case 'behaviors': handleBehaviors($method, $id, $input, $pdo); break;
    case 'activities': handleActivities($method, $id, $input, $pdo); break;
    case 'achievements': handleAchievements($method, $id, $input, $pdo); break;
    case 'academics': handleAcademics($method, $id, $input, $pdo); break;
    case 'attendance': handleAttendance($method, $id, $input, $pdo); break;
    case 'fees': handleFees($method, $id, $input, $pdo); break;
    case 'spending': handleSpending($method, $id, $input, $pdo); break;
    case 'library': handleLibrary($method, $id, $input, $pdo); break;
    case 'communications': handleCommunications($method, $id, $input, $pdo); break;
    case 'stats': handleStats($pdo); break;
    case 'search': handleSearch($pdo); break;
    case 'engagement': handleEngagement($pdo); break;
    case 'upload': handleUpload($_FILES, $_POST, $pdo); break;
    default:
        echo json_encode(["api" => "ERPV1 Student Dashboard API", "version" => "2.0", "endpoints" => [
            "students","student/{id}","behaviors","activities","achievements",
            "academics","attendance","fees","spending","library","communications",
            "stats","search","engagement"
        ]]);
}

// ─── 1. STUDENT PROFILES ───
function handleStudents($method, $id, $data, $pdo) {
    if ($method === 'GET') {
        $search = $_GET['search'] ?? '';
        $classFilter = $_GET['class_id'] ?? '';
        $sectionFilter = $_GET['section_id'] ?? '';
        $limit = intval($_GET['limit'] ?? 100);

        $sql = "SELECT s.id, s.full_name, s.first_name, s.last_name, s.admission_no, s.roll_no,
                s.email, s.mobile, s.date_of_birth, s.admission_date, s.student_photo,
                s.current_address, s.permanent_address, s.age, s.height, s.weight,
                s.gender_id, s.bloodgroup_id, s.class_id, s.section_id, s.active_status,
                c.class_name, sec.section_name
                FROM sm_students s
                LEFT JOIN sm_classes c ON s.class_id = c.id
                LEFT JOIN sm_sections sec ON s.section_id = sec.id
                WHERE s.active_status = 1";
        $params = [];

        if ($search) {
            $sql .= " AND (s.full_name LIKE ? OR s.admission_no LIKE ? OR s.email LIKE ?)";
            $params[] = "%$search%"; $params[] = "%$search%"; $params[] = "%$search%";
        }
        if ($classFilter) { $sql .= " AND s.class_id = ?"; $params[] = $classFilter; }
        if ($sectionFilter) { $sql .= " AND s.section_id = ?"; $params[] = $sectionFilter; }

        if ($id) {
            $sql = "SELECT s.*, c.class_name, sec.section_name
                    FROM sm_students s
                    LEFT JOIN sm_classes c ON s.class_id = c.id
                    LEFT JOIN sm_sections sec ON s.section_id = sec.id
                    WHERE s.id = ?";
            $params = [$id];
        }

        $sql .= " ORDER BY s.id ASC LIMIT $limit";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        echo json_encode($id ? $stmt->fetch() : $stmt->fetchAll());
    } elseif ($method === 'PUT' && $id) {
        $fields = [];
        $params = [];
        foreach (['full_name','email','mobile','current_address','date_of_birth','class_id','section_id','student_photo'] as $f) {
            if (isset($data[$f])) { $fields[] = "$f = ?"; $params[] = $data[$f]; }
        }
        if ($fields) {
            $params[] = $id;
            $pdo->prepare("UPDATE sm_students SET ".implode(',',$fields)." WHERE id = ?")->execute($params);
        }
        echo json_encode(["success" => true]);
    }
}

function handleStudentDetail($sub, $id, $data, $pdo) {
    if (!$id) $id = intval($_GET['id'] ?? 0);
    if (!$id) { echo json_encode(["error" => "Student ID required"]); return; }

    $student = $pdo->prepare("SELECT s.*, c.class_name, sec.section_name FROM sm_students s LEFT JOIN sm_classes c ON s.class_id=c.id LEFT JOIN sm_sections sec ON s.section_id=sec.id WHERE s.id=?")->execute([$id]);
    $student = $pdo->prepare("SELECT s.*, c.class_name, sec.section_name FROM sm_students s LEFT JOIN sm_classes c ON s.class_id=c.id LEFT JOIN sm_sections sec ON s.section_id=sec.id WHERE s.id=?");
    $student->execute([$id]);
    $profile = $student->fetch();

    $behaviors = $pdo->prepare("SELECT * FROM sm_student_behaviors WHERE student_id=? ORDER BY reported_date DESC");
    $behaviors->execute([$id]);

    $activities = $pdo->prepare("SELECT * FROM sm_student_activities WHERE student_id=?");
    $activities->execute([$id]);

    $achievements = $pdo->prepare("SELECT * FROM sm_student_achievements WHERE student_id=? ORDER BY achievement_date DESC");
    $achievements->execute([$id]);

    $attendance = $pdo->prepare("SELECT attendance_type, COUNT(*) as count FROM sm_student_attendances WHERE student_id=? GROUP BY attendance_type");
    $attendance->execute([$id]);

    $fees = $pdo->prepare("SELECT SUM(amount) as total_paid FROM sm_fees_payments WHERE student_id=?");
    $fees->execute([$id]);

    $spending = $pdo->prepare("SELECT category, SUM(amount) as total FROM sm_student_spending WHERE student_id=? GROUP BY category");
    $spending->execute([$id]);

    $communications = $pdo->prepare("SELECT * FROM sm_student_communications WHERE student_id=? OR student_id IS NULL ORDER BY sent_at DESC LIMIT 10");
    $communications->execute([$id]);

    echo json_encode([
        "profile" => $profile,
        "behaviors" => $behaviors->fetchAll(),
        "activities" => $activities->fetchAll(),
        "achievements" => $achievements->fetchAll(),
        "attendance" => $attendance->fetchAll(),
        "fees" => $fees->fetch(),
        "spending" => $spending->fetchAll(),
        "communications" => $communications->fetchAll()
    ]);
}

// ─── 2. BEHAVIOR TRACKING ───
function handleBehaviors($method, $id, $data, $pdo) {
    if ($method === 'GET') {
        $studentId = $_GET['student_id'] ?? null;
        if ($studentId) {
            $stmt = $pdo->prepare("SELECT b.*, s.full_name FROM sm_student_behaviors b JOIN sm_students s ON b.student_id=s.id WHERE b.student_id=? ORDER BY b.reported_date DESC");
            $stmt->execute([$studentId]);
        } else {
            $stmt = $pdo->query("SELECT b.*, s.full_name FROM sm_student_behaviors b JOIN sm_students s ON b.student_id=s.id ORDER BY b.reported_date DESC LIMIT 100");
        }
        echo json_encode($stmt->fetchAll());
    } elseif ($method === 'POST') {
        $stmt = $pdo->prepare("INSERT INTO sm_student_behaviors (student_id,behavior_type,category,remarks,reported_by,reported_date) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$data['student_id'],$data['behavior_type'],$data['category'],$data['remarks']??'',$data['reported_by']??'Admin',date('Y-m-d')]);
        echo json_encode(["success" => true, "id" => $pdo->lastInsertId()]);
    }
}

// ─── 3. ACTIVITIES & INTERESTS ───
function handleActivities($method, $id, $data, $pdo) {
    if ($method === 'GET') {
        $studentId = $_GET['student_id'] ?? null;
        $type = $_GET['type'] ?? null;
        $sql = "SELECT a.*, s.full_name FROM sm_student_activities a JOIN sm_students s ON a.student_id=s.id WHERE 1=1";
        $params = [];
        if ($studentId) { $sql .= " AND a.student_id=?"; $params[] = $studentId; }
        if ($type) { $sql .= " AND a.activity_type=?"; $params[] = $type; }
        $sql .= " ORDER BY a.activity_name";
        $stmt = $pdo->prepare($sql); $stmt->execute($params);
        echo json_encode($stmt->fetchAll());
    } elseif ($method === 'POST') {
        $stmt = $pdo->prepare("INSERT INTO sm_student_activities (student_id,activity_type,activity_name,skill_level,notes) VALUES (?,?,?,?,?)");
        $stmt->execute([$data['student_id'],$data['activity_type'],$data['activity_name'],$data['skill_level']??'beginner',$data['notes']??'']);
        echo json_encode(["success" => true, "id" => $pdo->lastInsertId()]);
    }
}

// ─── 4. ACADEMIC PERFORMANCE ───
function handleAcademics($method, $id, $data, $pdo) {
    $studentId = $_GET['student_id'] ?? $id;
    if ($studentId) {
        $marks = $pdo->prepare("SELECT m.*, sub.subject_name, e.title as exam_name FROM sm_mark_stores m LEFT JOIN sm_subjects sub ON m.subject_id=sub.id LEFT JOIN sm_exam_types e ON m.exam_term_id=e.id WHERE m.student_id=? ORDER BY m.exam_term_id, sub.subject_name");
        $marks->execute([$studentId]);
        $attendance = $pdo->prepare("SELECT attendance_type, COUNT(*) as count, attendance_date FROM sm_student_attendances WHERE student_id=? GROUP BY attendance_type");
        $attendance->execute([$studentId]);
        echo json_encode(["marks" => $marks->fetchAll(), "attendance_summary" => $attendance->fetchAll()]);
    } else {
        $stmt = $pdo->query("SELECT s.id, s.full_name, s.admission_no, COALESCE(AVG(m.total_marks),0) as avg_marks FROM sm_students s LEFT JOIN sm_mark_stores m ON s.id=m.student_id WHERE s.active_status=1 GROUP BY s.id ORDER BY avg_marks DESC LIMIT 50");
        echo json_encode($stmt->fetchAll());
    }
}

// ─── 5. ACHIEVEMENTS ───
function handleAchievements($method, $id, $data, $pdo) {
    if ($method === 'GET') {
        $studentId = $_GET['student_id'] ?? null;
        $type = $_GET['type'] ?? null;
        $sql = "SELECT a.*, s.full_name FROM sm_student_achievements a JOIN sm_students s ON a.student_id=s.id WHERE 1=1";
        $params = [];
        if ($studentId) { $sql .= " AND a.student_id=?"; $params[] = $studentId; }
        if ($type) { $sql .= " AND a.achievement_type=?"; $params[] = $type; }
        $sql .= " ORDER BY a.achievement_date DESC";
        $stmt = $pdo->prepare($sql); $stmt->execute($params);
        echo json_encode($stmt->fetchAll());
    } elseif ($method === 'POST') {
        $stmt = $pdo->prepare("INSERT INTO sm_student_achievements (student_id,achievement_type,title,description,achievement_date,participation_status) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$data['student_id'],$data['achievement_type'],$data['title'],$data['description']??'',$data['achievement_date']??date('Y-m-d'),$data['participation_status']??'participated']);
        echo json_encode(["success" => true, "id" => $pdo->lastInsertId()]);
    }
}

// ─── 6. COMMUNICATION MODULE ───
function handleCommunications($method, $id, $data, $pdo) {
    if ($method === 'GET') {
        $stmt = $pdo->query("SELECT c.*, s.full_name FROM sm_student_communications c LEFT JOIN sm_students s ON c.student_id=s.id ORDER BY c.sent_at DESC LIMIT 50");
        echo json_encode($stmt->fetchAll());
    } elseif ($method === 'POST') {
        $stmt = $pdo->prepare("INSERT INTO sm_student_communications (student_id,channel,subject,message,event_type,sent_by) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$data['student_id']??null,$data['channel'],$data['subject'],$data['message'],$data['event_type']??'general',$data['sent_by']??'Admin']);
        echo json_encode(["success" => true, "id" => $pdo->lastInsertId()]);
    }
}

// ─── 7. LIBRARY MANAGEMENT ───
function handleLibrary($method, $id, $data, $pdo) {
    if ($method === 'GET') {
        $books = $pdo->query("SELECT b.*, COALESCE(bi.pending,0) as pending_count FROM sm_books b LEFT JOIN (SELECT book_id, COUNT(*) as pending FROM sm_book_issues WHERE issue_status='I' GROUP BY book_id) bi ON b.id=bi.book_id ORDER BY b.book_title LIMIT 100");
        $issues = $pdo->query("SELECT bi.*, b.book_title, b.author_name FROM sm_book_issues bi JOIN sm_books b ON bi.book_id=b.id ORDER BY bi.given_date DESC LIMIT 50");
        $pendingBooks = $pdo->query("SELECT COUNT(*) as count FROM sm_book_issues WHERE issue_status='I'")->fetch();
        echo json_encode([
            "books" => $books->fetchAll(),
            "issues" => $issues->fetchAll(),
            "pending_count" => $pendingBooks['count'] ?? 0
        ]);
    }
}

// ─── 8. FEE MANAGEMENT ───
function handleFees($method, $id, $data, $pdo) {
    $studentId = $_GET['student_id'] ?? $id;
    if ($studentId) {
        $payments = $pdo->prepare("SELECT fp.*, ft.name as fee_type FROM sm_fees_payments fp LEFT JOIN sm_fees_types ft ON fp.fees_type_id=ft.id WHERE fp.student_id=? ORDER BY fp.payment_date DESC");
        $payments->execute([$studentId]);
        $total = $pdo->prepare("SELECT SUM(amount) as total_paid FROM sm_fees_payments WHERE student_id=?");
        $total->execute([$studentId]);
        $spending = $pdo->prepare("SELECT * FROM sm_student_spending WHERE student_id=? ORDER BY spending_date DESC");
        $spending->execute([$studentId]);
        echo json_encode([
            "payments" => $payments->fetchAll(),
            "total_paid" => $total->fetch()['total_paid'] ?? 0,
            "spending" => $spending->fetchAll()
        ]);
    } else {
        $summary = $pdo->query("SELECT s.id, s.full_name, s.admission_no, COALESCE(SUM(fp.amount),0) as total_paid, COALESCE(sp.total_spent,0) as total_spent FROM sm_students s LEFT JOIN sm_fees_payments fp ON s.id=fp.student_id LEFT JOIN (SELECT student_id, SUM(amount) as total_spent FROM sm_student_spending GROUP BY student_id) sp ON s.id=sp.student_id WHERE s.active_status=1 GROUP BY s.id ORDER BY s.full_name LIMIT 100");
        echo json_encode($summary->fetchAll());
    }
}

// ─── 8b. SPENDING ───
function handleSpending($method, $id, $data, $pdo) {
    if ($method === 'GET') {
        $studentId = $_GET['student_id'] ?? null;
        if ($studentId) {
            $stmt = $pdo->prepare("SELECT * FROM sm_student_spending WHERE student_id=? ORDER BY spending_date DESC");
            $stmt->execute([$studentId]);
        } else {
            $stmt = $pdo->query("SELECT sp.*, s.full_name FROM sm_student_spending sp JOIN sm_students s ON sp.student_id=s.id ORDER BY sp.spending_date DESC LIMIT 100");
        }
        echo json_encode($stmt->fetchAll());
    } elseif ($method === 'POST') {
        $stmt = $pdo->prepare("INSERT INTO sm_student_spending (student_id,category,amount,description,spending_date) VALUES (?,?,?,?,?)");
        $stmt->execute([$data['student_id'],$data['category'],$data['amount'],$data['description']??'',$data['spending_date']??date('Y-m-d')]);
        echo json_encode(["success" => true, "id" => $pdo->lastInsertId()]);
    }
}

// ─── 9. ATTENDANCE ───
function handleAttendance($method, $id, $data, $pdo) {
    $studentId = $_GET['student_id'] ?? $id;
    if ($studentId) {
        $stmt = $pdo->prepare("SELECT * FROM sm_student_attendances WHERE student_id=? ORDER BY attendance_date DESC LIMIT 60");
        $stmt->execute([$studentId]);
        $summary = $pdo->prepare("SELECT attendance_type, COUNT(*) as count FROM sm_student_attendances WHERE student_id=? GROUP BY attendance_type");
        $summary->execute([$studentId]);
        echo json_encode(["records" => $stmt->fetchAll(), "summary" => $summary->fetchAll()]);
    } else {
        $stmt = $pdo->query("SELECT s.id, s.full_name, COUNT(CASE WHEN a.attendance_type='P' THEN 1 END) as present, COUNT(CASE WHEN a.attendance_type='A' THEN 1 END) as absent, COUNT(CASE WHEN a.attendance_type='L' THEN 1 END) as late, COUNT(a.id) as total FROM sm_students s LEFT JOIN sm_student_attendances a ON s.id=a.student_id WHERE s.active_status=1 GROUP BY s.id LIMIT 100");
        echo json_encode($stmt->fetchAll());
    }
}

// ─── 10. DASHBOARD STATS ───
function handleStats($pdo) {
    $students = $pdo->query("SELECT COUNT(*) as c FROM sm_students WHERE active_status=1")->fetch()['c'];
    $classes = $pdo->query("SELECT COUNT(*) as c FROM sm_classes WHERE active_status=1")->fetch()['c'];
    $sections = $pdo->query("SELECT COUNT(*) as c FROM sm_sections WHERE active_status=1")->fetch()['c'];
    $books = $pdo->query("SELECT COUNT(*) as c FROM sm_books WHERE active_status=1")->fetch()['c'];

    $behaviorStats = $pdo->query("SELECT behavior_type, COUNT(*) as count FROM sm_student_behaviors GROUP BY behavior_type")->fetchAll();
    $activityStats = $pdo->query("SELECT activity_type, COUNT(*) as count FROM sm_student_activities GROUP BY activity_type")->fetchAll();
    $achievementStats = $pdo->query("SELECT participation_status, COUNT(*) as count FROM sm_student_achievements GROUP BY participation_status")->fetchAll();

    $recentBehaviors = $pdo->query("SELECT b.*, s.full_name FROM sm_student_behaviors b JOIN sm_students s ON b.student_id=s.id ORDER BY b.reported_date DESC LIMIT 5")->fetchAll();
    $recentAchievements = $pdo->query("SELECT a.*, s.full_name FROM sm_student_achievements a JOIN sm_students s ON a.student_id=s.id ORDER BY a.achievement_date DESC LIMIT 5")->fetchAll();

    $genderDist = $pdo->query("SELECT gender_id, COUNT(*) as count FROM sm_students WHERE active_status=1 GROUP BY gender_id")->fetchAll();

    echo json_encode([
        "totalStudents" => (int)$students,
        "totalClasses" => (int)$classes,
        "totalSections" => (int)$sections,
        "totalBooks" => (int)$books,
        "behaviorStats" => $behaviorStats,
        "activityStats" => $activityStats,
        "achievementStats" => $achievementStats,
        "recentBehaviors" => $recentBehaviors,
        "recentAchievements" => $recentAchievements,
        "genderDistribution" => $genderDist
    ]);
}

// ─── 10b. SMART SEARCH ───
function handleSearch($pdo) {
    $q = $_GET['q'] ?? '';
    $filter = $_GET['filter'] ?? 'all';
    if (!$q) { echo json_encode([]); return; }

    $results = [];
    $like = "%$q%";

    if ($filter === 'all' || $filter === 'students') {
        $stmt = $pdo->prepare("SELECT id, full_name, admission_no, email, class_id, section_id, 'student' as result_type FROM sm_students WHERE full_name LIKE ? OR admission_no LIKE ? OR email LIKE ? LIMIT 20");
        $stmt->execute([$like, $like, $like]);
        $results = array_merge($results, $stmt->fetchAll());
    }
    if ($filter === 'all' || $filter === 'behavior') {
        $stmt = $pdo->prepare("SELECT b.id, s.full_name, b.behavior_type, b.category, b.remarks, 'behavior' as result_type FROM sm_student_behaviors b JOIN sm_students s ON b.student_id=s.id WHERE s.full_name LIKE ? OR b.remarks LIKE ? LIMIT 10");
        $stmt->execute([$like, $like]);
        $results = array_merge($results, $stmt->fetchAll());
    }
    if ($filter === 'all' || $filter === 'achievements') {
        $stmt = $pdo->prepare("SELECT a.id, s.full_name, a.title, a.achievement_type, a.participation_status, 'achievement' as result_type FROM sm_student_achievements a JOIN sm_students s ON a.student_id=s.id WHERE s.full_name LIKE ? OR a.title LIKE ? LIMIT 10");
        $stmt->execute([$like, $like]);
        $results = array_merge($results, $stmt->fetchAll());
    }
    if ($filter === 'all' || $filter === 'activities') {
        $stmt = $pdo->prepare("SELECT a.id, s.full_name, a.activity_name, a.activity_type, a.skill_level, 'activity' as result_type FROM sm_student_activities a JOIN sm_students s ON a.student_id=s.id WHERE s.full_name LIKE ? OR a.activity_name LIKE ? LIMIT 10");
        $stmt->execute([$like, $like]);
        $results = array_merge($results, $stmt->fetchAll());
    }
    echo json_encode($results);
}

// ─── 9b. ENGAGEMENT ───
function handleEngagement($pdo) {
    $engaged = $pdo->query("SELECT s.id, s.full_name,
        (SELECT COUNT(*) FROM sm_student_behaviors WHERE student_id=s.id) as behavior_count,
        (SELECT COUNT(*) FROM sm_student_activities WHERE student_id=s.id) as activity_count,
        (SELECT COUNT(*) FROM sm_student_achievements WHERE student_id=s.id) as achievement_count,
        (SELECT COUNT(*) FROM sm_student_attendances WHERE student_id=s.id AND attendance_type='P') as present_count
        FROM sm_students s WHERE s.active_status=1 ORDER BY s.id LIMIT 50")->fetchAll();

    foreach ($engaged as &$s) {
        $score = ($s['behavior_count']*10) + ($s['activity_count']*15) + ($s['achievement_count']*25) + ($s['present_count']*2);
        $s['engagement_score'] = $score;
        $s['status'] = $score >= 50 ? 'highly_active' : ($score >= 20 ? 'active' : 'inactive');
    }
    usort($engaged, fn($a,$b) => $b['engagement_score'] - $a['engagement_score']);
    echo json_encode($engaged);
}

// ─── FILE UPLOAD ───
function handleUpload($files, $post, $pdo) {
    if (!isset($files['file'])) { http_response_code(400); echo json_encode(["error" => "No file"]); return; }
    $file = $files['file'];
    $uploadDir = 'uploads/student/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fileName = uniqid() . '.' . $ext;
    $targetPath = $uploadDir . $fileName;
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        echo json_encode(["success" => true, "url" => $targetPath]);
    } else {
        http_response_code(500); echo json_encode(["error" => "Upload failed"]);
    }
}
