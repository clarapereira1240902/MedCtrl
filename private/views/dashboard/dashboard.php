<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedCtrl</title>

    <!-- favicon -->
    <link rel="shortcut icon" href="../../../assets/img/logo.png" type="image/png">

    <!-- Bootstrap CSS & custom CSS --> 
    <link rel="stylesheet" href="../../../assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="../../../assets/css/1240902_private.css">
    <link rel="stylesheet" href="../../../assets/css/1240902_components.css">

    <!-- Google Fonts --> 
    <link rel="preconnect" href="https://fonts.googleapis.com"> 
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> 
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,300;0,700;1,400&display=swap" rel="stylesheet"> 

    <!-- Font Awesome --> 
    <link rel="stylesheet" href="../../../assets/fontawesome/all.min.css"> 
</head>

<body>
    <!-- NAVBAR -->
    <header class="container-fluid navbar-medctrl">
        <div class="row align-items-center">
            <div class="col-6 d-flex align-items-center">
                <button class="btn btn-user d-lg-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuMobile">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <!-- Logo e Nome -->
                <img src="../../../assets/img/logo.png" height="40">
                <h3 class="ms-3 mb-0">MedCtrl</h3>
            </div>

            <div class="col-6 text-end">
                <div class="dropdown">
                    <button class="btn btn-user dropdown-toggle" data-bs-toggle="dropdown"> 
                        <i class="fa-regular fa-user me-2"></i>Utilizador
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="#"><i class="fa-solid fa-key me-2"></i>Alterar password</a>
                        </li>
                        <li><hr></li>
                        <li>
                            <a class="dropdown-item" href="../../../login.html"><i class="fa-solid fa-right-from-bracket me-2"></i>Terminar sessão</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <!-- MENU MOBILE / OFFCANVAS -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="menuMobile">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">
                <i class="fa-solid fa-laptop-medical me-2"></i>MedCtrl
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body offcanvas-medctrl">
            <nav>
                <a href="dashboard.html" class="active">
                    <i class="fa-solid fa-chart-line"></i> &ensp; Dashboard
                </a>

                <a href="../equipamentos/lista.html">
                    <i class="fa-solid fa-laptop-medical"></i> &ensp; Equipamentos
                </a>

                <a href="../localizacoes/lista.html">
                    <i class="fa-solid fa-location-dot"></i> &ensp; Localizações
                </a>

                <a href="../fornecedores/lista.html">
                    <i class="fa-solid fa-handshake"></i> &ensp; Fornecedores
                </a>

                <a href="../documentacao/lista.html">
                    <i class="fa-solid fa-file-medical"></i> &ensp; Documentação
                </a>
                <a href="../conteudos/conteudos.html">
                    <i class="fa-solid fa-pen-to-square"></i> &ensp; Conteúdos
                </a>
            </nav>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">

            <!-- SIDEBAR -->
            <aside class="col-lg-2 sidebar d-none d-lg-block">
                <nav>
                    <a href="dashboard.html" class="active">
                        <i class="fa-solid fa-chart-line"></i> &ensp; Dashboard
                    </a>
                    <a href="../equipamentos/lista.html">
                        <i class="fa-solid fa-laptop-medical"></i> &ensp; Equipamentos
                    </a>
                    <a href="../localizacoes/lista.html">
                        <i class="fa-solid fa-location-dot"></i> &ensp; Localizações
                    </a>
                    <a href="../fornecedores/lista.html">
                        <i class="fa-solid fa-handshake"></i> &ensp; Fornecedores
                    </a>
                    <a href="../documentacao/lista.html">
                        <i class="fa-solid fa-file-medical"></i> &ensp; Documentação
                    </a>
                    <a href="../conteudos/conteudos.html">
                        <i class="fa-solid fa-pen-to-square"></i> &ensp; Conteúdos
                    </a>
                </nav>
            </aside>

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
                                            <a href="../documentacao/lista.html" class="btn btn-save btn-sm">
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
                                            <a href="../localizacoes/lista.html" class="btn btn-save btn-sm">Ver localizações
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