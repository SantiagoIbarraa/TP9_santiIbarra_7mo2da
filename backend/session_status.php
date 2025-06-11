<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

try {
    $response = [
        'loggedIn' => false,
        'role' => null,
        'username' => null,
        'userId' => null
    ];
    
    // Verificar si hay una sesión activa
    if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
        $response['loggedIn'] = true;
        $response['role'] = $_SESSION['role'];
        $response['username'] = $_SESSION['username'] ?? $_SESSION['email'] ?? 'Usuario';
        $response['userId'] = $_SESSION['user_id'];
    }
    
    echo json_encode($response);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => 'Error del servidor: ' . $e->getMessage(),
        'loggedIn' => false
    ]);
}
?>