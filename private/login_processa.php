<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/ligacao.php';
require_once __DIR__ . '/includes/funcoes.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../public/login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($email) || empty($password)) {
    header('Location: ../public/login.php');
    exit;
}

$sql = "SELECT * FROM utilizadores WHERE email = :email LIMIT 1";

$stmt = $ligacao->prepare($sql);
$stmt->bindParam(':email', $email);
$stmt->execute();

$utilizador = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$utilizador) {
    header('Location: ../public/login.php');
    exit;
}

if (!password_verify($password, $utilizador['password_hash'])) {
    header('Location: ../public/login.php');
    exit;
}

start_session();

$_SESSION['utilizador'] = [
    'id' => $utilizador['id'],
    'nome' => $utilizador['nome'],
    'email' => $utilizador['email']
];

header('Location: index.php');
exit;