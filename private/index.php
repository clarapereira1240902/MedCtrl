<?php
require_once __DIR__ . '/includes/funcoes.php';
redirect_if_not_logged();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedCtrl</title>

    <!-- favicon -->
    <link rel="shortcut icon" href="../assets/img/logo.png" type="image/png">

    <!-- Bootstrap CSS & custom CSS --> 
    <link rel="stylesheet" href="../assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/1240902_private.css">
    <link rel="stylesheet" href="../assets/css/1240902_components.css">

    <!-- Google Fonts --> 
    <link rel="preconnect" href="https://fonts.googleapis.com"> 
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> 
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,300;0,700;1,400&display=swap" rel="stylesheet"> 

    <!-- Font Awesome --> 
    <link rel="stylesheet" href="../assets/fontawesome/all.min.css"> 
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
                <img src="../assets/img/logo.png" height="40">
                <h3 class="ms-3 mb-0">MedCtrl</h3>
            </div>

            <div class="col-6 text-end">
                <div class="dropdown">
                    <button class="btn btn-user dropdown-toggle" data-bs-toggle="dropdown"> 
                        <i class="fa-regular fa-user me-2"></i>
                        <?php echo $_SESSION['utilizador']['nome']; ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="#"><i class="fa-solid fa-key me-2"></i>Alterar password</a>
                        </li>
                        <li><hr></li>
                        <li>
                            <a class="dropdown-item" href="logout.php"><i class="fa-solid fa-right-from-bracket me-2"></i>Terminar sessão</a>
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
                <a href="views/dashboard/dashboard.html">
                    <i class="fa-solid fa-chart-line"></i> &ensp; Dashboard
                </a>

                <a href="views/equipamentos/lista.html">
                    <i class="fa-solid fa-laptop-medical"></i> &ensp; Equipamentos
                </a>

                <a href="views/localizacoes/lista.html">
                    <i class="fa-solid fa-location-dot"></i> &ensp; Localizações
                </a>

                <a href="views/fornecedores/lista.html">
                    <i class="fa-solid fa-handshake"></i> &ensp; Fornecedores
                </a>

                <a href="views/documentacao/lista.html">
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
                    <a href="views/dashboard/dashboard.html">
                        <i class="fa-solid fa-chart-line"></i> &ensp; Dashboard
                    </a>
                    <a href="views/equipamentos/lista.html">
                        <i class="fa-solid fa-laptop-medical"></i> &ensp; Equipamentos
                    </a>
                    <a href="views/localizacoes/lista.html">
                        <i class="fa-solid fa-location-dot"></i> &ensp; Localizações
                    </a>
                    <a href="views/fornecedores/lista.html">
                        <i class="fa-solid fa-handshake"></i> &ensp; Fornecedores
                    </a>
                    <a href="views/documentacao/lista.html">
                        <i class="fa-solid fa-file-medical"></i> &ensp; Documentação
                    </a>
                    <a href="views/conteudos/conteudos.html">
                        <i class="fa-solid fa-pen-to-square"></i> &ensp; Conteúdos
                    </a>
                </nav>
            </aside>

            <!-- Conteúdo Principal -->
            <main class="col-lg-10 p-4">
                <section class="pagina-inicial">
                    <h2>MedCtrl - Área Privada</h2>
                    <p>Bem-vindo ao sistema de gestão de equipamentos médicos.</p>
                    <p> Escolha uma opção no menu lateral para continuar.</p>

                    <div class="icone-centro">
                        <i class="fa-solid fa-laptop-medical"></i>
                    </div>
                </section>
            </main>
        
        </div>
    </div>

    
<!-- Bootstrap JS and custom JS --> 
<script src="../assets/bootstrap/bootstrap.bundle.min.js"></script> 

</body>
</html>