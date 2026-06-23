<?php
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../../config/ligacao.php';

redirect_if_not_logged();

exigir_admin_ou_tecnico();

$pesquisa = trim($_GET['pesquisa'] ?? '');

try {
    registar_log(
        $ligacao,
        'Exportou fornecedores em CSV',
        'fornecedores',
        null,
        'Lista de fornecedores exportada em formato CSV.'
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

    $fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $nome_ficheiro = 'fornecedores_' . date('Y-m-d_H-i-s') . '.csv';

    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $nome_ficheiro . '"');
    header('Pragma: no-cache');
    header('Expires: 0');

    $ficheiro = fopen('php://output', 'w');

    fprintf($ficheiro, chr(0xEF) . chr(0xBB) . chr(0xBF));

    fputcsv($ficheiro, [
        'Empresa',
        'NIF',
        'Tipo de Fornecedor',
        'Telefone',
        'Email',
        'Morada',
        'Website',
        'Pessoa de Contacto',
        'Telefone da Pessoa de Contacto',
        'Total de Equipamentos Associados',
        'Observações',
        'Situação'
    ], ';');

    foreach ($fornecedores as $fornecedor) {
        fputcsv($ficheiro, [
            $fornecedor['nome_empresa'],
            $fornecedor['nif'],
            $fornecedor['tipo_fornecedor'],
            $fornecedor['telefone'],
            $fornecedor['email'],
            $fornecedor['morada'],
            $fornecedor['website'],
            $fornecedor['pessoa_contacto'],
            $fornecedor['telefone_contacto'],
            $fornecedor['total_equipamentos'],
            $fornecedor['observacoes'],
            ((int) $fornecedor['ativo'] === 1) ? 'Ativo' : 'Inativo'
        ], ';');
    }

    fclose($ficheiro);
    exit;

} catch (PDOException $e) {
    die('Erro ao exportar fornecedores.');
}