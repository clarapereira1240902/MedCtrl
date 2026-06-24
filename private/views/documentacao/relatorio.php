<?php
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../../config/ligacao.php';

redirect_if_not_logged();
exigir_admin_ou_tecnico();

$pesquisa = trim($_GET['pesquisa'] ?? '');
$tipo_documento_id = $_GET['tipo_documento_id'] ?? '';
$validade = $_GET['validade'] ?? '';
$situacao = $_GET['situacao'] ?? '';

try {
    registar_log(
        $ligacao,
        'Gerou relatório de documentação',
        'documentos',
        null,
        'Relatório imprimível/exportável para PDF de documentação gerado.'
    );

    $sql = "
        SELECT
            d.nome,
            td.nome AS tipo_documento,
            e.codigo_inventario,
            e.designacao AS equipamento,
            f.nome_empresa AS fornecedor,
            d.data_documento,
            d.data_validade,
            d.ficheiro_link,
            d.observacoes,
            d.ativo
        FROM documentos d
        INNER JOIN tipos_documento td
            ON d.tipo_documento_id = td.id
        INNER JOIN equipamentos e
            ON d.equipamento_id = e.id
        LEFT JOIN fornecedores f
            ON d.fornecedor_id = f.id
        WHERE LOWER(CONCAT(
            d.nome, ' ',
            td.nome, ' ',
            e.codigo_inventario, ' ',
            e.designacao, ' ',
            IFNULL(f.nome_empresa, '')
        )) LIKE LOWER(:pesquisa)
        AND (:tipo_documento_id = '' OR d.tipo_documento_id = :tipo_documento_id)
        AND (:situacao = '' OR d.ativo = :situacao)
        AND (
            :validade = ''
            OR (:validade = 'valido' AND (d.data_validade IS NULL OR d.data_validade >= CURDATE()))
            OR (:validade = 'expirar' AND d.data_validade BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY))
            OR (:validade = 'expirado' AND d.data_validade < CURDATE())
        )
        ORDER BY d.nome ASC
    ";

    $stmt = $ligacao->prepare($sql);
    $stmt->execute([
        'pesquisa' => '%' . $pesquisa . '%',
        'tipo_documento_id' => $tipo_documento_id,
        'validade' => $validade,
        'situacao' => $situacao
    ]);

    $documentos = $stmt->fetchAll(PDO::FETCH_OBJ);

} catch (PDOException $e) {
    die('Erro ao gerar relatório de documentação.');
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Documentação</title>

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

    <h1>Relatório de Documentação</h1>

    <div class="info">
        Gerado em <?php echo date('d/m/Y H:i'); ?> |
        Total de documentos: <?php echo count($documentos); ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>Documento</th>
                <th>Tipo</th>
                <th>Equipamento</th>
                <th>Fornecedor</th>
                <th>Data Documento</th>
                <th>Validade</th>
                <th>Situação</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($documentos as $documento) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($documento->nome); ?></td>
                    <td><?php echo htmlspecialchars($documento->tipo_documento); ?></td>
                    <td>
                        <?php echo htmlspecialchars($documento->codigo_inventario); ?>
                        <br>
                        <?php echo htmlspecialchars($documento->equipamento); ?>
                    </td>
                    <td><?php echo htmlspecialchars($documento->fornecedor ?? 'Sem fornecedor'); ?></td>
                    <td><?php echo htmlspecialchars($documento->data_documento ?? '—'); ?></td>
                    <td><?php echo htmlspecialchars($documento->data_validade ?? 'Sem validade'); ?></td>
                    <td><?php echo ((int) $documento->ativo === 1) ? 'Ativo' : 'Inativo'; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>