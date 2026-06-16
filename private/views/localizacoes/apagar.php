<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

$menu_ativo = 'localizacoes';

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/navbar.php';
?>


    <div class="container-fluid">
        <div class="row">

            <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

            <!-- Conteúdo Principal -->
            <main class="col-lg-10 p-4">
                <div class="d-flex justify-content-center mt-5">
                    <div class="card shadow rounded text-center p-4" style="max-width:650px; width:100%;">

                        <!-- Ícone -->
                        <div class="text-warning display-4 mb-3">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
    
                        <!-- Texto de alerta -->
                        <h3 class="mb-2">Eliminar Localização</h3>
                        <p class="text-muted mb-3">Tens a certeza que pretendes eliminar esta localização?</p>

                        <!-- Informação do fornecedor -->
                        <div class="bg-light p-3 rounded mb-3">
                            <h5>Hospital Central</h5>
                            <p class="mb-1">2º Piso - Cardiologia</p>
                            <p class="mb-0">Sala 12</p>
                        </div>

                        <!-- ALERTA IMPORTANTE -->
                        <div class="alert alert-danger">
                            <i class="fa-solid fa-circle-exclamation me-1"></i>
                            Existem <strong>5 equipamentos</strong> associados a esta localização.
                        </div>

                        <!-- Botões -->
                        <div class="d-flex justify-content-center gap-3">
                            <a href="lista.php" class="btn btn-outline-secondary btn-sm px-4">
                                <i class="fa-solid fa-xmark me-1"></i>Cancelar
                            </a>

                            <a href="#" class="btn btn-danger btn-sm px-4">
                                <i class="fa-solid fa-trash me-1"></i>Eliminar
                            </a>
                        </div>

                    </div>
                </div>  
            </main>
        
        </div>
    </div>

    
<!-- Bootstrap JS and custom JS --> 
<script src="<?php echo BASE_URL; ?>/assets/bootstrap/bootstrap.bundle.min.js"></script> 

</body>
</html>