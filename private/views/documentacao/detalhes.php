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
                <div class="page-header">
                    <h2><i class="fa-solid fa-file-medical me-2"></i>Detalhes do Documento</h2>
                    <a href="lista.html" class="btn btn-cancel btn-sm">
                        <i class="fa-solid fa-arrow-left me-1"></i>Voltar
                    </a>
                </div>

                <!-- Cartão principal -->
                <div class="card-medctrl p-4">

                    <div class="row">

                        <div class="col-12 col-md-6">
                            <p><strong>Tipo de Documento:</strong> Manual Técnico</p>
                            <p><strong>Nome:</strong> Manual Monitor Philips MX450</p>
                            <p><strong>Data do Documento:</strong> 2024-03-10</p>
                            <p><strong>Validade:</strong> 2027-03-10</p>
                        </div>

                        <div class="col-12 col-md-6">
                            <p><strong>Equipamento:</strong> INV-001 | Monitor Multiparamétrico</p>
                            <a href="../equipamentos/detalhes.php" class="btn btn-save btn-sm mb-3">
                                <i class="fa-solid fa-eye me-1"></i>Ver equipamento
                            </a>
                            <p><strong>Fornecedor:</strong> Philips Healthcare</p>
                            <p><strong>Localização ficheiro:</strong> docs/manuals/mx450.pdf</p>
                        </div>

                    </div>

                    <hr>

                    <div class="mt-3">
                        <strong>Observações:</strong>
                        <p>Documento oficial do fabricante com instruções de utilização e manutenção.</p>
                    </div>

                    <hr>

                    <div class="mt-4">

                        <h5 class="mb-3">
                            <i class="fa-solid fa-file-lines me-2"></i>Ver Documento
                        </h5>

                        <div class="border rounded p-3 bg-light">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                                <div>
                                    <strong>manual_monitor_philips_mx450.pdf</strong>
                                    <br>
                                    <small class="text-muted">
                                        Manual Técnico - PDF
                                    </small>
                                </div>

                                <a href="docs/manuals/mx450.pdf" target="_blank" class="btn btn-save btn-sm">
                                    <i class="fa-solid fa-download me-1"></i>Abrir
                                </a>
                            </div>
                        </div>
                        
                    </div>

                    <!-- BOTÕES -->
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="editar.php" class="btn btn-edit btn-sm"><i class="fa-solid fa-pen me-1"></i> Editar </a>
                        <a href="lista.php" class="btn btn-cancel btn-sm"> Cancelar</a>
                    </div>

                </div>
            </main>
        
        </div>
    </div>

    
<!-- Bootstrap JS and custom JS --> 
<script src="../../../assets/bootstrap/bootstrap.bundle.min.js"></script> 

</body>
</html>