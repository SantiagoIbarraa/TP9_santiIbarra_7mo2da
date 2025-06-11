<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Comparación directa sin hash (texto plano)
    if ($user && $password === $user['password']) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['email'] = $user['email'];
        
        // Redirigir según el rol
        if ($user['role'] === 'admin') {
            header('Location: http://localhost/TP9_2/frontend/admin/movies.php');
        } else {
            header('Location: ../frontend/index.php');
        }
        exit();
    } else {
        header('Location: ../frontend/login.html?error=1');
        exit();
    }
}
?>