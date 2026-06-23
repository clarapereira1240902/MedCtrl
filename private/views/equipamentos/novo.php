<?php
require_once __DIR__ . '/../../includes/funcoes.php';

redirect_if_not_logged();

exigir_admin_ou_tecnico();

$menu_ativo = 'equipamentos';

require_once __DIR__ . '/../../../config/ligacao.php';

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

$tipos_entrada = $ligacao->query("
    SELECT id, nome 
    FROM tipos_entrada 
    ORDER BY nome
")->fetchAll(PDO::FETCH_OBJ);

$localizacoes = $ligacao->query("
    SELECT id, edificio, piso, servico, sala 
    FROM localizacoes 
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

$erro_formulario = '';
$form = $_POST;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {

        $estado_id = (int) ($_POST['estado_id'] ?? 0);

        $stmt_estado = $ligacao->prepare("
            SELECT id
            FROM estados_equipamento
            WHERE id = :id
            AND LOWER(nome) <> 'abatido'
            LIMIT 1
        ");

        $stmt_estado->execute([
            'id' => $estado_id
        ]);

        if (!$stmt_estado->fetch()) {
            $erro_formulario = 'O estado selecionado não é válido.';
        }

        if (empty($erro_formulario)) {
            $stmt_check = $ligacao->prepare("
                SELECT id
                FROM equipamentos
                WHERE codigo_inventario = :codigo_inventario
                LIMIT 1
            ");

            $stmt_check->execute([
                'codigo_inventario' => trim($_POST['codigo_inventario'] ?? '')
            ]);

            if ($stmt_check->fetch()) {
                $erro_formulario = 'Já existe um equipamento com esse código de inventário.';
            }
        }

        if (empty($erro_formulario)) {

            $ligacao->beginTransaction();

            $sql_insert = "
                INSERT INTO equipamentos (
                    localizacao_id,
                    categoria_id,
                    estado_id,
                    criticidade_id,
                    tipo_entrada_id,
                    codigo_inventario,
                    designacao,
                    marca,
                    modelo,
                    numero_serie,
                    fabricante,
                    data_aquisicao,
                    ano_fabrico,
                    custo_aquisicao,
                    observacoes,
                    ativo
                )
                VALUES (
                    :localizacao_id,
                    :categoria_id,
                    :estado_id,
                    :criticidade_id,
                    :tipo_entrada_id,
                    :codigo_inventario,
                    :designacao,
                    :marca,
                    :modelo,
                    :numero_serie,
                    :fabricante,
                    :data_aquisicao,
                    :ano_fabrico,
                    :custo_aquisicao,
                    :observacoes,
                    1
                )
            ";

            $stmt = $ligacao->prepare($sql_insert);

            $stmt->execute([
                'localizacao_id' => (int) ($_POST['localizacao_id'] ?? 0),
                'categoria_id' => (int) ($_POST['categoria_id'] ?? 0),
                'estado_id' => $estado_id,
                'criticidade_id' => (int) ($_POST['criticidade_id'] ?? 0),
                'tipo_entrada_id' => (int) ($_POST['tipo_entrada_id'] ?? 0),
                'codigo_inventario' => trim($_POST['codigo_inventario'] ?? ''),
                'designacao' => trim($_POST['designacao'] ?? ''),
                'marca' => trim($_POST['marca'] ?? ''),
                'modelo' => trim($_POST['modelo'] ?? ''),
                'numero_serie' => trim($_POST['numero_serie'] ?? ''),
                'fabricante' => trim($_POST['fabricante'] ?? ''),
                'data_aquisicao' => !empty($_POST['data_aquisicao']) ? $_POST['data_aquisicao'] : null,
                'ano_fabrico' => !empty($_POST['ano_fabrico']) ? $_POST['ano_fabrico'] : null,
                'custo_aquisicao' => !empty($_POST['custo_aquisicao']) ? $_POST['custo_aquisicao'] : null,
                'observacoes' => trim($_POST['observacoes'] ?? '')
            ]);

            $novo_id = $ligacao->lastInsertId();

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
                            'equipamento_id' => $novo_id,
                            'fornecedor_id' => (int) $fornecedor_id,
                            'tipo_fornecedor_id' => (int) $tipo_fornecedor_id
                        ]);
                    }
                }
            }

            $stmt_garantia = $ligacao->prepare("
                INSERT INTO garantias_contratos (
                    equipamento_id,
                    inicio_garantia,
                    fim_garantia,
                    tem_contrato_manutencao,
                    tipo_contrato,
                    entidade_responsavel,
                    periodicidade,
                    observacoes
                ) VALUES (
                    :equipamento_id,
                    :inicio_garantia,
                    :fim_garantia,
                    :tem_contrato_manutencao,
                    :tipo_contrato,
                    :entidade_responsavel,
                    :periodicidade,
                    :observacoes
                )
            ");

            $stmt_garantia->execute([
                'equipamento_id' => $novo_id,
                'inicio_garantia' => !empty($_POST['inicio_garantia']) ? $_POST['inicio_garantia'] : null,
                'fim_garantia' => !empty($_POST['fim_garantia']) ? $_POST['fim_garantia'] : null,
                'tem_contrato_manutencao' => (int) ($_POST['tem_contrato_manutencao'] ?? 0),
                'tipo_contrato' => trim($_POST['tipo_contrato'] ?? ''),
                'entidade_responsavel' => trim($_POST['entidade_responsavel'] ?? ''),
                'periodicidade' => trim($_POST['periodicidade'] ?? ''),
                'observacoes' => trim($_POST['observacoes_garantia'] ?? '')
            ]);

            registar_log(
                $ligacao,
                'Criou equipamento',
                'equipamentos',
                (int) $novo_id,
                'Novo equipamento registado: ' . trim($_POST['codigo_inventario'] ?? '') . ' - ' . trim($_POST['designacao'] ?? '')
            );

            $ligacao->commit();

            header('Location: detalhes.php?id=' . $novo_id);
            exit;
        }

    } catch (PDOException $e) {

        if ($ligacao->inTransaction()) {
            $ligacao->rollBack();
        }

        $erro_formulario = 'Erro ao criar equipamento. Verifica se os dados preenchidos são válidos.';
    }
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
                    <i class="fa-solid fa-plus me-2"></i>
                    Novo Equipamento
                </h2>
            </div>

            <hr>

            <?php if (!empty($erro_formulario)) : ?>
                <div class="alert alert-danger">
                    <i class="fa-solid fa-circle-exclamation me-1"></i>
                    <?php echo htmlspecialchars($erro_formulario); ?>
                </div>
            <?php endif; ?>

            <form class="form-medctrl" method="post">

                <div class="row">
                    
                    <!--Informações gerais do equipamento-->
                    <div class="col-12 mb-3">
                        <h4><i class="fa-solid fa-circle-info me-2"></i>Informação Geral</h4>
                        <hr>
                    </div>

                    <div class="col-12 col-md-4 mb-3">
                        <label class="form-label">Código de Inventário</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            name="codigo_inventario" 
                            placeholder="Ex: EQ001" 
                            value="<?php echo htmlspecialchars($form['codigo_inventario'] ?? ''); ?>"
                            required
                        >
                    </div>

                    <div class="col-12 col-md-8 mb-3">
                        <label class="form-label">Designação</label>
                        <input 
                            type="text" 
                            name="designacao" 
                            class="form-control" 
                            placeholder="Ex: Monitor Multiparamétrico" 
                            value="<?php echo htmlspecialchars($form['designacao'] ?? ''); ?>"
                            required
                        >
                    </div>

                    <div class="col-12 col-md-4 mb-3">
                        <label class="form-label">Categoria</label>
                        <select name="categoria_id" class="form-select" required>
                            <option value="">Selecione uma categoria</option>
                            <?php foreach ($categorias as $categoria) : ?>
                                <option value="<?php echo $categoria->id; ?>" <?php echo (($form['categoria_id'] ?? '') == $categoria->id) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($categoria->nome); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12 col-md-4 mb-3">
                        <label class="form-label">Marca</label>
                        <input 
                            type="text" 
                            name="marca" 
                            class="form-control" 
                            value="<?php echo htmlspecialchars($form['marca'] ?? ''); ?>"
                            required
                        >
                    </div>

                    <div class="col-12 col-md-4 mb-3">
                        <label class="form-label">Modelo</label>
                        <input 
                            type="text" 
                            name="modelo" 
                            class="form-control" 
                            value="<?php echo htmlspecialchars($form['modelo'] ?? ''); ?>"
                            required
                        >
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">Número de Série</label>
                        <input 
                            type="text" 
                            name="numero_serie" 
                            class="form-control" 
                            value="<?php echo htmlspecialchars($form['numero_serie'] ?? ''); ?>"
                            required
                        >
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">Fabricante</label>
                        <input 
                            type="text" 
                            name="fabricante" 
                            class="form-control" 
                            value="<?php echo htmlspecialchars($form['fabricante'] ?? ''); ?>"
                        >
                    </div>

                    <div class="col-12 col-md-4 mb-3">
                        <label class="form-label">Data de Aquisição</label>
                        <input 
                            type="date" 
                            name="data_aquisicao" 
                            class="form-control" 
                            value="<?php echo htmlspecialchars($form['data_aquisicao'] ?? ''); ?>"
                        >
                    </div>

                    <div class="col-12 col-md-4 mb-3">
                        <label class="form-label">Ano de Fabrico</label>
                        <input 
                            type="number" 
                            name="ano_fabrico" 
                            class="form-control" 
                            value="<?php echo htmlspecialchars($form['ano_fabrico'] ?? ''); ?>"
                        >
                    </div>

                    <div class="col-12 col-md-4 mb-3">
                        <label class="form-label">Custo (€)</label>
                        <input 
                            type="number" 
                            step="0.01"
                            name="custo_aquisicao" 
                            class="form-control" 
                            value="<?php echo htmlspecialchars($form['custo_aquisicao'] ?? ''); ?>"
                        >
                    </div>

                    <div class="col-12 col-md-4 mb-3">
                        <label class="form-label">Tipo de Entrada</label>
                        <select name="tipo_entrada_id" class="form-select" required>
                            <option value="">Selecione um tipo</option>
                            <?php foreach ($tipos_entrada as $tipo) : ?>
                                <option value="<?php echo $tipo->id; ?>" <?php echo (($form['tipo_entrada_id'] ?? '') == $tipo->id) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($tipo->nome); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12 col-md-4 mb-3">
                        <label class="form-label">Estado</label>
                        <select name="estado_id" class="form-select" required>
                            <option value="">Selecione um estado</option>
                            <?php foreach ($estados as $estado) : ?>
                                <option value="<?php echo $estado->id; ?>" <?php echo (($form['estado_id'] ?? '') == $estado->id) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($estado->nome); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12 col-md-4 mb-3">
                        <label class="form-label">Criticidade</label>
                        <select name="criticidade_id" class="form-select" required>
                            <option value="">Selecione uma criticidade</option>
                            <?php foreach ($criticidades as $criticidade) : ?>
                                <option value="<?php echo $criticidade->id; ?>" <?php echo (($form['criticidade_id'] ?? '') == $criticidade->id) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($criticidade->nome); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Observações</label>
                        <textarea class="form-control" name="observacoes" rows="3"><?php echo htmlspecialchars($form['observacoes'] ?? ''); ?></textarea>
                    </div>
                    
                    <!--Localização do equipamento-->
                    <div class="col-12 mt-4">
                        <h4><i class="fa-solid fa-location-dot me-2"></i>Localização</h4>
                        <hr>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Localização</label>
                        <select name="localizacao_id" class="form-select" required>
                            <option value="">Selecione uma localização</option>
                            <?php foreach ($localizacoes as $localizacao) : ?>
                                <option value="<?php echo $localizacao->id; ?>" <?php echo (($form['localizacao_id'] ?? '') == $localizacao->id) ? 'selected' : ''; ?>>
                                    <?php
                                    echo htmlspecialchars(
                                        $localizacao->edificio . ' | ' .
                                        $localizacao->piso . ' | ' .
                                        $localizacao->servico . ' | ' .
                                        $localizacao->sala
                                    );
                                    ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>


                    <!-- Fornecedores do equipamento -->
                    <div class="col-12 mt-4">
                        <h4>
                            <i class="fa-solid fa-handshake me-2"></i>Fornecedores
                        </h4>
                        <hr>
                    </div>

                    <?php foreach ($tipos_fornecedor as $tipo) : ?>
                        <div class="col-12 col-md-3 mb-3">
                            <label class="form-label">
                                <?php echo htmlspecialchars($tipo->nome); ?>
                            </label>

                            <select name="fornecedores[<?php echo $tipo->id; ?>]" class="form-select">
                                <option value="">Nenhum</option>

                                <?php foreach ($fornecedores as $fornecedor) : ?>
                                    <?php if ($fornecedor->tipo_fornecedor_id == $tipo->id) : ?>
                                        <option value="<?php echo $fornecedor->id; ?>" <?php echo (($form['fornecedores'][$tipo->id] ?? '') == $fornecedor->id) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($fornecedor->nome_empresa); ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endforeach; ?>


                    <!--Garantias do equipamento-->
                    <div class="col-12 mt-4">
                        <h4><i class="fa-solid fa-shield-halved me-2"></i>Garantia e Manutenção</h4>
                        <hr>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">Data início garantia</label>
                        <input 
                            type="date" 
                            name="inicio_garantia" 
                            class="form-control" 
                            value="<?php echo htmlspecialchars($form['inicio_garantia'] ?? ''); ?>"
                        >
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">Data fim garantia</label>
                        <input 
                            type="date" 
                            name="fim_garantia" 
                            class="form-control" 
                            value="<?php echo htmlspecialchars($form['fim_garantia'] ?? ''); ?>"
                        >
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">Contrato de manutenção</label>
                        <select name="tem_contrato_manutencao" class="form-select">
                            <option value="0" <?php echo (($form['tem_contrato_manutencao'] ?? '0') == '0') ? 'selected' : ''; ?>>
                                Não
                            </option>
                            <option value="1" <?php echo (($form['tem_contrato_manutencao'] ?? '') == '1') ? 'selected' : ''; ?>>
                                Sim
                            </option>
                        </select>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">Tipo contrato</label>
                        <select name="tipo_contrato" class="form-select">
                            <option value="" <?php echo (($form['tipo_contrato'] ?? '') === '') ? 'selected' : ''; ?>>
                                Nenhum
                            </option>
                            <option value="Preventivo" <?php echo (($form['tipo_contrato'] ?? '') === 'Preventivo') ? 'selected' : ''; ?>>
                                Preventivo
                            </option>
                            <option value="Corretivo" <?php echo (($form['tipo_contrato'] ?? '') === 'Corretivo') ? 'selected' : ''; ?>>
                                Corretivo
                            </option>
                            <option value="Preventivo + Corretivo" <?php echo (($form['tipo_contrato'] ?? '') === 'Preventivo + Corretivo') ? 'selected' : ''; ?>>
                                Preventivo + Corretivo
                            </option>
                        </select>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">Periodicidade</label>
                        <select name="periodicidade" class="form-select">
                            <option value="" <?php echo (($form['periodicidade'] ?? '') === '') ? 'selected' : ''; ?>>
                                Nenhuma
                            </option>
                            <option value="Mensal" <?php echo (($form['periodicidade'] ?? '') === 'Mensal') ? 'selected' : ''; ?>>
                                Mensal
                            </option>
                            <option value="Trimestral" <?php echo (($form['periodicidade'] ?? '') === 'Trimestral') ? 'selected' : ''; ?>>
                                Trimestral
                            </option>
                            <option value="Semestral" <?php echo (($form['periodicidade'] ?? '') === 'Semestral') ? 'selected' : ''; ?>>
                                Semestral
                            </option>
                            <option value="Anual" <?php echo (($form['periodicidade'] ?? '') === 'Anual') ? 'selected' : ''; ?>>
                                Anual
                            </option>
                        </select>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">Entidade responsável</label>
                        <input 
                            type="text" 
                            name="entidade_responsavel" 
                            class="form-control" 
                            value="<?php echo htmlspecialchars($form['entidade_responsavel'] ?? ''); ?>"
                        >
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Observações da garantia</label>
                        <textarea name="observacoes_garantia" class="form-control" rows="3"><?php echo htmlspecialchars($form['observacoes_garantia'] ?? ''); ?></textarea>
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
<script src="<?php echo BASE_URL; ?>/assets/bootstrap/bootstrap.bundle.min.js"></script>

</body>
</html>