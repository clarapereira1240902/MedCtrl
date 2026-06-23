<?php
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../../config/ligacao.php';

redirect_if_not_logged();
exigir_admin_ou_tecnico();

$pesquisa = trim($_GET['pesquisa'] ?? '');
$categoria_id = $_GET['categoria_id'] ?? '';
$estado_id = $_GET['estado_id'] ?? '';
$criticidade_id = $_GET['criticidade_id'] ?? '';
$servico = $_GET['servico'] ?? '';
$fornecedor_id = $_GET['fornecedor_id'] ?? '';
$situacao = $_GET['situacao'] ?? '';
$ordenar = $_GET['ordenar'] ?? 'codigo';

$ordenacoes_validas = [
    'codigo' => 'e.codigo_inventario ASC',
    'designacao' => 'e.designacao ASC',
    'marca' => 'e.marca ASC',
    'estado' => 'ee.nome ASC',
    'criticidade' => 'cr.nome ASC',
    'situacao' => 'e.ativo DESC, e.codigo_inventario ASC'
];

$order_by = $ordenacoes_validas[$ordenar] ?? $ordenacoes_validas['codigo'];

try {
    registar_log(
        $ligacao,
        'Exportou equipamentos em JSON',
        'equipamentos',
        null,
        'Lista de equipamentos exportada em formato JSON.'
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
            GROUP_CONCAT(DISTINCT f.nome_empresa ORDER BY f.nome_empresa SEPARATOR ', ') AS fornecedores,
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
        LEFT JOIN equipamento_fornecedor ef
            ON ef.equipamento_id = e.id
        LEFT JOIN fornecedores f
            ON ef.fornecedor_id = f.id
        WHERE (
            e.codigo_inventario LIKE :pesquisa
            OR e.designacao LIKE :pesquisa
            OR e.marca LIKE :pesquisa
            OR e.modelo LIKE :pesquisa
            OR e.numero_serie LIKE :pesquisa
        )
        AND (:categoria_id = '' OR e.categoria_id = :categoria_id)
        AND (:estado_id = '' OR e.estado_id = :estado_id)
        AND (:criticidade_id = '' OR e.criticidade_id = :criticidade_id)
        AND (:servico = '' OR l.servico = :servico)
        AND (:fornecedor_id = '' OR ef.fornecedor_id = :fornecedor_id)
        AND (:situacao = '' OR e.ativo = :situacao)
        GROUP BY
            e.id,
            e.codigo_inventario,
            e.designacao,
            e.marca,
            e.modelo,
            e.numero_serie,
            e.fabricante,
            c.nome,
            ee.nome,
            cr.nome,
            l.edificio,
            l.piso,
            l.servico,
            l.sala,
            e.data_aquisicao,
            e.ano_fabrico,
            e.custo_aquisicao,
            e.ativo
        ORDER BY $order_by
    ";

    $stmt = $ligacao->prepare($sql);
    $stmt->execute([
        'pesquisa' => '%' . $pesquisa . '%',
        'categoria_id' => $categoria_id,
        'estado_id' => $estado_id,
        'criticidade_id' => $criticidade_id,
        'servico' => $servico,
        'fornecedor_id' => $fornecedor_id,
        'situacao' => $situacao
    ]);

    $equipamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $nome_ficheiro = 'equipamentos_' . date('Y-m-d_H-i-s') . '.json';

    header('Content-Type: application/json; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $nome_ficheiro . '"');
    header('Pragma: no-cache');
    header('Expires: 0');

    echo json_encode($equipamentos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;

} catch (PDOException $e) {
    die('Erro ao exportar equipamentos em JSON.');
}