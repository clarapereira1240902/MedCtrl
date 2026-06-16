<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

$menu_ativo = 'equipamentos';

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
                            <h4>Monitor de Sinais Vitais</h4>
                            <p>Código: EQUIP-2024-001</p>
                            <p>Marca: Philips</p>
                        </div>

                        <!-- Botões -->
                        <div class="d-flex justify-content-center gap-3 mt-4">
                            <a href="lista.php" class="btn btn-cancel btn-sm px-4">
                                <i class="fa-solid fa-xmark me-1"></i>
                                Não
                            </a>

                            <a href="#" class="btn btn-delete btn-sm px-4">
                                <i class="fa-solid fa-trash me-1"></i>
                                Sim, eliminar
                            </a>
                        </div>

                    </div>
                </div>  
            </main>
        
        </div>
    </div>

    
<!-- Bootstrap JS and custom JS --> 
<script src="../../../assets/bootstrap/bootstrap.bundle.min.js"></script> 

</body>
</html>