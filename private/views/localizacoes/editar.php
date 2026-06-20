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
        $sql_update = "
            UPDATE localizacoes
            SET
                edificio = :edificio,
                piso = :piso,
                servico = :servico,
                sala = :sala
            WHERE id = :id
        ";

        $stmt_update = $ligacao->prepare($sql_update);

        $stmt_update->execute([
            'edificio' => trim($_POST['edificio']),
            'piso' => trim($_POST['piso']),
            'servico' => trim($_POST['servico']),
            'sala' => trim($_POST['sala']),
            'id' => $id
        ]);

        header('Location: lista.php');
        exit;

    } catch (PDOException $e) {
        die('Erro ao atualizar localização.');
    }
}

try {
    $sql = "
        SELECT id, edificio, piso, servico, sala
        FROM localizacoes
        WHERE id = :id
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

            <!-- Conteúdo Principal -->
            <main class="col-lg-10 p-4">

                <div class="page-header">
                    <h2>
                        <i class="fa-solid fa-pen-to-square me-2"></i> Editar Localização
                    </h2>

                    <a href="lista.php" class="btn btn-cancel btn-sm">
                        <i class="fa-solid fa-arrow-left me-1"></i> Voltar
                    </a>
                </div>

                <form class="form-medctrl" method="post">

                    <div class="row">

                        <!-- Coluna esquerda -->
                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Edifício</label>
                                <input type="text" name="edificio" class="form-control" value="<?php echo htmlspecialchars($localizacao->edificio); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Piso</label>
                                <input type="text" name="piso" class="form-control" value="<?php echo htmlspecialchars($localizacao->piso); ?>" required>
                            </div>
                        </div>

                        <!-- Coluna direita -->
                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Serviço / Departamento</label>
                                <input type="text" name="servico" class="form-control" value="<?php echo htmlspecialchars($localizacao->servico); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Sala / Gabinete</label>
                                <input type="text" name="sala" class="form-control" value="<?php echo htmlspecialchars($localizacao->sala); ?>" required>
                            </div>
                        </div>

                    </div>

                    <!-- Botões -->
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="lista.php" class="btn btn-cancel btn-sm">Cancelar</a>

                        <button type="submit" class="btn btn-save btn-sm">
                            <i class="fa-regular fa-floppy-disk me-1"></i>Guardar alterações
                        </button>
                    </div>

                </form>

            </main>
        
        </div>
    </div>

    
<!-- Bootstrap JS and custom JS --> 
<script src="<?php echo BASE_URL; ?>/assets/bootstrap/bootstrap.bundle.min.js"></script>

</body>
</html>