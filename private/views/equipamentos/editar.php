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
                            <div class="col-12 col-md-3 mb-3">
                                <label class="form-label">Edifício</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($equipamento->edificio); ?>">
                            </div>

                            <div class="col-12 col-md-3 mb-3">
                                <label class="form-label">Piso</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($equipamento->piso); ?>">
                            </div>

                            <div class="col-12 col-md-3 mb-3">
                                <label class="form-label">Departamento</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($equipamento->servico); ?>">
                            </div>

                            <div class="col-12 col-md-3 mb-3">
                                <label class="form-label">Sala</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($equipamento->sala); ?>">
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
                            <div class="col-12 col-md-4 mb-3">
                                <label class="form-label">Fabricante</label>
                                <select class="form-select">
                                    <option>Siemens Healthineers</option>
                                    <option selected>Philips Healthcare</option>
                                    <option>Dräger Portugal</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-4 mb-3">
                                <label class="form-label">Distribuidor</label>
                                <select class="form-select">
                                    <option selected>MedTech Solutions</option>
                                    <option>Medical Partners</option>
                                    <option>BioMedical Portugal</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-4 mb-3">
                                <label class="form-label">Assistência Técnica</label>
                                <select class="form-select">
                                    <option selected>TechRepair Medical</option>
                                    <option>BioServiços</option>
                                    <option>MedSupport</option>
                                </select>
                            </div>
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


                    <!-- Observações -->
                    <div class="mt-3">
                        <label class="form-label">Observações</label>
                        <textarea name="observacoes" class="form-control" rows="3"><?php echo htmlspecialchars($equipamento->observacoes); ?></textarea>
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