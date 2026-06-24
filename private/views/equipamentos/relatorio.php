<?php
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../../config/ligacao.php';

redirect_if_not_logged();
exigir_admin_ou_tecnico();

try {
    registar_log(
        $ligacao,
        'Gerou relatório de equipamentos',
        'equipamentos',
        null,
        'Relatório imprimível/exportável para PDF gerado.'
    );

    $sql = "
        SELECT
            e.codigo_inventario,
            e.designacao,
            e.marca,
            e.modelo,
            e.numero_serie,
            c.nome AS categoria,
            ee.nome AS estado,
            cr.nome AS criticidade,
            l.edificio,
            l.piso,
            l.servico,
            l.sala,
            CASE
                WHEN e.ativo = 1 THEN 'Ativo'
                ELSE 'Inativo'
            END AS situacao
        FROM equipamentos e
        INNER JOIN categorias c
            ON e.categoria_id = c.id
        INNER JOIN estados_equipamento ee
            ON e.estado_id = ee.id
        INNER JOIN criticidades cr
            ON e.criticidade_id = cr.id
        INNER JOIN localizacoes l
            ON e.localizacao_id = l.id
        ORDER BY e.codigo_inventario ASC
    ";

    $stmt = $ligacao->prepare($sql);
    $stmt->execute();

    $equipamentos = $stmt->fetchAll(PDO::FETCH_OBJ);

} catch (PDOException $e) {
    die('Erro ao gerar relatório.');
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Equipamentos</title>

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

    <h1>Relatório de Equipamentos</h1>

    <div class="info">
        Gerado em <?php echo date('d/m/Y H:i'); ?> |
        Total de equipamentos: <?php echo count($equipamentos); ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Designação</th>
                <th>Categoria</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Nº Série</th>
                <th>Estado</th>
                <th>Criticidade</th>
                <th>Localização</th>
                <th>Situação</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($equipamentos as $equipamento) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($equipamento->codigo_inventario); ?></td>
                    <td><?php echo htmlspecialchars($equipamento->designacao); ?></td>
                    <td><?php echo htmlspecialchars($equipamento->categoria); ?></td>
                    <td><?php echo htmlspecialchars($equipamento->marca); ?></td>
                    <td><?php echo htmlspecialchars($equipamento->modelo); ?></td>
                    <td><?php echo htmlspecialchars($equipamento->numero_serie); ?></td>
                    <td><?php echo htmlspecialchars($equipamento->estado); ?></td>
                    <td><?php echo htmlspecialchars($equipamento->criticidade); ?></td>
                    <td>
                        <?php echo htmlspecialchars($equipamento->edificio); ?>,
                        Piso <?php echo htmlspecialchars($equipamento->piso); ?>,
                        <?php echo htmlspecialchars($equipamento->servico); ?>,
                        Sala <?php echo htmlspecialchars($equipamento->sala); ?>
                    </td>
                    <td><?php echo htmlspecialchars($equipamento->situacao); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>