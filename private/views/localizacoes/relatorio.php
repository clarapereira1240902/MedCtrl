<?php
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../../config/ligacao.php';

redirect_if_not_logged();
exigir_admin_ou_tecnico();

$pesquisa = trim($_GET['pesquisa'] ?? '');

try {
    registar_log(
        $ligacao,
        'Gerou relatório de localizações',
        'localizacoes',
        null,
        'Relatório imprimível/exportável para PDF de localizações gerado.'
    );

    $sql = "
        SELECT 
            l.edificio, 
            l.piso, 
            l.servico, 
            l.sala,
            l.ativo,
            COUNT(e.id) AS total_equipamentos
        FROM localizacoes l
        LEFT JOIN equipamentos e
            ON e.localizacao_id = l.id
        WHERE LOWER(CONCAT(
            l.edificio, ' ',
            l.piso, ' ',
            l.servico, ' ',
            l.sala
        )) LIKE LOWER(:pesquisa)
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

} catch (PDOException $e) {
    die('Erro ao gerar relatório de localizações.');
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Localizações</title>

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

    <h1>Relatório de Localizações</h1>

    <div class="info">
        Gerado em <?php echo date('d/m/Y H:i'); ?> |
        Total de localizações: <?php echo count($localizacoes); ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>Edifício</th>
                <th>Piso</th>
                <th>Serviço</th>
                <th>Sala</th>
                <th>Total de Equipamentos</th>
                <th>Situação</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($localizacoes as $localizacao) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($localizacao->edificio); ?></td>
                    <td><?php echo htmlspecialchars($localizacao->piso); ?></td>
                    <td><?php echo htmlspecialchars($localizacao->servico); ?></td>
                    <td><?php echo htmlspecialchars($localizacao->sala); ?></td>
                    <td><?php echo (int) $localizacao->total_equipamentos; ?></td>
                    <td><?php echo ((int) $localizacao->ativo === 1) ? 'Ativa' : 'Inativa'; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>