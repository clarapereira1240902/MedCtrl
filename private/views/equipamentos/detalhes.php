<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

$menu_ativo = 'equipamentos';

require_once __DIR__ . '/../../../config/ligacao.php';

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: lista.php');
    exit;
}


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

        INNER JOIN categorias c
            ON e.categoria_id = c.id

        INNER JOIN estados_equipamento est
            ON e.estado_id = est.id

        INNER JOIN criticidades cri
            ON e.criticidade_id = cri.id

        INNER JOIN tipos_entrada te
            ON e.tipo_entrada_id = te.id

        INNER JOIN localizacoes l
            ON e.localizacao_id = l.id

        WHERE e.id = :id
        LIMIT 1
    ";

    $stmt = $ligacao->prepare($sql);
    $stmt->execute(['id' => $id]);

    $equipamento = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$equipamento) {
        header('Location: lista.php');
        exit;
    }

} catch(PDOException $e) {

    die('Erro ao carregar equipamento.');

}


function classe_estado($estado)
{
    $estado = mb_strtolower(trim($estado), 'UTF-8');

    if ($estado === 'operacional') return 'badge bg-success';
    if ($estado === 'em manutenção' || $estado === 'em manutencao') return 'badge bg-warning text-dark';
    if ($estado === 'inativo') return 'badge bg-danger';
    if ($estado === 'em calibração' || $estado === 'em calibracao') return 'badge bg-info text-dark';
    if ($estado === 'em quarentena') return 'badge bg-secondary';
    if ($estado === 'abatido') return 'badge bg-dark';

    return 'badge bg-secondary';
}

