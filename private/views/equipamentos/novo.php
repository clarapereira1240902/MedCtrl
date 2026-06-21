<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
$menu_ativo = 'equipamentos';

require_once __DIR__ . '/../../../config/ligacao.php';

$categorias = $ligacao->query("SELECT id, nome FROM categorias ORDER BY nome")->fetchAll(PDO::FETCH_OBJ);
$estados = $ligacao->query("SELECT id, nome FROM estados_equipamento ORDER BY nome")->fetchAll(PDO::FETCH_OBJ);
$criticidades = $ligacao->query("SELECT id, nome FROM criticidades ORDER BY nome")->fetchAll(PDO::FETCH_OBJ);
$tipos_entrada = $ligacao->query("SELECT id, nome FROM tipos_entrada ORDER BY nome")->fetchAll(PDO::FETCH_OBJ);
$localizacoes = $ligacao->query("SELECT id, edificio, piso, servico, sala FROM localizacoes ORDER BY edificio, piso, servico, sala")->fetchAll(PDO::FETCH_OBJ);
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


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {

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
                observacoes
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
                :observacoes
            )
        ";

        $stmt = $ligacao->prepare($sql_insert);

        $stmt->execute([
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
            'observacoes' => trim($_POST['observacoes'])
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
            'inicio_garantia' => $_POST['inicio_garantia'] ?: null,
            'fim_garantia' => $_POST['fim_garantia'] ?: null,
            'tem_contrato_manutencao' => (int) $_POST['tem_contrato_manutencao'],
            'tipo_contrato' => trim($_POST['tipo_contrato']),
            'entidade_responsavel' => trim($_POST['entidade_responsavel']),
            'periodicidade' => trim($_POST['periodicidade']),
            'observacoes' => trim($_POST['observacoes_garantia'])
        ]);

        header('Location: detalhes.php?id=' . $novo_id);
        exit;

    } catch (PDOException $e) {

        die('Erro ao criar equipamento.');

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

                <form class="form-medctrl" method="post">

                    <div class="row">
                        
                        <!--Informações gerais do equipamento-->
                        <div class="col-12 mb-3">
                            <h4><i class="fa-solid fa-circle-info me-2"></i>Informação Geral</h4>
                            <hr>
                        </div>

                        <div class="col-12 col-md-4 mb-3">
                            <label class="form-label">Código de Inventário</label>
                            <input type="text" class="form-control" name="codigo_inventario" placeholder="Ex: EQ001">
                        </div>

                        <div class="col-12 col-md-8 mb-3">
                            <label class="form-label">Designação</label>
                            <input type="text" name="designacao" class="form-control" placeholder="Ex: Monitor Multiparamétrico">
                        </div>

                        <div class="col-12 col-md-4 mb-3">
                            <label class="form-label">Categoria</label>
                            <select name="categoria_id" class="form-select" required>
                                <option value="">Selecione uma categoria</option>
                                <?php foreach ($categorias as $categoria) : ?>
                                    <option value="<?php echo $categoria->id; ?>">
                                        <?php echo htmlspecialchars($categoria->nome); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12 col-md-4 mb-3">
                            <label class="form-label">Marca</label>
                            <input type="text" name="marca" class="form-control">
                        </div>

                        <div class="col-12 col-md-4 mb-3">
                            <label class="form-label">Modelo</label>
                            <input type="text" name="modelo" class="form-control">
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label">Número de Série</label>
                            <input type="text" name="numero_serie" class="form-control">
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label">Fabricante</label>
                            <input type="text" name="fabricante" class="form-control">
                        </div>

                        <div class="col-12 col-md-4 mb-3">
                            <label class="form-label">Data de Aquisição</label>
                            <input type="date" name="data_aquisicao" class="form-control">
                        </div>

                        <div class="col-12 col-md-4 mb-3">
                            <label class="form-label">Ano de Fabrico</label>
                            <input type="number" name="ano_fabrico" class="form-control">
                        </div>

                        <div class="col-12 col-md-4 mb-3">
                            <label class="form-label">Custo (€)</label>
                            <input type="number" name="custo_aquisicao" class="form-control">
                        </div>

                        <div class="col-12 col-md-4 mb-3">
                            <label class="form-label">Tipo de Entrada</label>
                            <select name="tipo_entrada_id" class="form-select" required>
                                <option value="">Selecione um tipo</option>
                                <?php foreach ($tipos_entrada as $tipo) : ?>
                                    <option value="<?php echo $tipo->id; ?>">
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
                                    <option value="<?php echo $estado->id; ?>">
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
                                    <option value="<?php echo $criticidade->id; ?>">
                                        <?php echo htmlspecialchars($criticidade->nome); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Observações</label>
                            <textarea class="form-control" name="observacoes" rows="3"></textarea>
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
                                    <option value="<?php echo $localizacao->id; ?>">
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
                                            <option value="<?php echo $fornecedor->id; ?>">
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
                            <input type="date" name="inicio_garantia" class="form-control">
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label">Data fim garantia</label>
                            <input type="date" name="fim_garantia" class="form-control">
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label">Contrato de manutenção</label>
                            <select name="tem_contrato_manutencao" class="form-select">
                                <option value="0">Não</option>
                                <option value="1">Sim</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label">Tipo contrato</label>
                            <select name="tipo_contrato" class="form-select">
                                <option value="">Nenhum</option>
                                <option value="Preventivo">Preventivo</option>
                                <option value="Corretivo">Corretivo</option>
                                <option value="Preventivo + Corretivo">Preventivo + Corretivo</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label">Periodicidade</label>
                            <select name="periodicidade" class="form-select">
                                <option value="">Nenhuma</option>
                                <option value="Mensal">Mensal</option>
                                <option value="Trimestral">Trimestral</option>
                                <option value="Semestral">Semestral</option>
                                <option value="Anual">Anual</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label">Entidade responsável</label>
                            <input type="text" name="entidade_responsavel" class="form-control">
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Observações da garantia</label>
                            <textarea name="observacoes_garantia" class="form-control" rows="3"></textarea>
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