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
                        <i class="fa-solid fa-file-medical me-2"></i>Documentação
                    </h2>
                    <a href="novo.php" class="btn btn-primary-custom btn-sm">
                        <i class="fa-solid fa-plus me-1"></i> Novo Documento
                    </a>
                </div>

                <hr>

                <!-- Pesquisa de documentação -->
                <div class="search-card mb-4">
                    <h5>
                        <i class="fa-solid fa-magnifying-glass me-2"></i>Pesquisa
                    </h5>
                    <div class="search-row">
                        <input type="text" class="form-control search-input" placeholder="Documento, equipamento ou fornecedor">
                        <button class="btn btn-save search-btn" type="button">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                        <button class="btn btn-outline-secondary search-btn" type="button" data-bs-toggle="collapse" data-bs-target="#filtrosDocumentacao">
                            <i class="fa-solid fa-sliders"></i>
                        </button>
                    </div>

                    <div class="collapse mt-3" id="filtrosDocumentacao">
                        <div class="row g-3 pt-3 border-top">
                            <div class="col-12 col-md-6">
                                <label class="form-label">Tipo de Documento</label>
                                <select class="form-select">
                                    <option selected>Todos</option>
                                    <option>Manual Técnico</option>
                                    <option>Certificado</option>
                                    <option>Contrato</option>
                                    <option>Ficha Técnica</option>
                                    <option>Relatório</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Validade</label>
                                <select class="form-select">
                                    <option selected>Todas</option>
                                    <option>Válido</option>
                                    <option>A expirar</option>
                                    <option>Expirado</option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-3">
                                <button type="reset" class="btn btn-cancel btn-sm">
                                    <i class="fa-solid fa-rotate-left me-1"></i>Limpar
                                </button>
                                <button type="button" class="btn btn-save btn-sm">
                                    <i class="fa-solid fa-filter me-1"></i>Aplicar filtros
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!--Tabela de listagem-->
                <div class="table-responsive">
                    <table class="table table-medctrl align-middle">
                        <thead>
                            <tr>
                                <th>Documento</th>
                                <th>Tipo</th>
                                <th>Equipamento</th>
                                <th>Fornecedor</th>
                                <th>Validade</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Manual Philips MX450</td>
                                <td><span class="badge bg-primary mb-2">Manual Técnico</span></td>
                                <td>Monitor Vital</td>
                                <td>Philips</td>
                                <td>2028-01-01</td>

                                <td class="text-center">
                                    <a href="detalhes.php" class="btn btn-sm btn-view-list"><i class="fa-solid fa-eye"></i></a>
                                    <a href="editar.php" class="btn btn-sm btn-edit-list"><i class="fa-solid fa-pen"></i></a>
                                    <a href="apagar.php" class="btn btn-sm btn-delete-list"><i class="fa-solid fa-trash"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </main>
        
        </div>
    </div>

    
<!-- Bootstrap JS and custom JS --> 
<script src="../../../assets/bootstrap/bootstrap.bundle.min.js"></script> 

</body>
</html>