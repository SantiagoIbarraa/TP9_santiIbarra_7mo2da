<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// Configuración de la base de datos
$host = 'localhost';  // Cambiado de vuelta a localhost para XAMPP
$dbname = 'movies_db';
$username = 'root';
$password = '';  // XAMPP por defecto no tiene contraseña

try {
    // Intentar conexión con PDO
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    
    $pdo = new PDO($dsn, $username, $password, $options);
    
    // Obtener todas las películas
    $stmt = $pdo->prepare("SELECT * FROM movies ORDER BY created_at DESC");
    $stmt->execute();
    $movies = $stmt->fetchAll();
    
    // Convertir campos numéricos
    foreach ($movies as &$movie) {
        $movie['id'] = (int)$movie['id'];
        $movie['year'] = $movie['year'] ? (int)$movie['year'] : null;
    }
    
    echo json_encode($movies);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => 'Error de base de datos: ' . $e->getMessage(),
        'details' => [
            'host' => $host,
            'dbname' => $dbname,
            'username' => $username,
            'error_code' => $e->getCode()
        ]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => 'Error del servidor: ' . $e->getMessage()
    ]);
}
?>