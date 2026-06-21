<?php
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../../config/ligacao.php';

redirect_if_not_logged();

$menu_ativo = 'equipamentos';

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: lista.php');
    exit;
}

try {
    $sql = "
        SELECT 
            id, 
            codigo_inventario, 
            designacao, 
            marca, 
            modelo,
            ativo
        FROM equipamentos
        WHERE id = :id
        LIMIT 1
    ";

    $stmt = $ligacao->prepare($sql);
    $stmt->execute(['id' => $id]);

    $equipamento = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$equipamento) {
        header('Location: lista.php');
        exit;
    }

} catch (PDOException $e) {
    die('Erro ao carregar equipamento.');
}

$equipamento_ativo = ((int) $equipamento->ativo === 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if ($equipamento_ativo) {
            $novo_estado = 0;
        } else {
            $novo_estado = 1;
        }

        $sql = "
            UPDATE equipamentos
            SET ativo = :ativo
            WHERE id = :id
        ";

        $stmt = $ligacao->prepare($sql);
        $stmt->execute([
            'ativo' => $novo_estado,
            'id' => $id
        ]);

        header('Location: lista.php');
        exit;

    } catch (PDOException $e) {
        die('Erro ao alterar o estado do equipamento.');
    }
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/navbar.php';
?>


<div class="container-fluid">
    <div class="row">

        <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

        <!-- Conteúdo Principal -->
        <main class="col-lg-10 p-4">
            <div class="delete-wrapper">
                <div class="delete-card">
                    
                    <div class="delete-icon">
                        <?php if ($equipamento_ativo) : ?>
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        <?php else : ?>
                            <i class="fa-solid fa-circle-check"></i>
                        <?php endif; ?>
                    </div>

                    <?php if ($equipamento_ativo) : ?>

                        <h2>Inativar Equipamento</h2>

                        <p class="delete-warning">
                            Tens a certeza que pretendes inativar este equipamento?
                        </p>

                    <?php else : ?>

                        <h2>Reativar Equipamento</h2>

                        <p class="delete-warning">
                            Tens a certeza que pretendes reativar este equipamento?
                        </p>

                    <?php endif; ?>

                    <!-- Informação do equipamento -->
                    <div class="delete-item">
                        <h4><?php echo htmlspecialchars($equipamento->designacao); ?></h4>
                        <p>Código: <?php echo htmlspecialchars($equipamento->codigo_inventario); ?></p>
                        <p>Marca: <?php echo htmlspecialchars($equipamento->marca); ?></p>
                        <p>Modelo: <?php echo htmlspecialchars($equipamento->modelo); ?></p>

                        <p>
                            Estado no sistema:
                            <?php if ($equipamento_ativo) : ?>
                                <span class="badge bg-success">Ativo</span>
                            <?php else : ?>
                                <span class="badge bg-danger">Inativo</span>
                            <?php endif; ?>
                        </p>
                    </div>

                    <!-- Botões -->
                    <form method="post" class="d-flex justify-content-center gap-3 mt-4">
                        <a href="lista.php" class="btn btn-cancel btn-sm px-4">
                            <i class="fa-solid fa-xmark me-1"></i>Não
                        </a>

                        <?php if ($equipamento_ativo) : ?>

                            <button type="submit" class="btn btn-delete btn-sm px-4">
                                <i class="fa-solid fa-ban me-1"></i>Sim, inativar
                            </button>

                        <?php else : ?>

                            <button type="submit" class="btn btn-success btn-sm px-4">
                                <i class="fa-solid fa-rotate-left me-1"></i>Sim, reativar
                            </button>

                        <?php endif; ?>
                    </form>

                </div>
            </div>  
        </main>
    
    </div>
</div>


<!-- Bootstrap JS and custom JS --> 
<script src="<?php echo BASE_URL; ?>/assets/bootstrap/bootstrap.bundle.min.js"></script>

</body>
</html>