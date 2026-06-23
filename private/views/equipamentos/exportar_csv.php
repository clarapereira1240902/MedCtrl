<?php
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../../config/ligacao.php';

redirect_if_not_logged();

try {
    registar_log(
        $ligacao,
        'Exportou equipamentos em CSV',
        'equipamentos',
        null,
        'Lista de equipamentos exportada em formato CSV.'
    );

    $sql = "
        SELECT
            e.codigo_inventario,
            e.designacao,
            e.marca,
            e.modelo,
            e.numero_serie,
            e.fabricante,
            c.nome AS categoria,
            ee.nome AS estado,
            cr.nome AS criticidade,
            l.edificio,
            l.piso,
            l.servico,
            l.sala,
            e.data_aquisicao,
            e.ano_fabrico,
            e.custo_aquisicao,
            CASE
                WHEN e.ativo = 1 THEN 'Ativo'
                ELSE 'Inativo'
            END AS situacao
        FROM equipamentos e
        LEFT JOIN categorias c
            ON e.categoria_id = c.id
        LEFT JOIN estados_equipamento ee
            ON e.estado_id = ee.id
        LEFT JOIN criticidades cr
            ON e.criticidade_id = cr.id
        LEFT JOIN localizacoes l
            ON e.localizacao_id = l.id
        ORDER BY e.codigo_inventario ASC
    ";

    $stmt = $ligacao->prepare($sql);
    $stmt->execute();

    $equipamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $nome_ficheiro = 'equipamentos_' . date('Y-m-d_H-i-s') . '.csv';

    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $nome_ficheiro . '"');
    header('Pragma: no-cache');
    header('Expires: 0');

    $ficheiro = fopen('php://output', 'w');

    fprintf($ficheiro, chr(0xEF) . chr(0xBB) . chr(0xBF));

    fputcsv($ficheiro, [
        'Código de Inventário',
        'Designação',
        'Marca',
        'Modelo',
        'Número de Série',
        'Fabricante',
        'Categoria',
        'Estado',
        'Criticidade',
        'Edifício',
        'Piso',
        'Serviço',
        'Sala',
        'Data de Aquisição',
        'Ano de Fabrico',
        'Custo de Aquisição',
        'Situação'
    ], ';');

    foreach ($equipamentos as $equipamento) {
        fputcsv($ficheiro, [
            $equipamento['codigo_inventario'],
            $equipamento['designacao'],
            $equipamento['marca'],
            $equipamento['modelo'],
            $equipamento['numero_serie'],
            $equipamento['fabricante'],
            $equipamento['categoria'],
            $equipamento['estado'],
            $equipamento['criticidade'],
            $equipamento['edificio'],
            $equipamento['piso'],
            $equipamento['servico'],
            $equipamento['sala'],
            $equipamento['data_aquisicao'],
            $equipamento['ano_fabrico'],
            $equipamento['custo_aquisicao'],
            $equipamento['situacao']
        ], ';');
    }

    fclose($ficheiro);
    exit;

} catch (PDOException $e) {
    die('Erro ao exportar equipamentos.');
}