<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

$menu_ativo = 'fornecedores';

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/navbar.php';
?>


    <div class="container-fluid">
        <div class="row">

            <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

            <!-- Conteúdo Principal -->
            <main class="col-lg-10 p-4">
                <div class="page-header">
                    <h2><i class="fa-solid fa-handshake me-2"></i>Detalhes do Fornecedor</h2>
                    <a href="lista.html" class="btn btn-cancel btn-sm">
                        <i class="fa-solid fa-arrow-left me-1"></i>Voltar
                    </a>
                </div>

                <!-- Cartão principal -->
                <div class="form-medctrl">

                    <!-- Identificação -->
                    <div class="mb-4">
                        <h3 class="mb-1">Dräger Portugal</h3>
                        <span class="badge bg-primary">Fabricante</span>
                    </div>

                    <hr>

                    <!-- Informação geral -->
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <p><strong>NIF:</strong> 507123456</p>
                            <p><strong>Telefone:</strong> 229876543</p>
                            <p><strong>Email:</strong> geral@drager.pt</p>
                            <p><strong>Website:</strong> www.drager.pt</p>
                        </div>

                        <div class="col-12 col-md-6">
                            <p><strong>Morada:</strong> Rua da Saúde, Porto</p>
                            <p><strong>Pessoa de contacto:</strong> João Costa</p>
                            <p><strong>Telefone contacto:</strong> 912345678</p>
                        </div>
                    </div>

                    <hr>
                    
                    <!-- Equipamentos Associados -->
                    <div class="mt-3">
                        <h5 class="mb-3">
                            <i class="fa-solid fa-laptop-medical me-2"></i>Equipamentos Associados
                        </h5>

                        <div class="documento-card mb-2 d-flex justify-content-between align-items-center">
                            <span>Monitor de Sinais Vitais MX450</span>

                            <a href="../equipamentos/detalhes.php" class="btn btn-save btn-sm">
                                <i class="fa-solid fa-eye me-1"></i>Ver
                            </a>
                        </div>

                        <div class="documento-card mb-2 d-flex justify-content-between align-items-center">
                            <span>Ventilador Pulmonar V500</span>

                            <a href="../equipamentos/detalhes.php" class="btn btn-save btn-sm">
                                <i class="fa-solid fa-eye me-1"></i>Ver
                            </a>
                        </div>

                        <div class="documento-card d-flex justify-content-between align-items-center">
                            <span>ECG Philips TC70</span>

                            <a href="../equipamentos/detalhes.php" class="btn btn-save btn-sm">
                                <i class="fa-solid fa-eye me-1"></i>Ver
                            </a>
                        </div>
                    </div>

                    <hr>

                    <!-- Observações -->
                        <div class="mt-3">
                        <h5>Observações</h5>
                        <p class="text-muted">
                            Fornecedor principal de equipamentos críticos hospitalares.  
                            Suporte técnico 24/7 e contrato de manutenção ativa.
                        </p>

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