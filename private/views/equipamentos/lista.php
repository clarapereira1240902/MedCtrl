<?php
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../../config/ligacao.php';

redirect_if_not_logged();

$menu_ativo = 'equipamentos';

try {
    $sql = "
        SELECT 
            equipamentos.id,
            equipamentos.codigo_inventario,
            equipamentos.designacao,
            equipamentos.marca,
            equipamentos.modelo,
            equipamentos.numero_serie,
            categorias.nome AS categoria,
            estados_equipamento.nome AS estado,
            criticidades.nome AS criticidade
        FROM equipamentos
        INNER JOIN categorias 
            ON equipamentos.categoria_id = categorias.id
        INNER JOIN estados_equipamento 
            ON equipamentos.estado_id = estados_equipamento.id
        INNER JOIN criticidades 
            ON equipamentos.criticidade_id = criticidades.id
        WHERE equipamentos.ativo = 1
        ORDER BY equipamentos.codigo_inventario ASC
    ";

    $stmt = $ligacao->query($sql);
    $equipamentos = $stmt->fetchAll(PDO::FETCH_OBJ);
    $erro = '';

} catch (PDOException $e) {
    $erro = 'Aconteceu um erro ao carregar os equipamentos.';
    $equipamentos = [];
}

function classe_estado($estado)
{
    $estado = mb_strtolower(trim($estado), 'UTF-8');

    if ($estado === 'operacional') {
        return 'badge bg-success';
    }

    if ($estado === 'em manutenção' || $estado === 'em manutencao') {
        return 'badge bg-warning text-dark';
    }

    if ($estado === 'inativo') {
        return 'badge bg-danger';
    }

    if ($estado === 'em calibração' || $estado === 'em calibracao') {
        return 'badge bg-info text-dark';
    }

    if ($estado === 'em quarentena') {
        return 'badge bg-secondary';
    }

    if ($estado === 'abatido') {
        return 'badge bg-dark';
    }

    return 'badge bg-secondary';
}

