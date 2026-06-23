<?php
if (!isset($menu_ativo)) {
    $menu_ativo = '';
}

$eh_admin = utilizador_admin();
$pode_gerir = pode_gerir_dados();
?>

<div class="offcanvas offcanvas-start" tabindex="-1" id="menuMobile">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">MedCtrl</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body offcanvas-medctrl">
        <nav>
            <a href="<?php echo BASE_URL; ?>/private/views/dashboard/dashboard.php" class="<?php echo $menu_ativo == 'dashboard' ? 'active' : ''; ?>">
                <i class="fa-solid fa-chart-line"></i> &ensp; Dashboard
            </a>

            <a href="<?php echo BASE_URL; ?>/private/views/equipamentos/lista.php" class="<?php echo $menu_ativo == 'equipamentos' ? 'active' : ''; ?>">
                <i class="fa-solid fa-laptop-medical"></i> &ensp; Equipamentos
            </a>

            <?php if ($pode_gerir) : ?>
                <a href="<?php echo BASE_URL; ?>/private/views/localizacoes/lista.php" class="<?php echo $menu_ativo == 'localizacoes' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-location-dot"></i> &ensp; Localizações
                </a>

                <a href="<?php echo BASE_URL; ?>/private/views/fornecedores/lista.php" class="<?php echo $menu_ativo == 'fornecedores' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-handshake"></i> &ensp; Fornecedores
                </a>
            <?php endif; ?>

            <a href="<?php echo BASE_URL; ?>/private/views/documentacao/lista.php" class="<?php echo $menu_ativo == 'documentacao' ? 'active' : ''; ?>">
                <i class="fa-solid fa-file-medical"></i> &ensp; Documentação
            </a>

            <?php if ($eh_admin) : ?>
                <a href="<?php echo BASE_URL; ?>/private/views/conteudos/conteudos.php" class="<?php echo $menu_ativo == 'conteudos' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-pen-to-square"></i> &ensp; Conteúdos
                </a>

                <a href="<?php echo BASE_URL; ?>/private/views/mensagens/lista.php" class="<?php echo $menu_ativo == 'mensagens' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-envelope"></i> &ensp; Mensagens
                </a>
            <?php endif; ?>
        </nav>
    </div>
</div>

<aside class="col-lg-2 sidebar d-none d-lg-block">
    <nav>
        <a href="<?php echo BASE_URL; ?>/private/views/dashboard/dashboard.php" class="<?php echo $menu_ativo == 'dashboard' ? 'active' : ''; ?>">
            <i class="fa-solid fa-chart-line"></i> &ensp; Dashboard
        </a>

        <a href="<?php echo BASE_URL; ?>/private/views/equipamentos/lista.php" class="<?php echo $menu_ativo == 'equipamentos' ? 'active' : ''; ?>">
            <i class="fa-solid fa-laptop-medical"></i> &ensp; Equipamentos
        </a>

        <?php if ($pode_gerir) : ?>
            <a href="<?php echo BASE_URL; ?>/private/views/localizacoes/lista.php" class="<?php echo $menu_ativo == 'localizacoes' ? 'active' : ''; ?>">
                <i class="fa-solid fa-location-dot"></i> &ensp; Localizações
            </a>

            <a href="<?php echo BASE_URL; ?>/private/views/fornecedores/lista.php" class="<?php echo $menu_ativo == 'fornecedores' ? 'active' : ''; ?>">
                <i class="fa-solid fa-handshake"></i> &ensp; Fornecedores
            </a>
        <?php endif; ?>

        <a href="<?php echo BASE_URL; ?>/private/views/documentacao/lista.php" class="<?php echo $menu_ativo == 'documentacao' ? 'active' : ''; ?>">
            <i class="fa-solid fa-file-medical"></i> &ensp; Documentação
        </a>

        <?php if ($eh_admin) : ?>
            <a href="<?php echo BASE_URL; ?>/private/views/conteudos/conteudos.php" class="<?php echo $menu_ativo == 'conteudos' ? 'active' : ''; ?>">
                <i class="fa-solid fa-pen-to-square"></i> &ensp; Conteúdos
            </a>

            <a href="<?php echo BASE_URL; ?>/private/views/mensagens/lista.php" class="<?php echo $menu_ativo == 'mensagens' ? 'active' : ''; ?>">
                <i class="fa-solid fa-envelope"></i> &ensp; Mensagens
            </a>
        <?php endif; ?>
    </nav>
</aside>