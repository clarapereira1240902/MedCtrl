<?php
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../../config/ligacao.php';

redirect_if_not_logged();

$menu_ativo = 'fornecedores';

$pesquisa = trim($_GET['pesquisa'] ?? '');

try {
    $sql = "
        SELECT
            f.id,
            f.nome_empresa,
            f.nif,
            f.telefone,
            f.email,
            f.pessoa_contacto,
            tf.nome AS tipo_fornecedor,
            COUNT(ef.id) AS total_equipamentos
        FROM fornecedores f
        INNER JOIN tipos_fornecedor tf
            ON f.tipo_fornecedor_id = tf.id
        LEFT JOIN equipamento_fornecedor ef
            ON ef.fornecedor_id = f.id
        WHERE f.ativo = 1
        AND LOWER(CONCAT(
            f.nome_empresa, ' ',
            f.nif, ' ',
            IFNULL(f.email, ''), ' ',
            IFNULL(f.telefone, ''), ' ',
            IFNULL(f.pessoa_contacto, ''), ' ',
            tf.nome
        )) LIKE LOWER(:pesquisa)
        GROUP BY
            f.id,
            f.nome_empresa,
            f.nif,
            f.telefone,
            f.email,
            f.pessoa_contacto,
            tf.nome
        ORDER BY f.nome_empresa ASC
    ";

    $stmt = $ligacao->prepare($sql);
    $stmt->execute([
        'pesquisa' => '%' . $pesquisa . '%'
    ]);

    $fornecedores = $stmt->fetchAll(PDO::FETCH_OBJ);
    $erro = '';

} catch (PDOException $e) {
    $erro = 'Aconteceu um erro ao carregar os fornecedores.';
    $fornecedores = [];
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/navbar.php';
?>

<div class="container-fluid">
    <div class="row">

        <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

        <main class="col-lg-10 p-4">

            <div class="page-header">
                <h2>
                    <i class="fa-solid fa-handshake me-2"></i> Listagem de Fornecedores
                </h2>

                <a href="novo.php" class="btn btn-primary-custom btn-sm">
                    <i class="fa-solid fa-plus me-1"></i> Novo Fornecedor
                </a>
            </div>

            <hr>

            <div class="search-card mb-4">
                <h5>
                    <i class="fa-solid fa-magnifying-glass me-2"></i>Pesquisa
                </h5>

                <form method="get" class="search-row">
                    <input
                        type="text"
                        name="pesquisa"
                        class="form-control search-input"
                        placeholder="Nome da empresa, NIF, email ou tipo de fornecedor"
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

            <?php elseif (count($fornecedores) === 0) : ?>

                <p class="text-muted">
                    Não existem fornecedores registados.
                </p>

            <?php else : ?>

                <p class="text-muted">
                    Existem <?php echo count($fornecedores); ?> fornecedores registados.
                </p>

                <div class="table-responsive">
                    <table class="table table-hover align-middle table-medctrl">

                        <thead>
                            <tr>
                                <th>Empresa</th>
                                <th>Tipo</th>
                                <th>Pessoa Contacto</th>
                                <th>Telefone</th>
                                <th>Email</th>
                                <th class="text-center">Equipamentos</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($fornecedores as $fornecedor) : ?>

                                <tr>
                                    <td>
                                        <strong>
                                            <?php echo htmlspecialchars($fornecedor->nome_empresa); ?>
                                        </strong>
                                        <br>
                                        <small class="text-muted">
                                            NIF: <?php echo htmlspecialchars($fornecedor->nif); ?>
                                        </small>
                                    </td>

                                    <td>
                                        <?php
                                        $classeBadge = 'bg-secondary';

                                        switch ($fornecedor->tipo_fornecedor) {

                                            case 'Fabricante':
                                                $classeBadge = 'bg-primary';
                                                break;

                                            case 'Distribuidor':
                                                $classeBadge = 'bg-success';
                                                break;

                                            case 'Assistência Técnica':
                                                $classeBadge = 'bg-danger';
                                                break;

                                            case 'Consumíveis':
                                                $classeBadge = 'bg-warning';
                                                break;

                                            default:
                                                $classeBadge = 'bg-secondary';
                                        }
                                        ?>
                                        <span class="badge <?php echo $classeBadge; ?>">
                                            <?php echo htmlspecialchars($fornecedor->tipo_fornecedor); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($fornecedor->pessoa_contacto ?? '—'); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($fornecedor->telefone ?? '—'); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($fornecedor->email ?? '—'); ?>
                                    </td>

                                    <td class="text-center">
                                        <?php echo (int) $fornecedor->total_equipamentos; ?>
                                    </td>

                                    <td class="text-center">
                                        <a href="detalhes.php?id=<?php echo $fornecedor->id; ?>" class="btn btn-view-list btn-sm me-1">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>

                                        <a href="editar.php?id=<?php echo $fornecedor->id; ?>" class="btn btn-edit-list btn-sm me-1">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>

                                        <a href="apagar.php?id=<?php echo $fornecedor->id; ?>" class="btn btn-delete-list btn-sm">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
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

<script src="<?php echo BASE_URL; ?>/assets/bootstrap/bootstrap.bundle.min.js"></script>

</body>
</html>