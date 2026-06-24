<?php
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../../config/ligacao.php';

redirect_if_not_logged();
exigir_admin_ou_tecnico();

$pesquisa = trim($_GET['pesquisa'] ?? '');

try {
    registar_log(
        $ligacao,
        'Gerou relatório de fornecedores',
        'fornecedores',
        null,
        'Relatório imprimível/exportável para PDF de fornecedores gerado.'
    );

    $sql = "
        SELECT
            f.nome_empresa,
            f.nif,
            f.telefone,
            f.email,
            f.morada,
            f.website,
            f.pessoa_contacto,
            f.telefone_contacto,
            f.observacoes,
            f.ativo,
            tf.nome AS tipo_fornecedor,
            COUNT(ef.id) AS total_equipamentos
        FROM fornecedores f
        INNER JOIN tipos_fornecedor tf
            ON f.tipo_fornecedor_id = tf.id
        LEFT JOIN equipamento_fornecedor ef
            ON ef.fornecedor_id = f.id
        WHERE LOWER(CONCAT(
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
            f.morada,
            f.website,
            f.pessoa_contacto,
            f.telefone_contacto,
            f.observacoes,
            f.ativo,
            tf.nome
        ORDER BY f.nome_empresa ASC
    ";

    $stmt = $ligacao->prepare($sql);
    $stmt->execute([
        'pesquisa' => '%' . $pesquisa . '%'
    ]);

    $fornecedores = $stmt->fetchAll(PDO::FETCH_OBJ);

} catch (PDOException $e) {
    die('Erro ao gerar relatório de fornecedores.');
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Fornecedores</title>

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/bootstrap/bootstrap.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            padding: 30px;
        }

        h1 {
            font-size: 22px;
            margin-bottom: 5px;
        }

        .info {
            margin-bottom: 20px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f0f0f0;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 6px;
            vertical-align: top;
        }

        .no-print {
            margin-bottom: 20px;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                padding: 0;
            }
        }
    </style>
</head>

<body>

    <div class="no-print">
        <button onclick="window.print()" class="btn btn-primary btn-sm">
            Imprimir / Guardar PDF
        </button>

        <a href="lista.php" class="btn btn-secondary btn-sm">
            Voltar
        </a>
    </div>

    <h1>Relatório de Fornecedores</h1>

    <div class="info">
        Gerado em <?php echo date('d/m/Y H:i'); ?> |
        Total de fornecedores: <?php echo count($fornecedores); ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>Empresa</th>
                <th>NIF</th>
                <th>Tipo</th>
                <th>Contacto</th>
                <th>Email</th>
                <th>Pessoa de Contacto</th>
                <th>Equipamentos</th>
                <th>Situação</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($fornecedores as $fornecedor) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($fornecedor->nome_empresa); ?></td>
                    <td><?php echo htmlspecialchars($fornecedor->nif); ?></td>
                    <td><?php echo htmlspecialchars($fornecedor->tipo_fornecedor); ?></td>
                    <td><?php echo htmlspecialchars($fornecedor->telefone); ?></td>
                    <td><?php echo htmlspecialchars($fornecedor->email); ?></td>
                    <td>
                        <?php echo htmlspecialchars($fornecedor->pessoa_contacto ?? '—'); ?>
                        <?php if (!empty($fornecedor->telefone_contacto)) : ?>
                            <br>
                            <?php echo htmlspecialchars($fornecedor->telefone_contacto); ?>
                        <?php endif; ?>
                    </td>
                    <td><?php echo (int) $fornecedor->total_equipamentos; ?></td>
                    <td><?php echo ((int) $fornecedor->ativo === 1) ? 'Ativo' : 'Inativo'; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>