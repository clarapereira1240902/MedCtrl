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
                    <?php echo $_SESSION['utilizador']['nome']; ?>
                </button>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="fa-solid fa-key me-2"></i>Alterar password
                        </a>
                    </li>
                    <li><hr></li>
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