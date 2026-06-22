<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/ligacao.php';
require_once __DIR__ . '/includes/funcoes.php';

start_session();

if (!empty($_SESSION['utilizador'])) {
    registar_log($ligacao, 'Logout efetuado', 'utilizadores', (int) $_SESSION['utilizador']['id'], 'Utilizador terminou sessão.');
}

logout_and_redirect();