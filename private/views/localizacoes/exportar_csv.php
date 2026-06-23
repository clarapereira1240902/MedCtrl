<?php
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../../config/ligacao.php';

redirect_if_not_logged();

exigir_admin_ou_tecnico();

$pesquisa = trim($_GET['pesquisa'] ?? '');

try {
    registar_log(
        $ligacao,
        'Exportou localizações em CSV',
        'localizacoes',
        null,
        'Lista de localizações exportada em formato CSV.'
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

    $localizacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $nome_ficheiro = 'localizacoes_' . date('Y-m-d_H-i-s') . '.csv';

    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $nome_ficheiro . '"');
    header('Pragma: no-cache');
    header('Expires: 0');

    $ficheiro = fopen('php://output', 'w');

    fprintf($ficheiro, chr(0xEF) . chr(0xBB) . chr(0xBF));

    fputcsv($ficheiro, [
        'Edifício',
        'Piso',
        'Serviço',
        'Sala',
        'Total de Equipamentos Associados',
        'Situação'
    ], ';');

    foreach ($localizacoes as $localizacao) {
        fputcsv($ficheiro, [
            $localizacao['edificio'],
            $localizacao['piso'],
            $localizacao['servico'],
            $localizacao['sala'],
            $localizacao['total_equipamentos'],
            ((int) $localizacao['ativo'] === 1) ? 'Ativa' : 'Inativa'
        ], ';');
    }

    fclose($ficheiro);
    exit;

} catch (PDOException $e) {
    die('Erro ao exportar localizações.');
}