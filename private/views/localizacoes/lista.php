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
                        <i class="fa-solid fa-location-dot me-2"></i> Localizações
                    </h2>
                    <a href="novo.php" class="btn btn-primary-custom btn-sm">
                        <i class="fa-solid fa-plus me-1"></i> Nova Localização
                    </a>
                </div>

                <hr>

                <!-- Pesquisa de localizações -->
                <div class="search-card mb-4">
                    <h5>
                        <i class="fa-solid fa-magnifying-glass me-2"></i>Pesquisa
                    </h5>

                    <div class="search-row">
                        <input type="text" class="form-control search-input" placeholder="Edifício, serviço, piso ou sala">
                        <button class="btn btn-save search-btn" type="button">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                </div>

                <!-- Tabela de listagem -->
                <div class="table-responsive">
                    <table class="table table-medctrl table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Edifício</th>
                                <th>Piso</th>
                                <th>Serviço</th>
                                <th>Sala</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Hospital Central</td>
                                <td>2º Piso</td>
                                <td>Cardiologia</td>
                                <td>Sala 12</td>
                                <td class="text-center">
                                    <a href="editar.php" class="btn btn-edit-list btn-sm"><i class="fa-solid fa-pen"></i></a>
                                    <a href="apagar.php" class="btn btn-delete-list btn-sm"><i class="fa-solid fa-trash"></i></a>
                                </td>
                            </tr>

                            <tr>
                                <td>Hospital Norte</td>
                                <td>1º Piso</td>
                                <td>Urgência</td>
                                <td>Sala 3</td>
                                <td class="text-center">
                                    <a href="editar.php" class="btn btn-edit-list btn-sm"><i class="fa-solid fa-pen"></i></a>
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
<script src="<?php echo BASE_URL; ?>/assets/bootstrap/bootstrap.bundle.min.js"></script> 

</body>
</html>