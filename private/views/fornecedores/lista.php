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
                <h2>
                    <i class="fa-solid fa-handshake me-2"></i> Listagem de Fornecedores
                </h2>
                <a href="novo.php" class="btn btn-primary-custom btn-sm">
                    <i class="fa-solid fa-plus me-1"></i> Novo Fornecedor
                </a>
            </div>

            <hr>

            <!-- Pesquisa de fornecedores -->
            <div class="search-card mb-4">
                <h5>
                    <i class="fa-solid fa-magnifying-glass me-2"></i>Pesquisa
                </h5>
                <div class="search-row">
                    <input type="text" class="form-control search-input" placeholder="Nome da empresa, NIF, email ou tipo de fornecedor">
                    <button class="btn btn-save search-btn" type="button">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </div>

            <p class="text-muted">Existem 3 fornecedores registados.</p>

            <!--Tabela de listagem-->
            <div class="table-responsive">
                <table class="table table-hover align-middle table-medctrl">
                    <thead>
                        <tr>
                            <th>Empresa</th>
                            <th>Tipo</th>
                            <th>Pessoa Contacto</th>
                            <th>Telefone</th>
                            <th>Email</th>
                            <th class="text-center">Equipamentos</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Dräger Portugal</strong></td>
                            <td><span class="badge bg-primary">Fabricante</span></td>
                            <td>João Costa</td>
                            <td>913456789</td>
                            <td>geral@drager.pt</td>
                            <td class="text-center">12</td>

                            <td class="text-center">
                                <a href="detalhes.php" class="btn btn-view-list btn-sm me-1"><i class="fa-solid fa-eye"></i></a>
                                <a href="editar.php" class="btn btn-edit-list btn-sm me-1"><i class="fa-solid fa-pen"></i></a>
                                <a href="apagar.php" class="btn btn-delete-list btn-sm"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>

                        <tr>
                            <td><strong>Siemens Healthineers</strong></td>
                            <td><span class="badge bg-success">Assistência Técnica</span></td>
                            <td>Ana Silva</td>
                            <td>934567890</td>
                            <td>suporte@siemens.pt</td>
                            <td class="text-center">8</td>

                            <td class="text-center">
                                <a href="detalhes.php" class="btn btn-view-list btn-sm me-1"><i class="fa-solid fa-eye"></i></a>
                                <a href="editar.php" class="btn btn-edit-list btn-sm me-1"><i class="fa-solid fa-pen"></i></a>
                                <a href="apagar.php" class="btn btn-delete-list btn-sm"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>

                        <tr>
                            <td><strong>MedSupply</strong></td>
                            <td><span class="badge bg-warning text-dark">Consumíveis</span></td>
                            <td>Carlos Ferreira</td>
                            <td>939876543</td>
                            <td>contacto@medsupply.pt</td>
                            <td class="text-center">22</td>

                            <td class="text-center">
                                <a href="detalhes.php" class="btn btn-view-list btn-sm me-1"><i class="fa-solid fa-eye"></i></a>
                                <a href="editar.php" class="btn btn-edit-list btn-sm me-1"><i class="fa-solid fa-pen"></i></a>
                                <a href="apagar.php" class="btn btn-delete-list btn-sm"><i class="fa-solid fa-trash"></i></a>
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