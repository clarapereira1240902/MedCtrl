<?php
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../../config/ligacao.php';

redirect_if_not_logged();

$menu_ativo = 'equipamentos';

$pesquisa = trim($_GET['pesquisa'] ?? '');
$categoria_id = $_GET['categoria_id'] ?? '';
$estado_id = $_GET['estado_id'] ?? '';
$criticidade_id = $_GET['criticidade_id'] ?? '';
$servico = $_GET['servico'] ?? '';
$fornecedor_id = $_GET['fornecedor_id'] ?? '';
$situacao = $_GET['situacao'] ?? '';
$ordenar = $_GET['ordenar'] ?? 'codigo';


$categorias = $ligacao->query("
    SELECT id, nome 
    FROM categorias 
    ORDER BY nome
")->fetchAll(PDO::FETCH_OBJ);

$estados = $ligacao->query("
    SELECT id, nome 
    FROM estados_equipamento
    WHERE LOWER(nome) <> 'abatido'
    ORDER BY nome
")->fetchAll(PDO::FETCH_OBJ);

$criticidades = $ligacao->query("
    SELECT id, nome 
    FROM criticidades 
    ORDER BY nome
")->fetchAll(PDO::FETCH_OBJ);

$servicos = $ligacao->query("
    SELECT DISTINCT servico
    FROM localizacoes
    WHERE ativo = 1
    ORDER BY servico
")->fetchAll(PDO::FETCH_OBJ);

$fornecedores = $ligacao->query("
    SELECT id, nome_empresa
    FROM fornecedores
    WHERE ativo = 1
    ORDER BY nome_empresa
")->fetchAll(PDO::FETCH_OBJ);


$ordenacoes_validas = [
    'codigo' => 'equipamentos.codigo_inventario ASC',
    'designacao' => 'equipamentos.designacao ASC',
    'marca' => 'equipamentos.marca ASC',
    'estado' => 'estados_equipamento.nome ASC',
    'criticidade' => 'criticidades.nome ASC',
    'situacao' => 'equipamentos.ativo DESC, equipamentos.codigo_inventario ASC'
];

$order_by = $ordenacoes_validas[$ordenar] ?? $ordenacoes_validas['codigo'];


try {
    $sql = "
        SELECT DISTINCT
            equipamentos.id,
            equipamentos.codigo_inventario,
            equipamentos.designacao,
            equipamentos.marca,
            equipamentos.modelo,
            equipamentos.numero_serie,
            equipamentos.ativo,
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
        INNER JOIN localizacoes
            ON equipamentos.localizacao_id = localizacoes.id
        LEFT JOIN equipamento_fornecedor
            ON equipamento_fornecedor.equipamento_id = equipamentos.id
        WHERE (
            equipamentos.codigo_inventario LIKE :pesquisa
            OR equipamentos.designacao LIKE :pesquisa
            OR equipamentos.marca LIKE :pesquisa
            OR equipamentos.modelo LIKE :pesquisa
            OR equipamentos.numero_serie LIKE :pesquisa
        )
        AND (:categoria_id = '' OR equipamentos.categoria_id = :categoria_id)
        AND (:estado_id = '' OR equipamentos.estado_id = :estado_id)
        AND (:criticidade_id = '' OR equipamentos.criticidade_id = :criticidade_id)
        AND (:servico = '' OR localizacoes.servico = :servico)
        AND (:fornecedor_id = '' OR equipamento_fornecedor.fornecedor_id = :fornecedor_id)
        AND (:situacao = '' OR equipamentos.ativo = :situacao)
        ORDER BY $order_by
    ";

    $stmt = $ligacao->prepare($sql);
    $stmt->execute([
        'pesquisa' => '%' . $pesquisa . '%',
        'categoria_id' => $categoria_id,
        'estado_id' => $estado_id,
        'criticidade_id' => $criticidade_id,
        'servico' => $servico,
        'fornecedor_id' => $fornecedor_id,
        'situacao' => $situacao
    ]);

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


function classe_situacao($ativo)
{
    if ((int) $ativo === 1) {
        return 'badge bg-success';
    }

    return 'badge bg-danger';
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

                <div class="d-flex gap-2 flex-wrap">
                    <a href="exportar_csv.php" class="btn btn-save btn-sm">
                        <i class="fa-solid fa-file-csv me-1"></i>
                    </a>

                    <a href="novo.php" class="btn btn-save btn-sm">
                        <i class="fa-solid fa-plus me-1"></i>Novo Equipamento
                    </a>
                </div>
            </div>

            <hr>

            <!-- Pesquisa e filtros -->
            <div class="search-card mb-4">

                <h5>
                    <i class="fa-solid fa-magnifying-glass me-2"></i>Pesquisa
                </h5>

                <form method="get">
                    <div class="search-row">
                        <input 
                            type="text" 
                            name="pesquisa" 
                            class="form-control search-input" 
                            placeholder="Código, designação, marca, modelo ou nº série" 
                            value="<?php echo htmlspecialchars($pesquisa); ?>"
                        >

                        <button class="btn btn-save search-btn" type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>

                        <button
                            class="btn btn-outline-secondary search-btn" 
                            type="button" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#filtrosAvancados"
                        >
                            <i class="fa-solid fa-sliders"></i>
                        </button>
                    </div>

                    <div class="collapse mt-3" id="filtrosAvancados">
                        <div class="row g-3 pt-3 border-top">

                            <div class="col-12 col-md-6 col-lg-4">
                                <label class="form-label">Situação</label>
                                <select name="situacao" class="form-select">
                                    <option value="">Todos</option>
                                    <option value="1" <?php echo $situacao === '1' ? 'selected' : ''; ?>>
                                        Ativos
                                    </option>
                                    <option value="0" <?php echo $situacao === '0' ? 'selected' : ''; ?>>
                                        Inativos
                                    </option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6 col-lg-4">
                                <label class="form-label">Estado</label>
                                <select name="estado_id" class="form-select">
                                    <option value="">Todos</option>
                                    <?php foreach ($estados as $estado) : ?>
                                        <option 
                                            value="<?php echo $estado->id; ?>"
                                            <?php echo ($estado_id == $estado->id) ? 'selected' : ''; ?>
                                        >
                                            <?php echo htmlspecialchars($estado->nome); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-12 col-md-6 col-lg-4">
                                <label class="form-label">Categoria</label>
                                <select name="categoria_id" class="form-select">
                                    <option value="">Todas</option>
                                    <?php foreach ($categorias as $categoria) : ?>
                                        <option
                                            value="<?php echo $categoria->id; ?>"
                                            <?php echo ($categoria_id == $categoria->id) ? 'selected' : ''; ?>
                                        >
                                            <?php echo htmlspecialchars($categoria->nome); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-12 col-md-6 col-lg-4">
                                <label class="form-label">Criticidade</label>
                                <select name="criticidade_id" class="form-select">
                                    <option value="">Todas</option>
                                    <?php foreach ($criticidades as $criticidade) : ?>
                                        <option
                                            value="<?php echo $criticidade->id; ?>"
                                            <?php echo ($criticidade_id == $criticidade->id) ? 'selected' : ''; ?>
                                        >
                                            <?php echo htmlspecialchars($criticidade->nome); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-12 col-md-6 col-lg-4">
                                <label class="form-label">Serviço</label>
                                <select name="servico" class="form-select">
                                    <option value="">Todos</option>

                                    <?php foreach ($servicos as $item) : ?>
                                        <option
                                            value="<?php echo htmlspecialchars($item->servico); ?>"
                                            <?php echo $servico === $item->servico ? 'selected' : ''; ?>
                                        >
                                            <?php echo htmlspecialchars($item->servico); ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>
                            </div>

                            <div class="col-12 col-md-6 col-lg-4">
                                <label class="form-label">Fornecedor</label>
                                <select name="fornecedor_id" class="form-select">
                                    <option value="">Todos</option>

                                    <?php foreach ($fornecedores as $fornecedor) : ?>
                                        <option
                                            value="<?php echo $fornecedor->id; ?>"
                                            <?php echo $fornecedor_id == $fornecedor->id ? 'selected' : ''; ?>
                                        >
                                            <?php echo htmlspecialchars($fornecedor->nome_empresa); ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>
                            </div>

                            <div class="col-12 col-md-6 col-lg-4">
                                <label class="form-label">Ordenar por</label>
                                <select name="ordenar" class="form-select">
                                    <option value="codigo" <?php echo $ordenar === 'codigo' ? 'selected' : ''; ?>>
                                        Código
                                    </option>

                                    <option value="designacao" <?php echo $ordenar === 'designacao' ? 'selected' : ''; ?>>
                                        Designação
                                    </option>

                                    <option value="marca" <?php echo $ordenar === 'marca' ? 'selected' : ''; ?>>
                                        Marca
                                    </option>

                                    <option value="estado" <?php echo $ordenar === 'estado' ? 'selected' : ''; ?>>
                                        Estado
                                    </option>

                                    <option value="criticidade" <?php echo $ordenar === 'criticidade' ? 'selected' : ''; ?>>
                                        Criticidade
                                    </option>

                                    <option value="situacao" <?php echo $ordenar === 'situacao' ? 'selected' : ''; ?>>
                                        Situação
                                    </option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-3">

                                <a href="lista.php" class="btn btn-cancel btn-sm">
                                    <i class="fa-solid fa-rotate-left me-1"></i>Limpar
                                </a>

                                <button type="submit" class="btn btn-save btn-sm">
                                    <i class="fa-solid fa-filter me-1"></i>
                                    Aplicar filtros
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

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
                <p class="text-muted">
                    Existem <?php echo count($equipamentos); ?> equipamentos registados.
                </p>


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
                                <th>Situação</th>
                                <th class="text-center" style="min-width: 150px;">Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($equipamentos as $equipamento) : ?>
                                <tr class="<?php echo ((int) $equipamento->ativo === 0) ? 'table-secondary' : ''; ?>">
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

                                    <td>
                                        <span class="<?php echo classe_situacao($equipamento->ativo); ?>">
                                            <?php echo ((int) $equipamento->ativo === 1) ? 'Ativo' : 'Inativo'; ?>
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1 flex-nowrap">
                                            <a href="detalhes.php?id=<?php echo $equipamento->id; ?>" class="btn btn-sm btn-view-list" title="Ver detalhes">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>

                                            <?php if ((int) $equipamento->ativo === 1) : ?>

                                                <a href="editar.php?id=<?php echo $equipamento->id; ?>" class="btn btn-sm btn-edit-list" title="Editar">
                                                    <i class="fa-solid fa-pen"></i>
                                                </a>

                                                <a href="apagar.php?id=<?php echo $equipamento->id; ?>" class="btn btn-sm btn-delete-list" title="Inativar">
                                                    <i class="fa-solid fa-ban"></i>
                                                </a>

                                            <?php else : ?>

                                                <a href="apagar.php?id=<?php echo $equipamento->id; ?>" class="btn btn-sm btn-success" title="Reativar">
                                                    <i class="fa-solid fa-rotate-left"></i>
                                                </a>

                                            <?php endif; ?>
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