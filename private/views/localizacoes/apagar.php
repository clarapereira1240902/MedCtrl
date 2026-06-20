<?php
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../../config/ligacao.php';

redirect_if_not_logged();

$menu_ativo = 'localizacoes';

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: lista.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $sql = "
            UPDATE localizacoes
            SET ativo = 0
            WHERE id = :id
            AND ativo = 1
        ";

        $stmt = $ligacao->prepare($sql);
        $stmt->execute(['id' => $id]);

        header('Location: lista.php');
        exit;

    } catch (PDOException $e) {
        die('Erro ao eliminar localização.');
    }
}

try {
    $sql = "
        SELECT 
            l.id,
            l.edificio,
            l.piso,
            l.servico,
            l.sala,
            COUNT(e.id) AS total_equipamentos
        FROM localizacoes l
        LEFT JOIN equipamentos e
            ON e.localizacao_id = l.id
            AND e.ativo = 1
        WHERE l.id = :id
        AND l.ativo = 1
        GROUP BY l.id, l.edificio, l.piso, l.servico, l.sala
        LIMIT 1
    ";

    $stmt = $ligacao->prepare($sql);
    $stmt->execute(['id' => $id]);

    $localizacao = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$localizacao) {
        header('Location: lista.php');
        exit;
    }

} catch (PDOException $e) {
    die('Erro ao carregar localização.');
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

                    <h3 class="mb-2">Eliminar Localização</h3>

                    <p class="text-muted mb-3">
                        Tens a certeza que pretendes eliminar esta localização?
                    </p>

                    <div class="bg-light p-3 rounded mb-3">
                        <h5><?php echo htmlspecialchars($localizacao->edificio); ?></h5>

                        <p class="mb-1">
                            <?php echo htmlspecialchars($localizacao->piso); ?>
                            -
                            <?php echo htmlspecialchars($localizacao->servico); ?>
                        </p>

                        <p class="mb-0">
                            <?php echo htmlspecialchars($localizacao->sala); ?>
                        </p>
                    </div>

                    <?php if ($localizacao->total_equipamentos > 0) : ?>
                        <div class="alert alert-danger">
                            <i class="fa-solid fa-circle-exclamation me-1"></i>
                            Existem <strong><?php echo $localizacao->total_equipamentos; ?> equipamentos</strong>
                            associados a esta localização.
                            A localização será ocultada da listagem, mas os registos associados serão preservados.
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