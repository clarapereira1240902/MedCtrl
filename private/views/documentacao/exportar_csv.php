<?php
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../../config/ligacao.php';

redirect_if_not_logged();

$pesquisa = trim($_GET['pesquisa'] ?? '');
$tipo_documento_id = $_GET['tipo_documento_id'] ?? '';
$validade = $_GET['validade'] ?? '';
$situacao = $_GET['situacao'] ?? '';

try {
    registar_log(
        $ligacao,
        'Exportou documentação em CSV',
        'documentos',
        null,
        'Lista de documentos exportada em formato CSV.'
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

    $documentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $nome_ficheiro = 'documentacao_' . date('Y-m-d_H-i-s') . '.csv';

    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $nome_ficheiro . '"');
    header('Pragma: no-cache');
    header('Expires: 0');

    $ficheiro = fopen('php://output', 'w');

    fprintf($ficheiro, chr(0xEF) . chr(0xBB) . chr(0xBF));

    fputcsv($ficheiro, [
        'Documento',
        'Tipo de Documento',
        'Código do Equipamento',
        'Equipamento',
        'Fornecedor',
        'Data do Documento',
        'Data de Validade',
        'Link do Ficheiro',
        'Observações',
        'Situação'
    ], ';');

    foreach ($documentos as $documento) {
        fputcsv($ficheiro, [
            $documento['nome'],
            $documento['tipo_documento'],
            $documento['codigo_inventario'],
            $documento['equipamento'],
            $documento['fornecedor'] ?? 'Sem fornecedor',
            $documento['data_documento'],
            $documento['data_validade'],
            $documento['ficheiro_link'],
            $documento['observacoes'],
            ((int) $documento['ativo'] === 1) ? 'Ativo' : 'Inativo'
        ], ';');
    }

    fclose($ficheiro);
    exit;

} catch (PDOException $e) {
    die('Erro ao exportar documentação.');
}