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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $sql = "
            UPDATE equipamentos
            SET ativo = 0
            WHERE id = :id
            AND ativo = 1
        ";

        $stmt = $ligacao->prepare($sql);
        $stmt->execute(['id' => $id]);

        header('Location: lista.php');
        exit;

    } catch (PDOException $e) {
        die('Erro ao eliminar equipamento.');
    }
}

try {
    $sql = "
        SELECT id, codigo_inventario, designacao, marca, modelo
        FROM equipamentos
        WHERE id = :id
        AND ativo = 1
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
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>

                        <!-- Texto de alerta -->
                        <h2>Eliminar Equipamento</h2>
                        <p class="delete-warning">Tens a certeza que pretendes eliminar este equipamento?</p>

                        <!-- Informação do equipamento -->
                        <div class="delete-item">
                            <h4><?php echo htmlspecialchars($equipamento->designacao); ?></h4>
                            <p>Código: <?php echo htmlspecialchars($equipamento->codigo_inventario); ?></p>
                            <p>Marca: <?php echo htmlspecialchars($equipamento->marca); ?></p>
                        </div>

                        <!-- Botões -->
                        <form method="post" class="d-flex justify-content-center gap-3 mt-4">
                            <a href="lista.php" class="btn btn-cancel btn-sm px-4">
                                <i class="fa-solid fa-xmark me-1"></i>Não
                            </a>

                            <button type="submit" class="btn btn-delete btn-sm px-4">
                                <i class="fa-solid fa-trash me-1"></i>Sim, eliminar
                            </button>
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