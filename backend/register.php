<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password']; // Sin hash, texto plano
    $role = $_POST['role'] === 'admin' ? 'admin' : 'user'; // Asegurar que el rol sea válido

    try {
        $stmt = $pdo->prepare('INSERT INTO users (email, password, role) VALUES (?, ?, ?)');
        if ($stmt->execute([$email, $password, $role])) {
            header('Location: ../frontend/login.html?registered=1');
            exit();
        } else {
            echo 'Error al registrar usuario';
        }
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Duplicate entry
            echo 'Este email ya está registrado';
        } else {
            echo 'Error al registrar usuario: ' . $e->getMessage();
        }
    }
}
?>