<?php

require_once __DIR__ . '/../../config/config.php';

function start_session()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function check_session()
{
    return isset($_SESSION['utilizador']);
}

function redirect_if_not_logged($redirect_to = '/public/login.php')
{
    start_session();

    if (!check_session()) {
        header('Location: ' . BASE_URL . $redirect_to);
        exit;
    }
}

function logout_and_redirect($redirect_to = '/public/login.php')
{
    start_session();
    session_unset();
    session_destroy();

    header('Location: ' . BASE_URL . $redirect_to);
    exit;
}

function registar_log($ligacao, $acao, $tabela_afetada = null, $registo_id = null, $detalhes = null) {
    try {
        date_default_timezone_set('Europe/Lisbon');

        $pasta_logs = __DIR__ . '/../logs';

        if (!is_dir($pasta_logs)) {
            mkdir($pasta_logs, 0777, true);
        }

        $ficheiro_log = $pasta_logs . '/sistema.log';

        $utilizador_id = $_SESSION['utilizador']['id'] ?? 'sem sessão';
        $utilizador_nome = $_SESSION['utilizador']['nome'] ?? 'sem utilizador';

        $data = date('Y-m-d H:i:s');

        $linha = '[' . $data . '] ';
        $linha .= 'Utilizador ID: ' . $utilizador_id . ' | ';
        $linha .= 'Nome: ' . $utilizador_nome . ' | ';
        $linha .= 'Ação: ' . $acao . ' | ';
        $linha .= 'Tabela: ' . ($tabela_afetada ?? '-') . ' | ';
        $linha .= 'Registo ID: ' . ($registo_id ?? '-') . ' | ';
        $linha .= 'Detalhes: ' . ($detalhes ?? '-') . PHP_EOL;

        file_put_contents($ficheiro_log, $linha, FILE_APPEND);

    } catch (Exception $e) {
        // Se o log falhar, o sistema continua normalmente.
    }
}