function classe_criticidade($criticidade)
{
    $criticidade = mb_strtolower(trim($criticidade), 'UTF-8');

    if ($criticidade === 'baixa') return 'badge bg-success';
    if ($criticidade === 'média' || $criticidade === 'media') return 'badge bg-warning text-dark';
    if ($criticidade === 'alta') return 'badge bg-danger';
    if ($criticidade === 'suporte de vida') return 'badge bg-dark';

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
                    <h2><i class="fa-solid fa-laptop-medical me-2"></i>Detalhes do Equipamento</h2>
                    <a href="lista.php" class="btn btn-cancel btn-sm">
                        <i class="fa-solid fa-arrow-left me-1"></i>Voltar
                    </a>
                </div>

                <!-- Cartão principal -->
                <div class="card-medctrl">
                    <div class="row">

                        <!-- Informação geral do equipamento -->
                        <div class="col-12 mb-3">
                            <h4><i class="fa-solid fa-circle-info me-2"></i>Informação Geral</h4>
                            <hr>
                        </div>

                        <!-- Coluna esquerda -->
                        <div class="col-12 col-md-6">
                            <div class="info-group">
                                <label>Código de Inventário</label>
                                <p><?php echo htmlspecialchars($equipamento->codigo_inventario); ?></p>
                            </div>

                            <div class="info-group">
                                <label>Designação</label>
                                <p><?php echo htmlspecialchars($equipamento->designacao); ?></p>
                            </div>

                            <div class="info-group">
                                <label>Categoria</label>
                                <p><?php echo htmlspecialchars($equipamento->categoria); ?></p>
                            </div>

                            <div class="info-group">
                                <label>Marca</label>
                                <p><?php echo htmlspecialchars($equipamento->marca); ?></p>
                            </div>

                            <div class="info-group">
                                <label>Modelo</label>
                                <p><?php echo htmlspecialchars($equipamento->modelo); ?></p>
                            </div>

                            <div class="info-group">
                                <label>Número de Série</label>
                                <p><?php echo htmlspecialchars($equipamento->numero_serie); ?></p>
                            </div>

                            <div class="info-group">
                                <label>Fabricante</label>
                                <p><?php echo htmlspecialchars($equipamento->fabricante); ?></p>
                            </div>
                        </div>

                        <!-- COLUNA DIREITA -->
                        <div class="col-12 col-md-6">
                            <div class="info-group">
                                <label>Data de Aquisição</label>
                                <p><?php echo htmlspecialchars($equipamento->data_aquisicao); ?></p>
                            </div>

                            <div class="info-group">
                                <label>Ano de Fabrico</label>
                                <p><?php echo htmlspecialchars($equipamento->ano_fabrico); ?></p>
                            </div>

                            <div class="info-group">
                                <label>Custo de Aquisição</label>
                                <p><?php echo htmlspecialchars($equipamento->custo_aquisicao); ?> €</p>
                            </div>

                            <div class="info-group">
                                <label>Tipo de Entrada</label>
                                <p>
                                    <span class="badge bg-primary">
                                        <?php echo htmlspecialchars($equipamento->tipo_entrada); ?>
                                    </span>
                                </p>
                            </div>

                            <div class="info-group">
                                <label>Estado Atual</label>
                                <p>
                                    <span class="<?php echo classe_estado($equipamento->estado); ?>">
                                        <?php echo htmlspecialchars($equipamento->estado); ?>
                                    </span>
                                </p>
                            </div>

                            <div class="info-group">
                                <label>Criticidade</label>
                                <p>
                                    <span class="<?php echo classe_criticidade($equipamento->criticidade); ?>">
                                        <?php echo htmlspecialchars($equipamento->criticidade); ?>
                                    </span>
                                </p>
                            </div>
                        </div>


                        <!-- Localização do equipamento -->
                        <div class="col-12 mt-4">
                            <h4><i class="fa-solid fa-location-dot me-2"></i>Localização</h4>
                            <hr>
                        </div>

                        <div class="col-12">
                            <div class="info-group">
                                <label>Localização atual</label>
                                <p>
    <?php
    echo htmlspecialchars(
        $equipamento->edificio . ' - ' .
        $equipamento->piso . ' - ' .
        $equipamento->servico . ' - ' .
        $equipamento->sala
    );
    ?>
</p>
                            </div>
                        </div>


                        <!-- Fornecedores do equipamento -->
                        <div class="col-12 mt-4">
                            <h4>
                                <i class="fa-solid fa-handshake me-2"></i>
                                Fornecedores
                            </h4>
                            <hr>
                        </div>

                        <div class="col-md-4">
                            <div class="info-group">
                                <label>Fabricante</label>
                                <p>Philips Healthcare</p>

                                <a href="../fornecedores/detalhes.php" class="btn btn-edit btn-sm mt-2">
                                    <i class="fa-solid fa-eye me-1"></i>
                                    Ver fornecedor
                                </a>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="info-group">
                                <label>Distribuidor</label>
                                <p>MedTech Solutions</p>

                                <a href="../fornecedores/detalhes.php" class="btn btn-edit btn-sm mt-2">
                                    <i class="fa-solid fa-eye me-1"></i>
                                    Ver fornecedor
                                </a>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="info-group">
                                <label>Assistência Técnica</label>
                                <p>TechRepair Medical</p>

                                <a href="../fornecedores/detalhes.php" class="btn btn-edit btn-sm mt-2">
                                    <i class="fa-solid fa-eye me-1"></i>
                                    Ver fornecedor
                                </a>
                            </div>
                        </div>


                        <!-- Garantia do equipamento-->
                        <div class="col-12 mt-4">
                            <h4><i class="fa-solid fa-shield-halved me-2"></i>Garantia e Manutenção</h4>
                            <hr>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="info-group">
                                <label>Início da garantia</label>
                                <p>2023-06-15</p>
                            </div>

                            <div class="info-group">
                                <label>Fim da garantia</label>
                                <p>2026-06-15</p>
                            </div>

                            <div class="info-group">
                                <label>Contrato manutenção</label>
                                <p>Sim</p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="info-group">
                                <label>Tipo de contrato</label>
                                <p>Preventivo + Corretivo</p>
                            </div>

                            <div class="info-group">
                                <label>Periodicidade</label>
                                <p>Semestral</p>
                            </div>

                            <div class="info-group">
                                <label>Entidade responsável</label>
                                <p>Philips Portugal</p>
                            </div>
                        </div>
                        

                        <!-- Documentação associada ao equipamento -->
                        <div class="col-12 mt-4">
                            <h4><i class="fa-solid fa-file-medical me-2"></i>Documentação Associada</h4>
                            <hr>
                        </div>

                        <div class="col-12">

                            <a href="../documentacao/detalhes.php" class="documento-card text-decoration-none d-block mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-1">Manual Monitor Philips MX450</h5>
                                        <span class="text-muted">Manual Técnico | Validade: 2027-03-10</span>
                                    </div>
                                    <div class="documento-icon">
                                        <i class="fa-solid fa-file-pdf"></i>
                                    </div>
                                </div>
                            </a>

                            <a href="../documentacao/detalhes.php" class="documento-card text-decoration-none d-block mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-1">Certificado de Calibração</h5>
                                        <span class="text-muted">Certificado | Validade: 2026-12-20</span>
                                    </div>
                                    <div class="documento-icon">
                                        <i class="fa-solid fa-file-lines"></i>
                                    </div>
                                </div>
                            </a>

                            <div class="mt-3">
                                <a href="../documentacao/novo.php" class="btn btn-save btn-sm">
                                    <i class="fa-solid fa-plus me-1"></i>Associar novo documento
                                </a>
                            </div>
                        
                        </div>
                
                        
                        <!-- Observações -->
                        <div class="mt-3">
                            <label>Observações</label>
                            <p class="obs-box">
                                Equipamento em excelente estado. Última manutenção realizada em Janeiro 2026.
                            </p>
                        </div>


                        <!-- Botões -->
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="editar.php?id=<?php echo $equipamento->id; ?>" class="btn btn-edit btn-sm"><i class="fa-solid fa-pen me-1"></i> Editar </a>
                            <a href="lista.php" class="btn btn-cancel btn-sm"> Cancelar</a>
                        </div>

                    </div>
                </div>
            </main>
        
        </div>
    </div>

    
<!-- Bootstrap JS and custom JS --> 
<script src="<?php echo BASE_URL; ?>/assets/bootstrap/bootstrap.bundle.min.js"></script>

</body>
</html>