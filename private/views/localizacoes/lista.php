<?php
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../../config/ligacao.php';

redirect_if_not_logged();

exigir_admin_ou_tecnico();

$menu_ativo = 'localizacoes';

$pesquisa = trim($_GET['pesquisa'] ?? '');

try {
    $sql = "
        SELECT 
            l.id, 
            l.edificio, 
            l.piso, 
            l.servico, 
            l.sala,
            l.ativo,
            COUNT(e.id) AS total_equipamentos
        FROM localizacoes l
        LEFT JOIN equipamentos e
            ON e.localizacao_id = l.id
        WHERE LOWER(CONCAT(l.edificio, ' ', l.piso, ' ', l.servico, ' ', l.sala)) LIKE LOWER(:pesquisa)
        GROUP BY
            l.id,
            l.edificio,
            l.piso,
            l.servico,
            l.sala,
            l.ativo
        ORDER BY l.edificio ASC, l.piso ASC, l.servico ASC, l.sala ASC
    ";

    $stmt = $ligacao->prepare($sql);
    $stmt->execute([
        'pesquisa' => '%' . $pesquisa . '%'
    ]);

    $localizacoes = $stmt->fetchAll(PDO::FETCH_OBJ);
    $erro = '';

} catch (PDOException $e) {
    $erro = 'Aconteceu um erro ao carregar as localizações.';
    $localizacoes = [];
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
                    <i class="fa-solid fa-location-dot me-2"></i> Localizações
                </h2>

                <div class="d-flex gap-2 flex-wrap">
                    <a href="exportar_csv.php" class="btn btn-save btn-sm">
                        <i class="fa-solid fa-file-csv me-1"></i>
                    </a>

                    <a href="novo.php" class="btn btn-save btn-sm">
                        <i class="fa-solid fa-plus me-1"></i>Nova Localização
                    </a>
                </div>
            </div>

            <hr>

            <!-- Pesquisa de localizações -->
            <div class="search-card mb-4">
                <h5>
                    <i class="fa-solid fa-magnifying-glass me-2"></i>Pesquisa
                </h5>

                <form method="get" class="search-row">
                    <input 
                        type="text" 
                        name="pesquisa"
                        class="form-control search-input" 
                        placeholder="Edifício, serviço, piso ou sala"
                        value="<?php echo htmlspecialchars($pesquisa); ?>"
                    >

                    <button class="btn btn-save search-btn" type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>

                    <a href="lista.php" class="btn btn-outline-secondary search-btn">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                </form>
            </div>

            <?php if (!empty($erro)) : ?>
                <p class="text-center text-danger">
                    <?php echo htmlspecialchars($erro); ?>
                </p>

            <?php elseif (count($localizacoes) === 0) : ?>
                <p class="text-muted">
                    Não existem localizações registadas.
                </p>

            <?php else : ?>
                <p class="text-muted">
                    Existem <?php echo count($localizacoes); ?> localizações registadas.
                </p>

                <div class="table-responsive">
                    <table class="table table-medctrl table-hover align-middle">

                        <thead>
                            <tr>
                                <th>Edifício</th>
                                <th>Piso</th>
                                <th>Serviço</th>
                                <th>Sala</th>
                                <th class="text-center">Equipamentos</th>
                                <th class="text-center">Situação</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($localizacoes as $localizacao) : ?>

                                <tr class="<?php echo ((int) $localizacao->ativo === 0) ? 'table-secondary' : ''; ?>">
                                    <td><?php echo htmlspecialchars($localizacao->edificio); ?></td>
                                    <td><?php echo htmlspecialchars($localizacao->piso); ?></td>
                                    <td><?php echo htmlspecialchars($localizacao->servico); ?></td>
                                    <td><?php echo htmlspecialchars($localizacao->sala); ?></td>

                                    <td class="text-center">
                                        <?php echo (int) $localizacao->total_equipamentos; ?>
                                    </td>

                                    <td class="text-center">
                                        <?php if ((int) $localizacao->ativo === 1) : ?>
                                            <span class="badge bg-success">Ativa</span>
                                        <?php else : ?>
                                            <span class="badge bg-danger">Inativa</span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center">
                                        <?php if ((int) $localizacao->ativo === 1) : ?>

                                            <a href="editar.php?id=<?php echo $localizacao->id; ?>" class="btn btn-edit-list btn-sm" title="Editar">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>

                                            <a href="apagar.php?id=<?php echo $localizacao->id; ?>" class="btn btn-delete-list btn-sm" title="Inativar">
                                                <i class="fa-solid fa-ban"></i>
                                            </a>

                                        <?php else : ?>

                                            <a href="apagar.php?id=<?php echo $localizacao->id; ?>" class="btn btn-success btn-sm" title="Reativar">
                                                <i class="fa-solid fa-rotate-left"></i>
                                            </a>

                                        <?php endif; ?>
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>
                </div>
            <?php endif; ?>

        </main>
    
    </div>
</div>


<!-- Bootstrap JS and custom JS --> 
<script src="<?php echo BASE_URL; ?>/assets/bootstrap/bootstrap.bundle.min.js"></script> 

</body>
</html>