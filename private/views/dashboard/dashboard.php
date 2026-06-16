<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

$menu_ativo = 'dashboard';

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/navbar.php';
?>


    <div class="container-fluid">
        <div class="row">

            <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

            <!-- CONTEÚDO PRINCIPAL -->
            <main class="col-lg-10 p-4">
                <div class="page-header">
                    <h2>
                        <i class="fa-solid fa-chart-line me-2"></i>Dashboard
                    </h2>
                </div>

                <hr>

                <!-- Indicadores principais -->
                <div class="row g-3 mb-4">
                    <div class="col-6 col-lg-3">
                        <div class="dashboard-card">
                            <div>
                                <span>Total de Equipamentos</span>
                                <h3>128</h3>
                            </div>
                            <i class="fa-solid fa-laptop-medical"></i>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="dashboard-card success">
                            <div>
                                <span>Ativos</span>
                                <h3>96</h3>
                            </div>
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="dashboard-card warning">
                            <div>
                                <span>Em Manutenção</span>
                                <h3>18</h3>
                            </div>
                            <i class="fa-solid fa-screwdriver-wrench"></i>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="dashboard-card danger">
                            <div>
                                <span>Inativos</span>
                                <h3>14</h3>
                            </div>
                            <i class="fa-solid fa-circle-xmark"></i>
                        </div>
                    </div>
                </div>

                <!-- Indicadores críticos -->
                <div class="row g-3 mb-4">
                    <div class="col-6 col-lg-3">
                        <div class="dashboard-mini-card">
                            <span>Garantia expirada</span><strong>9</strong>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="dashboard-mini-card">
                            <span>Sem documentação</span><strong>11</strong>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="dashboard-mini-card">
                            <span>Criticidade alta</span><strong>22</strong>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="dashboard-mini-card">
                            <span>Garantia termina em 30 dias</span><strong>6</strong>
                        </div>
                    </div>
                </div>
                
                <!-- Alertas e Ações rápidas -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card-medctrl">
                            <h4 class="mb-3">
                                <i class="fa-solid fa-bell me-2"></i>Alertas e Ações Rápidas
                            </h4>

                            <div class="accordion" id="accordionDashboard">

                                 <!-- Garantias expiradas -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseGarantias">
                                            <i class="fa-solid fa-triangle-exclamation me-2 text-danger"></i>Garantias expiradas
                                        </button>
                                    </h2>

                                    <div id="collapseGarantias" class="accordion-collapse collapse show" data-bs-parent="#accordionDashboard">
                                        <div class="accordion-body">
                                            Existem <strong>9 equipamentos</strong> com garantia expirada.
                                            <br><br>
                                            <a href="../equipamentos/lista.html" class="btn btn-save btn-sm">
                                                Ver equipamentos
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sem documentação -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDocumentacao">
                                            <i class="fa-solid fa-file-circle-xmark me-2 text-warning"></i>
                                            Equipamentos sem documentação associada
                                        </button>
                                    </h2>

                                    <div id="collapseDocumentacao" class="accordion-collapse collapse" data-bs-parent="#accordionDashboard">
                                        <div class="accordion-body">
                                            Existem <strong>11 equipamentos</strong> sem documentação técnica associada.
                                            <br><br>
                                            <a href="../documentacao/lista.php" class="btn btn-save btn-sm">
                                                Ver documentação
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sem localização -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLocalizacao">
                                            <i class="fa-solid fa-location-dot me-2 text-primary"></i>
                                            Equipamentos sem localização atribuída
                                        </button>
                                    </h2>

                                    <div id="collapseLocalizacao" class="accordion-collapse collapse" data-bs-parent="#accordionDashboard">
                                        <div class="accordion-body">
                                            Existem <strong>3 equipamentos</strong> sem localização definida no sistema.
                                            <br><br>
                                            <a href="../localizacoes/lista.php" class="btn btn-save btn-sm">Ver localizações
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gráficos -->
                <div class="row g-4 mb-4">
                    <!-- Criticidade -->
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

                    <!-- Suporte de Vida -->
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

                <!-- Tabelas com dados numéricos -->
                <div class="row g-4">

                    <!-- Equipamentos por serviço -->
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
                                    <tr>
                                        <td>Cardiologia</td>
                                        <td class="text-end">32</td>
                                    </tr>
                                    <tr>
                                        <td>Urgência</td>
                                        <td class="text-end">28</td>
                                    </tr>
                                    <tr>
                                        <td>UCI</td>
                                        <td class="text-end">21</td>
                                    </tr>
                                    <tr>
                                        <td>Radiologia</td>
                                        <td class="text-end">17</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Distribuição por categoria -->
                    <div class="col-lg-6">
                        <div class="card-medctrl">
                            <h4 class="mb-3">
                                <i class="fa-solid fa-chart-simple me-2"></i>
                                Distribuição por Categoria
                            </h4>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <span>Monitorização</span><strong>40%</strong>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" style="width:40%"></div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <span>Suporte de vida</span><strong>25%</strong>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-danger" style="width:25%"></div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <span>Terapia</span><strong>20%</strong>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-success" style="width:20%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="d-flex justify-content-between">
                                    <span>Diagnóstico</span><strong>15%</strong>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-warning" style="width:15%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

<!-- Bootstrap JS and custom JS --> 
<script src="../../../assets/bootstrap/bootstrap.bundle.min.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../../../assets/js/1240902.js"></script>

</body>
</html>