function classe_criticidade($criticidade)
{
    $criticidade = mb_strtolower(trim($criticidade), 'UTF-8');

    if ($criticidade === 'baixa') {
        return 'badge bg-success';
    }

    if ($criticidade === 'média' || $criticidade === 'media') {
        return 'badge bg-warning text-dark';
    }

    if ($criticidade === 'alta') {
        return 'badge bg-danger';
    }

    if ($criticidade === 'suporte de vida') {
        return 'badge bg-dark';
    }

    return 'badge bg-secondary';
}

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
                        <i class="fa-solid fa-laptop-medical me-2"></i>Listagem de Equipamentos
                    </h2>
                    <a href="novo.php" class="btn btn-primary-custom btn-sm">
                        <i class="fa-solid fa-plus me-1"></i> Novo Equipamento
                    </a>
                </div>

                <hr>

                <!-- Pesquisa e filtros -->
                <div class="search-card mb-4">

                    <h5>
                        <i class="fa-solid fa-magnifying-glass me-2"></i>Pesquisa
                    </h5>

                    <div class="search-row">
                        <input type="text" class="form-control search-input" placeholder="Código, designação, marca, modelo ou nº série">
                        <button class="btn btn-save search-btn" type="button">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                        <button
                            class="btn btn-outline-secondary search-btn" type="button" data-bs-toggle="collapse" data-bs-target="#filtrosAvancados">
                            <i class="fa-solid fa-sliders"></i>
                        </button>
                    </div>

                    <div class="collapse mt-3" id="filtrosAvancados">
                        <div class="row g-3 pt-3 border-top">
                            <div class="col-12 col-md-6 col-lg-3">
                                <label class="form-label">Estado</label>
                                <select class="form-select">
                                    <option selected>Todos</option>
                                    <option>Ativo</option>
                                    <option>Em manutenção</option>
                                    <option>Inativo</option>
                                    <option>Em calibração</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6 col-lg-3">
                                <label class="form-label">Categoria</label>
                                <select class="form-select">
                                    <option selected>Todas</option>
                                    <option>Monitorização</option>
                                    <option>Suporte de vida</option>
                                    <option>Terapia</option>
                                    <option>Diagnóstico</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6 col-lg-3">
                                <label class="form-label">Criticidade</label>
                                <select class="form-select">
                                    <option selected>Todas</option>
                                    <option>Baixa</option>
                                    <option>Média</option>
                                    <option>Alta</option>
                                    <option>Suporte de vida</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6 col-lg-3">
                                <label class="form-label">Serviço</label>
                                <select class="form-select">
                                    <option selected>Todos</option>
                                    <option>Cardiologia</option>
                                    <option>Urgência</option>
                                    <option>UCI</option>
                                    <option>Radiologia</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Fornecedor</label>
                                <select class="form-select">
                                    <option selected>Todos</option>
                                    <option>Philips Healthcare</option>
                                    <option>Dräger</option>
                                    <option>Siemens</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Ordenar por</label>
                                <select class="form-select">
                                    <option selected>Código</option>
                                    <option>Designação</option>
                                    <option>Marca</option>
                                    <option>Estado</option>
                                    <option>Criticidade</option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-3">

                                <button type="reset" class="btn btn-cancel btn-sm">
                                    <i class="fa-solid fa-rotate-left me-1"></i>
                                    Limpar
                                </button>

                                <button type="button" class="btn btn-save btn-sm">
                                    <i class="fa-solid fa-filter me-1"></i>
                                    Aplicar filtros
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

                
                <?php if (!empty($erro)) : ?>

                    <p class="text-center text-danger">
                        <?php echo htmlspecialchars($erro); ?>
                    </p>

                <?php elseif (count($equipamentos) === 0) : ?>

                    <p class="text-muted">
                        Não existem equipamentos registados.
                    </p>

                <?php else : ?>

                    <!--Tabela de listagem-->
                    <div class="table-responsive">
                        <table class="table table-medctrl">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Designação</th>
                                    <th>Categoria</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Nº Série</th>
                                    <th>Estado</th>
                                    <th>Criticidade</th>
                                    <th class="text-center" style="min-width: 130px;">Ações</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($equipamentos as $equipamento) : ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($equipamento->codigo_inventario); ?></td>
                                        <td><?php echo htmlspecialchars($equipamento->designacao); ?></td>
                                        <td><?php echo htmlspecialchars($equipamento->categoria); ?></td>
                                        <td><?php echo htmlspecialchars($equipamento->marca); ?></td>
                                        <td><?php echo htmlspecialchars($equipamento->modelo); ?></td>
                                        <td><?php echo htmlspecialchars($equipamento->numero_serie); ?></td>

                                        <td>
                                            <span class="<?php echo classe_estado($equipamento->estado); ?>">
                                                <?php echo htmlspecialchars($equipamento->estado); ?>
                                            </span>
                                        </td>

                                        <td>
                                            <span class="<?php echo classe_criticidade($equipamento->criticidade); ?>">
                                                <?php echo htmlspecialchars($equipamento->criticidade); ?>
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1 flex-nowrap">
                                                <a href="detalhes.php?id=<?php echo $equipamento->id; ?>" class="btn btn-sm btn-view-list">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>

                                                <a href="editar.php?id=<?php echo $equipamento->id; ?>" class="btn btn-sm btn-edit-list">
                                                    <i class="fa-solid fa-pen"></i>
                                                </a>

                                                <a href="apagar.php?id=<?php echo $equipamento->id; ?>" class="btn btn-sm btn-delete-list">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                <?php endif; ?>

            </main>
        
        </div>
    </div>

    
<!-- Bootstrap JS and custom JS --> 
<script src="<?php echo BASE_URL; ?>/assets/bootstrap/bootstrap.bundle.min.js"></script>

</body>
</html>