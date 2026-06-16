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

                <div class="page-header">
                    <h2>
                        <i class="fa-solid fa-pen-to-square me-2"></i> Editar Localização
                    </h2>

                    <a href="lista.html" class="btn btn-cancel btn-sm">
                        <i class="fa-solid fa-arrow-left me-1"></i> Voltar
                    </a>
                </div>

                <form class="form-medctrl">

                    <div class="row">

                        <!-- Coluna esquerda -->
                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Edifício</label>
                                <input type="text" class="form-control" value="Hospital Central">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Piso</label>
                                <input type="text" class="form-control" value="2º Piso">
                            </div>
                        </div>

                        <!-- Coluna direita -->
                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Serviço / Departamento</label>
                                <input type="text" class="form-control" value="Cardiologia">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Sala / Gabinete</label>
                                <input type="text" class="form-control" value="Sala 12">
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
<script src="../../../assets/bootstrap/bootstrap.bundle.min.js"></script> 

</body>
</html>