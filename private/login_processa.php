<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/ligacao.php';
require_once __DIR__ . '/includes/funcoes.php';

start_session();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/public/login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

$validation_errors = [];

if (empty($email)) {
    $validation_errors[] = 'O email é obrigatório.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $validation_errors[] = 'O email deve ter um formato válido.';
}

if (empty($password)) {
    $validation_errors[] = 'A palavra-passe é obrigatória.';
}

if (!empty($validation_errors)) {
    $_SESSION['validation_errors'] = $validation_errors;
    $_SESSION['email_antigo'] = $email;

    header('Location: ' . BASE_URL . '/public/login.php');
    exit;
}

try {

    $sql = "
        SELECT *
        FROM utilizadores
        WHERE email = :email
        AND ativo = 1
        LIMIT 1
    ";

    $stmt = $ligacao->prepare($sql);
    $stmt->execute([
        'email' => $email
    ]);

    $utilizador = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$utilizador || !password_verify($password, $utilizador['password_hash'])) {
        $_SESSION['validation_errors'] = ['Email ou palavra-passe inválidos.'];
        $_SESSION['email_antigo'] = $email;

        header('Location: ' . BASE_URL . '/public/login.php');
        exit;
    }

    session_regenerate_id(true);

    $_SESSION['utilizador'] = [
        'id' => $utilizador['id'],
        'perfil_id' => $utilizador['perfil_id'],
        'nome' => $utilizador['nome'],
        'email' => $utilizador['email']
    ];

    header('Location: ' . BASE_URL . '/private/index.php');
    exit;

} catch (PDOException $e) {

    $_SESSION['server_error'] = 'Erro ao validar o utilizador. Tente novamente mais tarde.';
    $_SESSION['email_antigo'] = $email;

    header('Location: ' . BASE_URL . '/public/login.php');
    exit;
}