<?php
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../../config/ligacao.php';

redirect_if_not_logged();

exigir_admin_ou_tecnico();

$menu_ativo = 'localizacoes';

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: lista.php');
    exit;
}

try {
    $sql = "
        SELECT 
            l.id,
            l.edificio,
            l.piso,
            l.servico,
            l.sala,
            l.ativo,
            COUNT(e.id) AS total_equipamentos
        FROM localizacoes l
        LEFT JOIN equipamentos e
            ON e.localizacao_id = l.id
        WHERE l.id = :id
        GROUP BY 
            l.id, 
            l.edificio, 
            l.piso, 
            l.servico, 
            l.sala,
            l.ativo
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

$localizacao_ativa = ((int) $localizacao->ativo === 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if ($localizacao_ativa) {
            $novo_estado = 0;
        } else {
            $novo_estado = 1;
        }

        $sql = "
            UPDATE localizacoes
            SET ativo = :ativo
            WHERE id = :id
        ";

        $stmt = $ligacao->prepare($sql);
        $stmt->execute([
            'ativo' => $novo_estado,
            'id' => $id
        ]);

        $descricao_localizacao = $localizacao->edificio . ' | Piso: ' . $localizacao->piso . ' | Serviço: ' . $localizacao->servico . ' | Sala: ' . $localizacao->sala;

        if ((int) $novo_estado === 1) {
            registar_log(
                $ligacao,
                'Reativou localização',
                'localizacoes',
                (int) $id,
                'Localização reativada: ' . $descricao_localizacao
            );
        } else {
            registar_log(
                $ligacao,
                'Inativou localização',
                'localizacoes',
                (int) $id,
                'Localização inativada: ' . $descricao_localizacao
            );
        }

        header('Location: lista.php');
        exit;

    } catch (PDOException $e) {
        die('Erro ao alterar o estado da localização.');
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

                    <div class="<?php echo $localizacao_ativa ? 'text-warning' : 'text-success'; ?> display-4 mb-3">
                        <?php if ($localizacao_ativa) : ?>
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        <?php else : ?>
                            <i class="fa-solid fa-circle-check"></i>
                        <?php endif; ?>
                    </div>

                    <?php if ($localizacao_ativa) : ?>

                        <h3 class="mb-2">Inativar Localização</h3>

                        <p class="text-muted mb-3">
                            Tens a certeza que pretendes inativar esta localização?
                        </p>

                    <?php else : ?>

                        <h3 class="mb-2">Reativar Localização</h3>

                        <p class="text-muted mb-3">
                            Tens a certeza que pretendes reativar esta localização?
                        </p>

                    <?php endif; ?>

                    <div class="bg-light p-3 rounded mb-3">
                        <h5><?php echo htmlspecialchars($localizacao->edificio); ?></h5>

                        <p class="mb-1">
                            <?php echo htmlspecialchars($localizacao->piso); ?>
                            -
                            <?php echo htmlspecialchars($localizacao->servico); ?>
                        </p>

                        <p class="mb-1">
                            <?php echo htmlspecialchars($localizacao->sala); ?>
                        </p>

                        <p class="mb-0">
                            Situação:
                            <?php if ($localizacao_ativa) : ?>
                                <span class="badge bg-success">Ativa</span>
                            <?php else : ?>
                                <span class="badge bg-danger">Inativa</span>
                            <?php endif; ?>
                        </p>
                    </div>

                    <?php if ($localizacao_ativa && $localizacao->total_equipamentos > 0) : ?>
                        <div class="alert alert-danger">
                            <i class="fa-solid fa-circle-exclamation me-1"></i>
                            Existem <strong><?php echo $localizacao->total_equipamentos; ?> equipamentos</strong>
                            associados a esta localização.
                            A localização ficará inativa, mas os registos associados serão preservados.
                        </div>
                    <?php endif; ?>

                    <form method="post" class="d-flex justify-content-center gap-3">

                        <a href="lista.php" class="btn btn-outline-secondary btn-sm px-4">
                            <i class="fa-solid fa-xmark me-1"></i>Cancelar
                        </a>

                        <?php if ($localizacao_ativa) : ?>

                            <button type="submit" class="btn btn-danger btn-sm px-4">
                                <i class="fa-solid fa-ban me-1"></i>Inativar
                            </button>

                        <?php else : ?>

                            <button type="submit" class="btn btn-success btn-sm px-4">
                                <i class="fa-solid fa-rotate-left me-1"></i>Reativar
                            </button>

                        <?php endif; ?>

                    </form>

                </div>
            </div>
        </main>

    </div>
</div>

<script src="<?php echo BASE_URL; ?>/assets/bootstrap/bootstrap.bundle.min.js"></script>

</body>
</html>