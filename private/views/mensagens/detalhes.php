<?php
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../../config/ligacao.php';

redirect_if_not_logged();

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

    $acao = $_POST['acao'] ?? '';

    try {
        if ($acao === 'marcar_lida') {
            $stmt = $ligacao->prepare("
                UPDATE mensagens_contacto
                SET lida = 1
                WHERE id = :id
            ");

            $stmt->execute(['id' => $id]);

            registar_log(
                $ligacao,
                'Marcou mensagem como lida',
                'mensagens_contacto',
                (int) $id,
                'Mensagem de contacto marcada como lida. Remetente: ' . $mensagem->nome . ' | Email: ' . $mensagem->email
            );
        }

        if ($acao === 'marcar_nao_lida') {
            $stmt = $ligacao->prepare("
                UPDATE mensagens_contacto
                SET lida = 0
                WHERE id = :id
            ");

            $stmt->execute(['id' => $id]);

            registar_log(
                $ligacao,
                'Marcou mensagem como lida',
                'mensagens_contacto',
                (int) $id,
                'Mensagem de contacto marcada como lida. Remetente: ' . $mensagem->nome . ' | Email: ' . $mensagem->email
            );
        }

        header('Location: detalhes.php?id=' . $id);
        exit;

    } catch (PDOException $e) {
        die('Erro ao atualizar o estado da mensagem.');
    }
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/navbar.php';
?>

<div class="container-fluid">
    <div class="row">

        <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

        <main class="col-lg-10 p-4">

            <div class="page-header">
                <h2>
                    <i class="fa-solid fa-envelope-open-text me-2"></i>Detalhes da Mensagem
                </h2>

                <a href="lista.php" class="btn btn-cancel btn-sm">
                    <i class="fa-solid fa-arrow-left me-1"></i>Voltar
                </a>
            </div>

            <div class="card-medctrl p-4">

                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h3 class="mb-1">
                            <?php echo htmlspecialchars($mensagem->nome); ?>
                        </h3>

                        <p class="text-muted mb-0">
                            <?php echo htmlspecialchars($mensagem->email); ?>
                        </p>
                    </div>

                    <div>
                        <?php if ((int) $mensagem->lida === 1) : ?>
                            <span class="badge bg-success">Lida</span>
                        <?php else : ?>
                            <span class="badge bg-warning text-dark">Por ler</span>
                        <?php endif; ?>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-12 col-md-6">
                        <p>
                            <strong>Data de envio:</strong>
                            <?php echo htmlspecialchars(formatar_data_mensagem($mensagem->data_envio)); ?>
                        </p>
                    </div>

                    <div class="col-12 col-md-6">
                        <p>
                            <strong>Email:</strong>
                            <a href="mailto:<?php echo htmlspecialchars($mensagem->email); ?>">
                                <?php echo htmlspecialchars($mensagem->email); ?>
                            </a>
                        </p>
                    </div>
                </div>

                <hr>

                <div class="mt-3">
                    <h5>Mensagem</h5>

                    <div class="border rounded p-3 bg-light">
                        <?php echo nl2br(htmlspecialchars($mensagem->mensagem)); ?>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">

                    <a href="mailto:<?php echo htmlspecialchars($mensagem->email); ?>" class="btn btn-save btn-sm">
                        <i class="fa-solid fa-reply me-1"></i>Responder
                    </a>

                    <?php if ((int) $mensagem->lida === 0) : ?>

                        <form method="post" class="d-inline">
                            <input type="hidden" name="acao" value="marcar_lida">

                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="fa-solid fa-check me-1"></i>Marcar como lida
                            </button>
                        </form>

                    <?php else : ?>

                        <form method="post" class="d-inline">
                            <input type="hidden" name="acao" value="marcar_nao_lida">

                            <button type="submit" class="btn btn-warning btn-sm">
                                <i class="fa-solid fa-rotate-left me-1"></i>Marcar como por ler
                            </button>
                        </form>

                    <?php endif; ?>

                    <a href="apagar.php?id=<?php echo $mensagem->id; ?>" class="btn btn-delete btn-sm">
                        <i class="fa-solid fa-trash me-1"></i>Eliminar
                    </a>

                    <a href="lista.php" class="btn btn-cancel btn-sm">Cancelar</a>

                </div>

            </div>

        </main>

    </div>
</div>

<script src="<?php echo BASE_URL; ?>/assets/bootstrap/bootstrap.bundle.min.js"></script>

</body>
</html>