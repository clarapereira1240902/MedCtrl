<?php
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../../config/ligacao.php';

redirect_if_not_logged();

exigir_admin();

$menu_ativo = 'mensagens';

$pesquisa = trim($_GET['pesquisa'] ?? '');
$lida = $_GET['lida'] ?? '';

$mensagem_sucesso = $_SESSION['mensagem_sucesso'] ?? '';
unset($_SESSION['mensagem_sucesso']);

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

function resumo_mensagem($texto, $limite = 90) {
    $texto = trim($texto ?? '');

    if (strlen($texto) > $limite) {
        return substr($texto, 0, $limite) . '...';
    }

    return $texto;
}

try {
    $total_nao_lidas = $ligacao->query("
        SELECT COUNT(*)
        FROM mensagens_contacto
        WHERE lida = 0
    ")->fetchColumn();

    $sql = "
        SELECT
            id,
            nome,
            email,
            mensagem,
            data_envio,
            lida
        FROM mensagens_contacto
        WHERE LOWER(CONCAT(
            nome, ' ',
            email, ' ',
            mensagem
        )) LIKE LOWER(:pesquisa)
        AND (:lida = '' OR lida = :lida)
        ORDER BY lida ASC, data_envio DESC, id DESC
    ";

    $stmt = $ligacao->prepare($sql);
    $stmt->execute([
        'pesquisa' => '%' . $pesquisa . '%',
        'lida' => $lida
    ]);

    $mensagens = $stmt->fetchAll(PDO::FETCH_OBJ);
    $erro = '';

} catch (PDOException $e) {
    $erro = 'Aconteceu um erro ao carregar as mensagens.';
    $mensagens = [];
    $total_nao_lidas = 0;
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
                    <i class="fa-solid fa-envelope me-2"></i>Mensagens de Contacto
                </h2>
            </div>

            <hr>

            <?php if (!empty($mensagem_sucesso)) : ?>
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check me-1"></i>
                    <?php echo htmlspecialchars($mensagem_sucesso); ?>
                </div>
            <?php endif; ?>

            <div class="row g-3 mb-4">
                <div class="col-12 col-md-4">
                    <div class="dashboard-mini-card">
                        <span>Mensagens por ler</span>
                        <strong><?php echo (int) $total_nao_lidas; ?></strong>
                    </div>
                </div>
            </div>

            <div class="search-card mb-4">
                <h5>
                    <i class="fa-solid fa-magnifying-glass me-2"></i>Pesquisa
                </h5>

                <form method="get">
                    <div class="search-row">
                        <input
                            type="text"
                            name="pesquisa"
                            class="form-control search-input"
                            placeholder="Nome, email ou mensagem"
                            value="<?php echo htmlspecialchars($pesquisa); ?>"
                        >

                        <button class="btn btn-save search-btn" type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>

                        <button class="btn btn-outline-secondary search-btn" type="button" data-bs-toggle="collapse" data-bs-target="#filtrosMensagens">
                            <i class="fa-solid fa-sliders"></i>
                        </button>
                    </div>

                    <div class="collapse mt-3" id="filtrosMensagens">
                        <div class="row g-3 pt-3 border-top">

                            <div class="col-12 col-md-4">
                                <label class="form-label">Estado</label>

                                <select name="lida" class="form-select">
                                    <option value="" <?php echo $lida === '' ? 'selected' : ''; ?>>Todas</option>
                                    <option value="0" <?php echo $lida === '0' ? 'selected' : ''; ?>>Por ler</option>
                                    <option value="1" <?php echo $lida === '1' ? 'selected' : ''; ?>>Lidas</option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-3">
                                <a href="lista.php" class="btn btn-cancel btn-sm">
                                    <i class="fa-solid fa-rotate-left me-1"></i>Limpar
                                </a>

                                <button type="submit" class="btn btn-save btn-sm">
                                    <i class="fa-solid fa-filter me-1"></i>Aplicar filtros
                                </button>
                            </div>

                        </div>
                    </div>
                </form>
            </div>

            <?php if (!empty($erro)) : ?>

                <p class="text-center text-danger">
                    <?php echo htmlspecialchars($erro); ?>
                </p>

            <?php elseif (count($mensagens) === 0) : ?>

                <p class="text-muted">
                    Não existem mensagens registadas.
                </p>

            <?php else : ?>

                <p class="text-muted">
                    Existem <?php echo count($mensagens); ?> mensagens registadas.
                </p>

                <div class="table-responsive">
                    <table class="table table-hover align-middle table-medctrl">

                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Mensagem</th>
                                <th>Data</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center" style="width: 130px;">Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($mensagens as $mensagem) : ?>

                                <tr class="<?php echo ((int) $mensagem->lida === 0) ? 'table-warning' : ''; ?>">
                                    <td>
                                        <strong>
                                            <?php echo htmlspecialchars($mensagem->nome); ?>
                                        </strong>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($mensagem->email); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars(resumo_mensagem($mensagem->mensagem)); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars(formatar_data_mensagem($mensagem->data_envio)); ?>
                                    </td>

                                    <td class="text-center">
                                        <?php if ((int) $mensagem->lida === 1) : ?>
                                            <span class="badge bg-success">Lida</span>
                                        <?php else : ?>
                                            <span class="badge bg-warning text-dark">Por ler</span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center text-nowrap">
                                        <div class="d-inline-flex gap-1 flex-nowrap">

                                            <a href="detalhes.php?id=<?php echo $mensagem->id; ?>" class="btn btn-sm btn-view-list" title="Ver mensagem">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>

                                            <a href="mailto:<?php echo htmlspecialchars($mensagem->email); ?>" class="btn btn-sm btn-edit-list" title="Responder por email">
                                                <i class="fa-solid fa-reply"></i>
                                            </a>

                                            <a href="apagar.php?id=<?php echo $mensagem->id; ?>" class="btn btn-sm btn-delete-list" title="Eliminar mensagem">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>

                                        </div>
                                    </td>
                                </tr>

                            <?php endforeach; ?>
                        </tbody>

                    </table>
                </div>

            <?php endif; ?>

        </main>

    </div>
</div>

<script src="<?php echo BASE_URL; ?>/assets/bootstrap/bootstrap.bundle.min.js"></script>

</body>
</html>