<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Verificar que el usuario sea admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'message' => 'Acceso denegado. Se requieren permisos de administrador.'
    ]);
    exit;
}

// Configuración de la base de datos
$host = 'localhost';
$dbname = 'movies_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido');
    }
    
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'create':
            createMovie($pdo);
            break;
        case 'update':
            updateMovie($pdo);
            break;
        case 'delete':
            deleteMovie($pdo);
            break;
        default:
            throw new Exception('Acción no válida');
    }
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error de base de datos: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

function createMovie($pdo) {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $image = trim($_POST['image'] ?? '');
    $genre = trim($_POST['genre'] ?? '');
    $year = !empty($_POST['year']) ? (int)$_POST['year'] : null;
    $video_url = trim($_POST['video_url'] ?? '');
    
    if (empty($title)) {
        throw new Exception('El título es obligatorio');
    }
    
    // Validar año si se proporciona
    if ($year !== null && ($year < 1900 || $year > 2030)) {
        throw new Exception('El año debe estar entre 1900 y 2030');
    }
    
    // Validar URLs si se proporcionan
    if (!empty($image) && !filter_var($image, FILTER_VALIDATE_URL)) {
        throw new Exception('La URL de la imagen no es válida');
    }
    
    if (!empty($video_url) && !filter_var($video_url, FILTER_VALIDATE_URL)) {
        throw new Exception('La URL del video no es válida');
    }
    
    $stmt = $pdo->prepare("
        INSERT INTO movies (title, description, image, genre, year, video_url) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([$title, $description, $image, $genre, $year, $video_url]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Película creada exitosamente',
        'id' => $pdo->lastInsertId()
    ]);
}

function updateMovie($pdo) {
    $id = (int)($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $image = trim($_POST['image'] ?? '');
    $genre = trim($_POST['genre'] ?? '');
    $year = !empty($_POST['year']) ? (int)$_POST['year'] : null;
    $video_url = trim($_POST['video_url'] ?? '');
    
    if ($id <= 0) {
        throw new Exception('ID de película no válido');
    }
    
    if (empty($title)) {
        throw new Exception('El título es obligatorio');
    }
    
    // Validar año si se proporciona
    if ($year !== null && ($year < 1900 || $year > 2030)) {
        throw new Exception('El año debe estar entre 1900 y 2030');
    }
    
    // Validar URLs si se proporcionan
    if (!empty($image) && !filter_var($image, FILTER_VALIDATE_URL)) {
        throw new Exception('La URL de la imagen no es válida');
    }
    
    if (!empty($video_url) && !filter_var($video_url, FILTER_VALIDATE_URL)) {
        throw new Exception('La URL del video no es válida');
    }
    
    // Verificar que la película existe
    $checkStmt = $pdo->prepare("SELECT id FROM movies WHERE id = ?");
    $checkStmt->execute([$id]);
    if (!$checkStmt->fetch()) {
        throw new Exception('La película no existe');
    }
    
    $stmt = $pdo->prepare("
        UPDATE movies 
        SET title = ?, description = ?, image = ?, genre = ?, year = ?, video_url = ?
        WHERE id = ?
    ");
    
    $stmt->execute([$title, $description, $image, $genre, $year, $video_url, $id]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Película actualizada exitosamente'
    ]);
}

function deleteMovie($pdo) {
    $id = (int)($_POST['id'] ?? 0);
    
    if ($id <= 0) {
        throw new Exception('ID de película no válido');
    }
    
    // Verificar que la película existe
    $checkStmt = $pdo->prepare("SELECT id FROM movies WHERE id = ?");
    $checkStmt->execute([$id]);
    if (!$checkStmt->fetch()) {
        throw new Exception('La película no existe');
    }
    
    $stmt = $pdo->prepare("DELETE FROM movies WHERE id = ?");
    $stmt->execute([$id]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Película eliminada exitosamente'
    ]);
}
?>