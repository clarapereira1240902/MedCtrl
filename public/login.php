<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../private/includes/funcoes.php';

start_session();

if (!empty($_SESSION['utilizador'])) {
    header('Location: ' . BASE_URL . '/private/index.php');
    exit;
}

$validation_errors = [];
$server_error = '';
$email_antigo = '';

if (!empty($_SESSION['validation_errors'])) {
    $validation_errors = $_SESSION['validation_errors'];
    unset($_SESSION['validation_errors']);
}

if (!empty($_SESSION['server_error'])) {
    $server_error = $_SESSION['server_error'];
    unset($_SESSION['server_error']);
}

if (!empty($_SESSION['email_antigo'])) {
    $email_antigo = $_SESSION['email_antigo'];
    unset($_SESSION['email_antigo']);
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(APP_NAME); ?></title>

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/bootstrap/bootstrap.min.css"> 
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/1240902_public.css"> 

    <link rel="shortcut icon" href="<?php echo BASE_URL; ?>/assets/img/logo.png" type="image/png"> 

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/fontawesome/all.min.css"> 

    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:wght@300;700&display=swap" rel="stylesheet"> 
</head>

<body class="login-body">
    <div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center">
        <div class="row justify-content-center w-100">
            <div class="col-xl-4 col-lg-5 col-md-6 col-sm-8 col-10">

                <div class="card shadow border-0 rounded-4 p-4">

                    <div class="text-center mb-4">
                        <img src="<?php echo BASE_URL; ?>/assets/img/logo.png" width="70" class="mb-3" alt="Logo MedCtrl">

                        <h2 class="fw-bold medctrl-title">
                            <?php echo htmlspecialchars(APP_NAME); ?>
                        </h2>

                        <p class="text-muted mb-0">
                            Gestão Inteligente de Equipamentos Médicos
                        </p>
                    </div>

                    <form action="<?php echo BASE_URL; ?>/private/login_processa.php" method="post">

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>

                            <input 
                                type="email" 
                                class="form-control" 
                                id="email" 
                                name="email" 
                                placeholder="Introduza o seu email" 
                                value="<?php echo htmlspecialchars($email_antigo); ?>"
                                required
                            >
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Palavra-passe</label>

                            <input 
                                type="password" 
                                class="form-control" 
                                id="password" 
                                name="password" 
                                placeholder="Introduza a sua palavra-passe" 
                                required
                            >
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary rounded-pill py-2">
                                Entrar
                                <i class="fa-solid fa-right-to-bracket ms-2"></i>
                            </button>
                        </div>

                        <?php if (!empty($validation_errors)) : ?>
                            <div class="login-error">
                                <i class="fa-solid fa-circle-exclamation me-2"></i>

                                <?php foreach ($validation_errors as $error) : ?>
                                    <div><?php echo htmlspecialchars($error); ?></div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($server_error)) : ?>
                            <div class="login-error">
                                <i class="fa-solid fa-circle-exclamation me-2"></i>
                                <?php echo htmlspecialchars($server_error); ?>
                            </div>
                        <?php endif; ?>

                    </form>

                </div>

            </div>
        </div>
    </div>

    <script src="<?php echo BASE_URL; ?>/assets/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>