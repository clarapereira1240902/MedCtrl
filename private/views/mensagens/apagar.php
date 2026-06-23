<?php
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../../config/ligacao.php';

redirect_if_not_logged();

exigir_admin();

$menu_ativo = 'mensagens';

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: lista.php');
    exit;
}

function formatar_data_mensagem($data) {
    if (empty($data)) {
        return '—';
    }

    $timestamp = strtotime($data);

    if (!$timestamp) {
        return '—';
    }

    return date('d/m/Y H:i', $timestamp);
}

try {
    $sql = "
        SELECT
            id,
            nome,
            email,
            mensagem,
            data_envio,
            lida
        FROM mensagens_contacto
        WHERE id = :id
        LIMIT 1
    ";

    $stmt = $ligacao->prepare($sql);
    $stmt->execute(['id' => $id]);

    $mensagem = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$mensagem) {
        header('Location: lista.php');
        exit;
    }

} catch (PDOException $e) {
    die('Erro ao carregar mensagem.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $ligacao->prepare("
            DELETE FROM mensagens_contacto
            WHERE id = :id
        ");

        $stmt->execute(['id' => $id]);

        registar_log(
            $ligacao,
            'Eliminou mensagem de contacto',
            'mensagens_contacto',
            (int) $id,
            'Mensagem eliminada. Remetente: ' . $mensagem->nome . ' | Email: ' . $mensagem->email
        );

        $_SESSION['mensagem_sucesso'] = 'Mensagem eliminada com sucesso.';

        header('Location: lista.php');
        exit;

    } catch (PDOException $e) {
        die('Erro ao eliminar mensagem.');
    }
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/navbar.php';
?>

<div class="container-fluid">
    <div class="row">

        <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

        <main class="col-lg-10 p-4">
            <div class="d-flex justify-content-center mt-5">
                <div class="card shadow rounded text-center p-4" style="max-width:650px; width:100%;">

                    <div class="text-warning display-4 mb-3">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>

                    <h3 class="mb-2">Eliminar Mensagem</h3>

                    <p class="text-muted mb-3">
                        Tens a certeza que pretendes eliminar esta mensagem?
                    </p>

                    <div class="bg-light p-3 rounded mb-3 text-start">
                        <h5 class="mb-1">
                            <?php echo htmlspecialchars($mensagem->nome); ?>
                        </h5>

                        <p class="mb-1">
                            <i class="fa-solid fa-envelope me-1"></i>
                            <?php echo htmlspecialchars($mensagem->email); ?>
                        </p>

                        <p class="mb-1">
                            <i class="fa-solid fa-calendar-days me-1"></i>
                            <?php echo htmlspecialchars(formatar_data_mensagem($mensagem->data_envio)); ?>
                        </p>

                        <p class="mb-0">
                            <?php echo htmlspecialchars(substr($mensagem->mensagem, 0, 160)); ?>
                            <?php echo strlen($mensagem->mensagem) > 160 ? '...' : ''; ?>
                        </p>
                    </div>

                    <div class="alert alert-danger">
                        <i class="fa-solid fa-triangle-exclamation me-1"></i>
                        Esta ação elimina definitivamente a mensagem da base de dados.
                    </div>

                    <form method="post" class="d-flex justify-content-center gap-3">

                        <a href="lista.php" class="btn btn-outline-secondary btn-sm px-4">
                            <i class="fa-solid fa-xmark me-1"></i>Cancelar
                        </a>

                        <button type="submit" class="btn btn-danger btn-sm px-4">
                            <i class="fa-solid fa-trash me-1"></i>Eliminar
                        </button>

                    </form>

                </div>
            </div>
        </main>

    </div>
</div>

<script src="<?php echo BASE_URL; ?>/assets/bootstrap/bootstrap.bundle.min.js"></script>

</body>
</html>