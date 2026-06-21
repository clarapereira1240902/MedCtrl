<?php
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../../config/ligacao.php';

redirect_if_not_logged();

$menu_ativo = 'equipamentos';

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: lista.php');
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {
        $sql_update = "
            UPDATE equipamentos
            SET
                localizacao_id = :localizacao_id,
                categoria_id = :categoria_id,
                estado_id = :estado_id,
                criticidade_id = :criticidade_id,
                tipo_entrada_id = :tipo_entrada_id,
                codigo_inventario = :codigo_inventario,
                designacao = :designacao,
                marca = :marca,
                modelo = :modelo,
                numero_serie = :numero_serie,
                fabricante = :fabricante,
                data_aquisicao = :data_aquisicao,
                ano_fabrico = :ano_fabrico,
                custo_aquisicao = :custo_aquisicao,
                observacoes = :observacoes
            WHERE id = :id
            AND ativo = 1
        ";

        $stmt_update = $ligacao->prepare($sql_update);

        $stmt_update->execute([
            'localizacao_id' => (int) $_POST['localizacao_id'],
            'categoria_id' => (int) $_POST['categoria_id'],
            'estado_id' => (int) $_POST['estado_id'],
            'criticidade_id' => (int) $_POST['criticidade_id'],
            'tipo_entrada_id' => (int) $_POST['tipo_entrada_id'],
            'codigo_inventario' => trim($_POST['codigo_inventario']),
            'designacao' => trim($_POST['designacao']),
            'marca' => trim($_POST['marca']),
            'modelo' => trim($_POST['modelo']),
            'numero_serie' => trim($_POST['numero_serie']),
            'fabricante' => trim($_POST['fabricante']),
            'data_aquisicao' => $_POST['data_aquisicao'] ?: null,
            'ano_fabrico' => $_POST['ano_fabrico'] ?: null,
            'custo_aquisicao' => $_POST['custo_aquisicao'] ?: null,
            'observacoes' => trim($_POST['observacoes']),
            'id' => $id
        ]);

        $ligacao->prepare("
            DELETE FROM equipamento_fornecedor
            WHERE equipamento_id = :id
        ")->execute(['id' => $id]);

        if (!empty($_POST['fornecedores'])) {
            $stmt_insert_fornecedor = $ligacao->prepare("
                INSERT INTO equipamento_fornecedor (
                    equipamento_id,
                    fornecedor_id,
                    tipo_fornecedor_id
                ) VALUES (
                    :equipamento_id,
                    :fornecedor_id,
                    :tipo_fornecedor_id
                )
            ");

            foreach ($_POST['fornecedores'] as $tipo_fornecedor_id => $fornecedor_id) {
                if (!empty($fornecedor_id)) {
                    $stmt_insert_fornecedor->execute([
                        'equipamento_id' => $id,
                        'fornecedor_id' => (int) $fornecedor_id,
                        'tipo_fornecedor_id' => (int) $tipo_fornecedor_id
                    ]);
                }
            }
        }

        header('Location: detalhes.php?id=' . $id);
        exit;

    } catch (PDOException $e) {
        die('Erro ao atualizar equipamento.');
    }
}


$categorias = $ligacao->query("SELECT id, nome FROM categorias ORDER BY nome")->fetchAll(PDO::FETCH_OBJ);
$estados = $ligacao->query("SELECT id, nome FROM estados_equipamento ORDER BY nome")->fetchAll(PDO::FETCH_OBJ);
$criticidades = $ligacao->query("SELECT id, nome FROM criticidades ORDER BY nome")->fetchAll(PDO::FETCH_OBJ);
$tipos_entrada = $ligacao->query("SELECT id, nome FROM tipos_entrada ORDER BY nome")->fetchAll(PDO::FETCH_OBJ);
$localizacoes = $ligacao->query("
    SELECT id, edificio, piso, servico, sala
    FROM localizacoes
    WHERE ativo = 1
    ORDER BY edificio, piso, servico, sala
")->fetchAll(PDO::FETCH_OBJ);

$tipos_fornecedor = $ligacao->query("
    SELECT id, nome
    FROM tipos_fornecedor
    ORDER BY nome
")->fetchAll(PDO::FETCH_OBJ);

$fornecedores = $ligacao->query("
    SELECT id, nome_empresa, tipo_fornecedor_id
    FROM fornecedores
    WHERE ativo = 1
    ORDER BY nome_empresa
")->fetchAll(PDO::FETCH_OBJ);


try {
    $sql = "
        SELECT
            e.*,
            c.nome AS categoria,
            est.nome AS estado,
            cri.nome AS criticidade,
            te.nome AS tipo_entrada,
            l.edificio,
            l.piso,
            l.servico,
            l.sala
        FROM equipamentos e
        INNER JOIN categorias c ON e.categoria_id = c.id
        INNER JOIN estados_equipamento est ON e.estado_id = est.id
        INNER JOIN criticidades cri ON e.criticidade_id = cri.id
        INNER JOIN tipos_entrada te ON e.tipo_entrada_id = te.id
        INNER JOIN localizacoes l ON e.localizacao_id = l.id
        WHERE e.id = :id
        AND e.ativo = 1
        LIMIT 1
    ";

    $stmt = $ligacao->prepare($sql);
    $stmt->execute(['id' => $id]);
    $equipamento = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$equipamento) {
        header('Location: lista.php');
        exit;
    }

    $stmt_fornecedores = $ligacao->prepare("
        SELECT fornecedor_id, tipo_fornecedor_id
        FROM equipamento_fornecedor
        WHERE equipamento_id = :id
    ");

    $stmt_fornecedores->execute(['id' => $id]);

    $fornecedores_associados = [];

    foreach ($stmt_fornecedores->fetchAll(PDO::FETCH_OBJ) as $item) {
        $fornecedores_associados[$item->tipo_fornecedor_id] = $item->fornecedor_id;
    }

    $stmt_garantia = $ligacao->prepare("
        SELECT *
        FROM garantias_contratos
        WHERE equipamento_id = :id
        LIMIT 1
    ");

    $stmt_garantia->execute(['id' => $id]);

    $garantia = $stmt_garantia->fetch(PDO::FETCH_OBJ);

    $stmt_documentos = $ligacao->prepare("
        SELECT
            d.id,
            d.nome,
            d.data_validade,
            td.nome AS tipo_documento
        FROM documentos d
        INNER JOIN tipos_documento td
            ON d.tipo_documento_id = td.id
        WHERE d.equipamento_id = :id
        AND d.ativo = 1
        ORDER BY d.nome ASC
    ");

    $stmt_documentos->execute(['id' => $id]);

    $documentos = $stmt_documentos->fetchAll(PDO::FETCH_OBJ);

} catch (PDOException $e) {
    die('Erro ao carregar equipamento.');
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
                        <i class="fa-solid fa-pen-to-square me-2"></i> Editar Equipamento
                    </h2>

                    <a href="lista.php" class="btn btn-cancel btn-sm">
                        <i class="fa-solid fa-arrow-left me-1"></i> Voltar
                    </a>
                </div>

                <form class="form-medctrl" method="post">
                    <div class="row">

                        <!-- Informação geral do equipamento -->
                        <div class="col-12 mb-3">
                            <h4><i class="fa-solid fa-circle-info me-2"></i>Informação Geral</h4>
                            <hr>
                        </div>

                        <!-- Coluna esquerda -->
                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Código de Inventário</label>
                                <input type="text" name="codigo_inventario" class="form-control" value="<?php echo htmlspecialchars($equipamento->codigo_inventario); ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Designação</label>
                                <input type="text" name="designacao" class="form-control" value="<?php echo htmlspecialchars($equipamento->designacao); ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Categoria</label>
                                <select name="categoria_id" class="form-select">
                                    <?php foreach ($categorias as $categoria) : ?>
                                        <option value="<?php echo $categoria->id; ?>"
                                            <?php echo $categoria->id == $equipamento->categoria_id ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($categoria->nome); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Marca</label>
                                <input type="text" name="marca" class="form-control" value="<?php echo htmlspecialchars($equipamento->marca); ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Modelo</label>
                                <input type="text" name="modelo" class="form-control" value="<?php echo htmlspecialchars($equipamento->modelo); ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Número de Série</label>
                                <input type="text" name="numero_serie" class="form-control" value="<?php echo htmlspecialchars($equipamento->numero_serie); ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Fabricante</label>
                                <input type="text" name="fabricante" class="form-control" value="<?php echo htmlspecialchars($equipamento->fabricante); ?>">
                            </div>
                        </div>

                        <!-- Coluna direita -->
                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Data de Aquisição</label>
                                <input type="date" name="data_aquisicao" class="form-control" value="<?php echo htmlspecialchars($equipamento->data_aquisicao); ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Ano de Fabrico</label>
                                <input type="number" name="ano_fabrico" class="form-control" value="<?php echo htmlspecialchars($equipamento->ano_fabrico); ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Custo de Aquisição</label>
                                <input type="text" name="custo_aquisicao" class="form-control" value="<?php echo htmlspecialchars($equipamento->custo_aquisicao); ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tipo de Entrada</label>
                                <select name="tipo_entrada_id" class="form-select">
                                    <?php foreach ($tipos_entrada as $tipo) : ?>
                                        <option value="<?php echo $tipo->id; ?>" <?php echo $tipo->id == $equipamento->tipo_entrada_id ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($tipo->nome); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Estado Atual</label>
                                <select name="estado_id" class="form-select">
                                    <?php foreach ($estados as $estado) : ?>
                                        <option value="<?php echo $estado->id; ?>" <?php echo $estado->id == $equipamento->estado_id ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($estado->nome); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Criticidade</label>
                                <select name="criticidade_id" class="form-select">
                                    <?php foreach ($criticidades as $criticidade) : ?>
                                        <option value="<?php echo $criticidade->id; ?>" <?php echo $criticidade->id == $equipamento->criticidade_id ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($criticidade->nome); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                    </div>


                    <!-- Localização do equipamento -->
                    <div class="mt-4 mb-4">
                        <h5 class="mb-3">
                            <i class="fa-solid fa-location-dot me-2"></i>Localização
                        </h5>
                        <hr>

                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label">Localização</label>

                                <select name="localizacao_id" class="form-select" required>
                                    <?php foreach ($localizacoes as $localizacao) : ?>
                                        <option value="<?php echo $localizacao->id; ?>"
                                            <?php echo $localizacao->id == $equipamento->localizacao_id ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($localizacao->edificio . ' - ' . $localizacao->piso . ' - ' . $localizacao->servico . ' - ' . $localizacao->sala); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>


                    <!-- Fornecedores do equipamento -->
                    <div class="mt-4 mb-4">
                        <h5 class="mb-3">
                            <i class="fa-solid fa-handshake me-2"></i>Fornecedores
                        </h5>
                        <hr>

                        <div class="row">
                            <?php foreach ($tipos_fornecedor as $tipo) : ?>
                                <div class="col-12 col-md-4 mb-3">
                                    <label class="form-label">
                                        <?php echo htmlspecialchars($tipo->nome); ?>
                                    </label>

                                    <select name="fornecedores[<?php echo $tipo->id; ?>]" class="form-select">
                                        <option value="">Nenhum</option>

                                        <?php foreach ($fornecedores as $fornecedor) : ?>
                                            <?php if ($fornecedor->tipo_fornecedor_id == $tipo->id) : ?>
                                                <option value="<?php echo $fornecedor->id; ?>"
                                                    <?php echo (($fornecedores_associados[$tipo->id] ?? '') == $fornecedor->id) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($fornecedor->nome_empresa); ?>
                                                </option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>


                    <!-- Garantia do equipamento  -->
                    <div class="mt-4 mb-4">
                        <h5 class="mb-3">
                            <i class="fa-solid fa-shield-halved me-2"></i>
                            Garantias e Contratos
                        </h5>
                        <hr>

                        <div class="row">
                            <div class="col-12 col-md-4 mb-3">
                                <label class="form-label">Início Garantia</label>
                                <input type="date" class="form-control">
                            </div>

                            <div class="col-12 col-md-4 mb-3">
                                <label class="form-label">Fim Garantia</label>
                                <input type="date" class="form-control">
                            </div>

                            <div class="col-12 col-md-4 mb-3">
                                <label class="form-label">Contrato de Manutenção</label>
                                <select class="form-select">
                                    <option>Sim</option>
                                    <option>Não</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label">Tipo de Contrato</label>
                                <select class="form-select">
                                    <option>Preventivo</option>
                                    <option>Corretivo</option>
                                    <option>Full Service</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label">Entidade Responsável</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                    </div>


                    <!--Documentação do equipamento-->
                    <div class="mt-4 mb-4">
                        <h5 class="mb-3">
                            <i class="fa-solid fa-file-medical me-2"></i>Documentação Associada
                        </h5>
                        <hr>

                        <?php if (count($documentos) === 0) : ?>

                            <p class="text-muted">
                                Não existem documentos associados a este equipamento.
                            </p>

                        <?php else : ?>

                            <?php foreach ($documentos as $documento) : ?>
                                <div class="documento-card mb-3">
                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                                        <div>
                                            <h5 class="mb-1">
                                                <?php echo htmlspecialchars($documento->nome); ?>
                                            </h5>

                                            <span class="text-muted">
                                                <?php echo htmlspecialchars($documento->tipo_documento); ?>
                                                |
                                                Validade:
                                                <?php echo htmlspecialchars($documento->data_validade ?? '—'); ?>
                                            </span>
                                        </div>

                                        <div class="d-flex gap-2">
                                            <a href="../documentacao/detalhes.php?id=<?php echo $documento->id; ?>" class="btn btn-save btn-sm">
                                                <i class="fa-solid fa-eye me-1"></i>Ver
                                            </a>

                                            <a href="../documentacao/apagar.php?id=<?php echo $documento->id; ?>" class="btn btn-save btn-sm">
                                                <i class="fa-solid fa-trash me-1"></i>Eliminar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        <?php endif; ?>

                        <div class="mt-3">
                            <a href="../documentacao/novo.php?equipamento_id=<?php echo $equipamento->id; ?>" class="btn btn-primary-custom btn-sm">
                                <i class="fa-solid fa-plus me-1"></i>Novo documento
                            </a>
                        </div>
                    </div>


                    <!-- Observações -->
                    <div class="mt-3">
                        <label class="form-label">Observações</label>
                        <textarea name="observacoes" class="form-control" rows="3"><?php echo htmlspecialchars($equipamento->observacoes); ?></textarea>
                    </div>

                    <!-- Botões -->
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="lista.php" class="btn btn-cancel btn-sm">Cancelar</a>

                        <button type="submit" class="btn btn-edit btn-sm">
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