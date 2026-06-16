<?php
require_once __DIR__ . '/includes/funcoes.php';
redirect_if_not_logged();

$menu_ativo = 'inicio';

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';
?>


    <div class="container-fluid">
        <div class="row">

            <?php include __DIR__ . '/includes/sidebar.php'; ?>

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
<script src="<?php echo BASE_URL; ?>/assets/bootstrap/bootstrap.bundle.min.js"></script>

</body>
</html>