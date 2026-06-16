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
                    <h2>
                        <i class="fa-solid fa-plus me-2"></i>
                        Novo Documento
                    </h2>
                </div>

                <hr>

                <form class="form-medctrl">

                    <div class="row">
                        
                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label">Nome do Documento</label>
                            <input type="text" class="form-control">
                        </div>

                        <div class="col-12 col-md-3 mb-3">
                            <label class="form-label">Tipo</label>
                            <select class="form-select">
                                <option>Manual</option>
                                <option>Certificado</option>
                                <option>Relatório</option>
                                <option>Garantia</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-3 mb-3">
                            <label class="form-label">Data</label>
                            <input type="date" class="form-control">
                        </div>

                        <div class="col-12 col-md-4 mb-3">
                            <label class="form-label">Validade</label>
                            <input type="date" class="form-control">
                        </div>

                        <div class="col-12 col-md-4 mb-3">
                            <label class="form-label">Equipamento</label>
                            <select class="form-select">
                                <option selected disabled>Selecionar equipamento</option>
                                <option>INV-001 | Monitor Multiparamétrico</option>
                                <option>INV-002 | Ventilador Pulmonar</option>
                                <option>INV-003 | Bomba de Infusão</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-4 mb-3">
                            <label class="form-label">Fornecedor</label>
                            <select class="form-select">
                                <option selected>Nenhum</option>
                                <option>Dräger</option>
                                <option>Philips Healthcare</option>
                                <option>GE Healthcare</option>
                            </select>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Ficheiro / Link</label>
                            <input type="text" class="form-control" placeholder="Ex: /docs/manual.pdf ou URL">
                        </div>


                        <!-- Botões -->
                        <div class="d-flex justify-content-end gap-2 mb-4"> 
                            <a href="lista.php" class="btn btn-cancel btn-sm"> 
                                <i class="fa-solid fa-xmark me-1"></i> Cancelar 
                            </a> 
                            <button type="submit" class="btn btn-save btn-sm"> 
                                <i class="fa-regular fa-floppy-disk me-1"></i> Guardar 
                            </button> 
                        </div>

                    </div>

                </form>

            </main>
        
        </div>
    </div>

    
<!-- Bootstrap JS and custom JS --> 
<script src="../../../assets/bootstrap/bootstrap.bundle.min.js"></script> 

</body>
</html>