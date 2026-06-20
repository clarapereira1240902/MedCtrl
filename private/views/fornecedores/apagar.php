<?php
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../../config/ligacao.php';

redirect_if_not_logged();

$menu_ativo = 'fornecedores';

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: lista.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $sql = "
            UPDATE fornecedores
            SET ativo = 0
            WHERE id = :id
            AND ativo = 1
        ";

        $stmt = $ligacao->prepare($sql);
        $stmt->execute(['id' => $id]);

        header('Location: lista.php');
        exit;

    } catch (PDOException $e) {
        die('Erro ao eliminar fornecedor.');
    }
}

try {
    $sql = "
        SELECT
            f.id,
            f.nome_empresa,
            f.email,
            f.telefone,
            tf.nome AS tipo_fornecedor,
            COUNT(ef.id) AS total_equipamentos
        FROM fornecedores f
        INNER JOIN tipos_fornecedor tf
            ON f.tipo_fornecedor_id = tf.id
        LEFT JOIN equipamento_fornecedor ef
            ON ef.fornecedor_id = f.id
        WHERE f.id = :id
        AND f.ativo = 1
        GROUP BY
            f.id,
            f.nome_empresa,
            f.email,
            f.telefone,
            tf.nome
        LIMIT 1
    ";

    $stmt = $ligacao->prepare($sql);
    $stmt->execute(['id' => $id]);

    $fornecedor = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$fornecedor) {
        header('Location: lista.php');
        exit;
    }

} catch (PDOException $e) {
    die('Erro ao carregar fornecedor.');
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

                    <h3 class="mb-2">Eliminar Fornecedor</h3>

                    <p class="text-muted mb-3">
                        Tens a certeza que pretendes eliminar este fornecedor?
                    </p>

                    <div class="bg-light p-3 rounded mb-3">
                        <h4 class="mb-1">
                            <?php echo htmlspecialchars($fornecedor->nome_empresa); ?>
                        </h4>

                        <span class="badge bg-primary mb-2">
                            <?php echo htmlspecialchars($fornecedor->tipo_fornecedor); ?>
                        </span>

                        <p class="mb-1">
                            <i class="fa-solid fa-at me-1"></i>
                            <?php echo htmlspecialchars($fornecedor->email ?? 'Sem email'); ?>
                        </p>

                        <p class="mb-0">
                            <i class="fa-solid fa-phone me-1"></i>
                            <?php echo htmlspecialchars($fornecedor->telefone ?? 'Sem telefone'); ?>
                        </p>
                    </div>

                    <?php if ($fornecedor->total_equipamentos > 0) : ?>
                        <div class="alert alert-danger">
                            <i class="fa-solid fa-triangle-exclamation me-1"></i>
                            Este fornecedor está associado a
                            <strong><?php echo $fornecedor->total_equipamentos; ?> equipamentos</strong>.
                            O fornecedor será ocultado da listagem, mas os registos associados serão preservados.
                        </div>
                    <?php endif; ?>

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