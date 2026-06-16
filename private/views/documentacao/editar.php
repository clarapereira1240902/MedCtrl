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
                        <i class="fa-solid fa-pen-to-square me-2"></i> Editar Documento
                    </h2>

                    <a href="lista.html" class="btn btn-cancel btn-sm">
                        <i class="fa-solid fa-arrow-left me-1"></i> Voltar
                    </a>
                </div>

                <form class="form-medctrl">

                    <div class="row">

                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label">Nome do Documento</label>
                            <input type="text" class="form-control" value="Manual Monitor Philips MX450">
                        </div>

                        <div class="col-12 col-md-3 mb-3">
                            <label class="form-label">Tipo de Documento</label>
                            <select class="form-select">
                                <option selected>Manual Técnico</option>
                                <option>Certificado</option>
                                <option>Relatório de Manutenção</option>
                                <option>Ficha Técnica</option>
                                <option>Contrato</option>
                                <option>Garantia</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-3 mb-3">
                            <label class="form-label">Data do Documento</label>
                            <input type="date" class="form-control" value="2024-03-10">
                        </div>

                        <div class="col-12 col-md-4 mb-3">
                            <label class="form-label">Data de Validade</label>
                            <input type="date" class="form-control" value="2027-03-10">
                        </div>

                        <div class="col-12 col-md-4 mb-3">
                            <label class="form-label">Equipamento associado</label>
                            <select class="form-select">
                                <option>INV-001 | Monitor Multiparamétrico</option>
                                <option selected>INV-002 | Monitor de Sinais Vitais</option>
                                <option>INV-003 | Ventilador Pulmonar</option>
                                <option>INV-004 | Bomba de Infusão</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-4 mb-3">
                            <label class="form-label">Fornecedor associado</label>
                            <select class="form-select">
                                <option>Nenhum</option>
                                <option selected>Philips Healthcare</option>
                                <option>Dräger Portugal</option>
                                <option>Siemens Healthineers</option>
                            </select>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Ficheiro / Link do Documento</label>
                            <input type="text" class="form-control" value="docs/manuais/mx450.pdf">
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Observações</label>
                            <textarea class="form-control" rows="3">Documento oficial do fabricante com instruções de utilização e manutenção.</textarea>
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
<script src="<?php echo BASE_URL; ?>/assets/bootstrap/bootstrap.bundle.min.js"></script>

</body>
</html>