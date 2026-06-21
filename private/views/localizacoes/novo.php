<?php
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../../config/ligacao.php';

redirect_if_not_logged();

$menu_ativo = 'localizacoes';

$erro_formulario = '';
$form = $_POST;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $edificio = trim($_POST['edificio'] ?? '');
    $piso = trim($_POST['piso'] ?? '');
    $servico = trim($_POST['servico'] ?? '');
    $sala = trim($_POST['sala'] ?? '');

    if ($edificio === '' || $piso === '' || $servico === '' || $sala === '') {
        $erro_formulario = 'Todos os campos são obrigatórios.';
    }

    if (empty($erro_formulario)) {
        try {
            $stmt_check = $ligacao->prepare("
                SELECT id
                FROM localizacoes
                WHERE LOWER(edificio) = LOWER(:edificio)
                AND LOWER(piso) = LOWER(:piso)
                AND LOWER(servico) = LOWER(:servico)
                AND LOWER(sala) = LOWER(:sala)
                LIMIT 1
            ");

            $stmt_check->execute([
                'edificio' => $edificio,
                'piso' => $piso,
                'servico' => $servico,
                'sala' => $sala
            ]);

            if ($stmt_check->fetch()) {
                $erro_formulario = 'Já existe uma localização com estes dados.';
            }

            if (empty($erro_formulario)) {
                $sql = "
                    INSERT INTO localizacoes (
                        edificio,
                        piso,
                        servico,
                        sala
                    ) VALUES (
                        :edificio,
                        :piso,
                        :servico,
                        :sala
                    )
                ";

                $stmt = $ligacao->prepare($sql);

                $stmt->execute([
                    'edificio' => $edificio,
                    'piso' => $piso,
                    'servico' => $servico,
                    'sala' => $sala
                ]);

                header('Location: lista.php');
                exit;
            }

        } catch (PDOException $e) {
            $erro_formulario = 'Erro ao criar localização. Verifica se os dados preenchidos são válidos.';
        }
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
                    <i class="fa-solid fa-location-dot me-2"></i>Nova Localização
                </h2>
            </div>

            <hr>

            <?php if (!empty($erro_formulario)) : ?>
                <div class="alert alert-danger">
                    <i class="fa-solid fa-circle-exclamation me-1"></i>
                    <?php echo htmlspecialchars($erro_formulario); ?>
                </div>
            <?php endif; ?>

            <form class="form-medctrl" method="post">

                <div class="row">

                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Edifício</label>
                            <input 
                                type="text" 
                                name="edificio" 
                                class="form-control" 
                                value="<?php echo htmlspecialchars($form['edificio'] ?? ''); ?>" 
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Piso</label>
                            <input 
                                type="text" 
                                name="piso" 
                                class="form-control" 
                                value="<?php echo htmlspecialchars($form['piso'] ?? ''); ?>" 
                                required
                            >
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Serviço / Departamento</label>
                            <input 
                                type="text" 
                                name="servico" 
                                class="form-control" 
                                value="<?php echo htmlspecialchars($form['servico'] ?? ''); ?>" 
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Sala / Gabinete</label>
                            <input 
                                type="text" 
                                name="sala" 
                                class="form-control" 
                                value="<?php echo htmlspecialchars($form['sala'] ?? ''); ?>" 
                                required
                            >
                        </div>
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2 mb-4"> 
                    <a href="lista.php" class="btn btn-cancel"> 
                        <i class="fa-solid fa-xmark me-1"></i> Cancelar 
                    </a> 

                    <button type="submit" class="btn btn-save"> 
                        <i class="fa-regular fa-floppy-disk me-1"></i> Guardar 
                    </button> 
                </div>

            </form>

        </main>
    
    </div>
</div>

<script src="<?php echo BASE_URL; ?>/assets/bootstrap/bootstrap.bundle.min.js"></script>

</body>
</html>