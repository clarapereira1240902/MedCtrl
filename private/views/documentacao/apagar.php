<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

$menu_ativo = 'documentacao';

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
                        <h3 class="mb-2">Eliminar Documento</h3>
                        <p class="text-muted mb-3">
                            Tens a certeza que pretendes eliminar este documento?
                        </p>

                        <!-- Informação do documento -->
                        <div class="bg-light p-3 rounded mb-3">
                            <h4 class="mb-2">Manual Monitor Philips MX450</h4>
                            <span class="badge bg-primary mb-2">Manual Técnico</span>
                            <p class="mb-1">
                                <i class="fa-solid fa-laptop-medical me-1"></i>
                                INV-001 | Monitor Multiparamétrico
                            </p>
                            <p class="mb-1">
                                <i class="fa-solid fa-handshake me-1"></i>
                                Philips Healthcare
                            </p>
                            <p class="mb-0">
                                <i class="fa-solid fa-calendar-days me-1"></i>
                                Validade: 2028-01-01
                            </p>

                        </div>

                        <!-- ALERTA -->
                        <div class="alert alert-danger">
                            <i class="fa-solid fa-triangle-exclamation me-1"></i>
                            Este documento encontra-se associado a um equipamento.
                            Após a eliminação deixará de estar disponível para consulta.
                        </div>

                        <!-- Botões -->
                        <div class="d-flex justify-content-center gap-3">
                            <a href="lista.php" class="btn btn-outline-secondary btn-sm px-4">
                                <i class="fa-solid fa-xmark me-1"></i>Cancelar
                            </a>

                            <a href="#"
                            class="btn btn-danger btn-sm px-4">
                                <i class="fa-solid fa-trash me-1"></i>Eliminar
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