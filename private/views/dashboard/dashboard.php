<?php
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../../config/ligacao.php';

redirect_if_not_logged();

$menu_ativo = 'dashboard';

function dashboard_valor($ligacao, $sql, $params = []) {
    try {
        $stmt = $ligacao->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    } catch (PDOException $e) {
        return 0;
    }
}

function dashboard_linhas($ligacao, $sql, $params = []) {
    try {
        $stmt = $ligacao->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
        return [];
    }
}

// ===============================
// INDICADORES PRINCIPAIS
// ===============================

$total_equipamentos = dashboard_valor($ligacao, "
    SELECT COUNT(*)
    FROM equipamentos
");

$equipamentos_ativos = dashboard_valor($ligacao, "
    SELECT COUNT(*)
    FROM equipamentos
    WHERE ativo = 1
");

$equipamentos_inativos = dashboard_valor($ligacao, "
    SELECT COUNT(*)
    FROM equipamentos
    WHERE ativo = 0
");

$equipamentos_manutencao = dashboard_valor($ligacao, "
    SELECT COUNT(*)
    FROM equipamentos e
    INNER JOIN estados_equipamento ee
        ON e.estado_id = ee.id
    WHERE e.ativo = 1
    AND (
        LOWER(ee.nome) LIKE '%manutenção%'
        OR LOWER(ee.nome) LIKE '%manutencao%'
    )
");

// ===============================
// INDICADORES CRÍTICOS
// ===============================

$garantias_expiradas = dashboard_valor($ligacao, "
    SELECT COUNT(DISTINCT e.id)
    FROM equipamentos e
    INNER JOIN garantias_contratos gc
        ON gc.equipamento_id = e.id
    WHERE e.ativo = 1
    AND gc.data_fim < CURDATE()
");

$sem_documentacao = dashboard_valor($ligacao, "
    SELECT COUNT(*)
    FROM equipamentos e
    LEFT JOIN documentos d
        ON d.equipamento_id = e.id
        AND d.ativo = 1
    WHERE e.ativo = 1
    AND d.id IS NULL
");

$criticidade_alta = dashboard_valor($ligacao, "
    SELECT COUNT(*)
    FROM equipamentos e
    INNER JOIN criticidades c
        ON e.criticidade_id = c.id
    WHERE e.ativo = 1
    AND LOWER(c.nome) = 'alta'
");

$garantias_30_dias = dashboard_valor($ligacao, "
    SELECT COUNT(DISTINCT e.id)
    FROM equipamentos e
    INNER JOIN garantias_contratos gc
        ON gc.equipamento_id = e.id
    WHERE e.ativo = 1
    AND gc.data_fim BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)
");

$sem_localizacao = dashboard_valor($ligacao, "
    SELECT COUNT(*)
    FROM equipamentos
    WHERE ativo = 1
    AND localizacao_id IS NULL
");

// ===============================
// GRÁFICO: CRITICIDADE
// ===============================

$dados_criticidade = dashboard_linhas($ligacao, "
    SELECT
        c.nome,
        COUNT(e.id) AS total
    FROM equipamentos e
    INNER JOIN criticidades c
        ON e.criticidade_id = c.id
    WHERE e.ativo = 1
    GROUP BY c.id, c.nome
    ORDER BY c.id ASC
");

$labels_criticidade = [];
$valores_criticidade = [];

foreach ($dados_criticidade as $linha) {
    $labels_criticidade[] = $linha->nome;
    $valores_criticidade[] = (int) $linha->total;
}

// ===============================
// GRÁFICO: SUPORTE DE VIDA POR SERVIÇO
// ===============================

$dados_suporte_vida = dashboard_linhas($ligacao, "
    SELECT
        COALESCE(l.servico, 'Sem serviço') AS servico,
        COUNT(e.id) AS total
    FROM equipamentos e
    INNER JOIN criticidades c
        ON e.criticidade_id = c.id
    LEFT JOIN localizacoes l
        ON e.localizacao_id = l.id
    WHERE e.ativo = 1
    AND LOWER(c.nome) = 'suporte de vida'
    GROUP BY servico
    HAVING total > 0
    ORDER BY total DESC
    LIMIT 5
");

$labels_suporte_vida = [];
$valores_suporte_vida = [];

foreach ($dados_suporte_vida as $linha) {
    $labels_suporte_vida[] = $linha->servico;
    $valores_suporte_vida[] = (int) $linha->total;
}

// ===============================
// TABELA: EQUIPAMENTOS POR SERVIÇO
// ===============================

$equipamentos_por_servico = dashboard_linhas($ligacao, "
    SELECT
        COALESCE(l.servico, 'Sem serviço') AS servico,
        COUNT(e.id) AS total
    FROM equipamentos e
    LEFT JOIN localizacoes l
        ON e.localizacao_id = l.id
    WHERE e.ativo = 1
    GROUP BY servico
    HAVING total > 0
    ORDER BY total DESC
    LIMIT 5
");

// ===============================
// DISTRIBUIÇÃO POR CATEGORIA
// ===============================

$categorias = dashboard_linhas($ligacao, "
    SELECT
        c.nome,
        COUNT(e.id) AS total
    FROM categorias c
    INNER JOIN equipamentos e
        ON e.categoria_id = c.id
    WHERE e.ativo = 1
    GROUP BY c.id, c.nome
    HAVING total > 0
    ORDER BY total DESC
    LIMIT 4
");

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/navbar.php';
?>

<div class="container-fluid">
    <div class="row">

        <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

        <main class="col-lg-10 p-4">
            <div class="page-header">
                <h2>
                    <i class="fa-solid fa-chart-line me-2"></i>Dashboard
                </h2>
            </div>

            <hr>

            <div class="row g-3 mb-4">
                <div class="col-6 col-lg-3">
                    <div class="dashboard-card">
                        <div>
                            <span>Total de Equipamentos</span>
                            <h3><?php echo $total_equipamentos; ?></h3>
                        </div>
                        <i class="fa-solid fa-laptop-medical"></i>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="dashboard-card success">
                        <div>
                            <span>Ativos</span>
                            <h3><?php echo $equipamentos_ativos; ?></h3>
                        </div>
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="dashboard-card warning">
                        <div>
                            <span>Em Manutenção</span>
                            <h3><?php echo $equipamentos_manutencao; ?></h3>
                        </div>
                        <i class="fa-solid fa-screwdriver-wrench"></i>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="dashboard-card danger">
                        <div>
                            <span>Inativos</span>
                            <h3><?php echo $equipamentos_inativos; ?></h3>
                        </div>
                        <i class="fa-solid fa-circle-xmark"></i>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-6 col-lg-3">
                    <div class="dashboard-mini-card">
                        <span>Garantia expirada</span>
                        <strong><?php echo $garantias_expiradas; ?></strong>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="dashboard-mini-card">
                        <span>Sem documentação</span>
                        <strong><?php echo $sem_documentacao; ?></strong>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="dashboard-mini-card">
                        <span>Criticidade alta</span>
                        <strong><?php echo $criticidade_alta; ?></strong>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="dashboard-mini-card">
                        <span>Garantia termina em 30 dias</span>
                        <strong><?php echo $garantias_30_dias; ?></strong>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="card-medctrl">
                        <h4 class="mb-3">
                            <i class="fa-solid fa-bell me-2"></i>Alertas e Ações Rápidas
                        </h4>

                        <div class="accordion" id="accordionDashboard">

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseGarantias">
                                        <i class="fa-solid fa-triangle-exclamation me-2 text-danger"></i>Garantias expiradas
                                    </button>
                                </h2>

                                <div id="collapseGarantias" class="accordion-collapse collapse show" data-bs-parent="#accordionDashboard">
                                    <div class="accordion-body">
                                        Existem <strong><?php echo $garantias_expiradas; ?> equipamentos</strong> com garantia expirada.
                                        <br><br>
                                        <a href="../equipamentos/lista.php" class="btn btn-save btn-sm">
                                            Ver equipamentos
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDocumentacao">
                                        <i class="fa-solid fa-file-circle-xmark me-2 text-warning"></i>
                                        Equipamentos sem documentação associada
                                    </button>
                                </h2>

                                <div id="collapseDocumentacao" class="accordion-collapse collapse" data-bs-parent="#accordionDashboard">
                                    <div class="accordion-body">
                                        Existem <strong><?php echo $sem_documentacao; ?> equipamentos</strong> sem documentação técnica associada.
                                        <br><br>
                                        <a href="../documentacao/lista.php" class="btn btn-save btn-sm">
                                            Ver documentação
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLocalizacao">
                                        <i class="fa-solid fa-location-dot me-2 text-primary"></i>
                                        Equipamentos sem localização atribuída
                                    </button>
                                </h2>

                                <div id="collapseLocalizacao" class="accordion-collapse collapse" data-bs-parent="#accordionDashboard">
                                    <div class="accordion-body">
                                        Existem <strong><?php echo $sem_localizacao; ?> equipamentos</strong> sem localização definida no sistema.
                                        <br><br>
                                        <a href="../equipamentos/lista.php" class="btn btn-save btn-sm">
                                            Ver equipamentos
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-lg-6">
                    <div class="card-medctrl dashboard-chart-card">
                        <h5 class="dashboard-chart-title">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i>
                            Distribuição por Criticidade
                        </h5>
                        <div class="chart-container">
                            <canvas id="graficoCriticidade"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card-medctrl dashboard-chart-card">
                        <h5 class="dashboard-chart-title">
                            <i class="fa-solid fa-heart-pulse me-2"></i>
                            Suporte de Vida por Serviço
                        </h5>
                        <div class="chart-container">
                            <canvas id="graficoSuporteVida"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">

                <div class="col-lg-6">
                    <div class="card-medctrl">
                        <h4 class="mb-3">
                            <i class="fa-solid fa-building-user me-2"></i>Equipamentos por Serviço
                        </h4>

                        <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>Serviço</th>
                                    <th class="text-end">Nº Equipamentos</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if (count($equipamentos_por_servico) === 0) : ?>

                                    <tr>
                                        <td colspan="2" class="text-muted">
                                            Sem dados disponíveis.
                                        </td>
                                    </tr>

                                <?php else : ?>

                                    <?php foreach ($equipamentos_por_servico as $linha) : ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($linha->servico); ?></td>
                                            <td class="text-end"><?php echo (int) $linha->total; ?></td>
                                        </tr>
                                    <?php endforeach; ?>

                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card-medctrl">
                        <h4 class="mb-3">
                            <i class="fa-solid fa-chart-simple me-2"></i>
                            Distribuição por Categoria
                        </h4>

                        <?php if (count($categorias) === 0) : ?>

                            <p class="text-muted mb-0">
                                Sem dados disponíveis.
                            </p>

                        <?php else : ?>

                            <?php
                            $classes_progress = ['', 'bg-danger', 'bg-success', 'bg-warning', 'bg-info'];
                            $i = 0;
                            ?>

                            <?php foreach ($categorias as $categoria) : ?>
                                <?php
                                $percentagem = $equipamentos_ativos > 0
                                    ? round(((int) $categoria->total / $equipamentos_ativos) * 100)
                                    : 0;

                                $classe_progress = $classes_progress[$i] ?? '';
                                $i++;
                                ?>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span><?php echo htmlspecialchars($categoria->nome); ?></span>
                                        <strong><?php echo $percentagem; ?>%</strong>
                                    </div>

                                    <div class="progress">
                                        <div class="progress-bar <?php echo $classe_progress; ?>" style="width:<?php echo $percentagem; ?>%"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<script src="<?php echo BASE_URL; ?>/assets/bootstrap/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
window.dashboardDados = {
    criticidade: {
        labels: <?php echo json_encode($labels_criticidade, JSON_UNESCAPED_UNICODE); ?>,
        valores: <?php echo json_encode($valores_criticidade, JSON_NUMERIC_CHECK); ?>
    },
    suporteVida: {
        labels: <?php echo json_encode($labels_suporte_vida, JSON_UNESCAPED_UNICODE); ?>,
        valores: <?php echo json_encode($valores_suporte_vida, JSON_NUMERIC_CHECK); ?>
    }
};
</script>

<script src="<?php echo BASE_URL; ?>/assets/js/1240902.js?v=<?php echo time(); ?>"></script>

</body>
</html>