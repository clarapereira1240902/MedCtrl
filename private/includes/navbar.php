<header class="container-fluid navbar-medctrl">
    <div class="row align-items-center">
        <div class="col-6 d-flex align-items-center">
            <button class="btn btn-user d-lg-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuMobile">
                <i class="fa-solid fa-bars"></i>
            </button>

            <img src="<?php echo BASE_URL; ?>/assets/img/logo.png" height="40">
            <h3 class="ms-3 mb-0">MedCtrl</h3>
        </div>

        <div class="col-6 text-end">
            <div class="dropdown">
                <button class="btn btn-user dropdown-toggle" data-bs-toggle="dropdown"> 
                    <i class="fa-regular fa-user me-2"></i>
                    <?php echo htmlspecialchars($_SESSION['utilizador']['nome'] ?? 'Utilizador'); ?>
                </button>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li class="px-3 py-2">
                        <strong>
                            <?php echo htmlspecialchars($_SESSION['utilizador']['nome'] ?? 'Utilizador'); ?>
                        </strong>
                        <br>
                        <small class="text-muted">
                            <?php echo htmlspecialchars($_SESSION['utilizador']['perfil_nome'] ?? 'Perfil'); ?>
                        </small>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>
                        <a class="dropdown-item" href="<?php echo BASE_URL; ?>/private/logout.php">
                            <i class="fa-solid fa-right-from-bracket me-2"></i>Terminar sessão
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>