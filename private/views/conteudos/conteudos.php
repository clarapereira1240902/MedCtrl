<?php
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../../config/ligacao.php';

redirect_if_not_logged();

exigir_admin();

$menu_ativo = 'conteudos';

$mensagem_sucesso = '';
$erro_formulario = '';

$campos_conteudo = [
    'objetivo' => [
        'titulo_principal' => [
            'ordem' => 1,
            'padrao' => 'Gestão Inteligente de Equipamentos Médicos'
        ],
        'texto_introdutorio' => [
            'ordem' => 2,
            'padrao' => 'Organize e acompanhe todo o ciclo de vida dos equipamentos médicos numa única plataforma.
Simplifique o inventário, melhore a rastreabilidade e tenha acesso rápido à informação essencial.'
        ]
    ],

    'solucao' => [
        'titulo' => [
            'ordem' => 1,
            'padrao' => 'Solução'
        ],
        'subtitulo' => [
            'ordem' => 2,
            'padrao' => 'Funcionalidades do sistema'
        ],

        'funcionalidade_1_titulo' => ['ordem' => 3, 'padrao' => 'Gestão de Equipamentos'],
        'funcionalidade_1_texto' => ['ordem' => 4, 'padrao' => 'Registo completo e estruturado'],

        'funcionalidade_2_titulo' => ['ordem' => 5, 'padrao' => 'Localização'],
        'funcionalidade_2_texto' => ['ordem' => 6, 'padrao' => 'Localização em tempo real no hospital'],

        'funcionalidade_3_titulo' => ['ordem' => 7, 'padrao' => 'Documentação'],
        'funcionalidade_3_texto' => ['ordem' => 8, 'padrao' => 'Acesso centralizado a documentos técnicos'],

        'funcionalidade_4_titulo' => ['ordem' => 9, 'padrao' => 'Fornecedores'],
        'funcionalidade_4_texto' => ['ordem' => 10, 'padrao' => 'Gestão integrada de fornecedores e contratos'],

        'funcionalidade_5_titulo' => ['ordem' => 11, 'padrao' => 'Pesquisa e Filtros Inteligentes'],
        'funcionalidade_5_texto' => ['ordem' => 12, 'padrao' => 'Acesso imediato à informação relevante'],

        'funcionalidade_6_titulo' => ['ordem' => 13, 'padrao' => 'Manutenção e Estado'],
        'funcionalidade_6_texto' => ['ordem' => 14, 'padrao' => 'Controlo do estado operacional dos equipamentos'],

        'funcionalidade_7_titulo' => ['ordem' => 15, 'padrao' => 'Gestão de Garantias e Contratos'],
        'funcionalidade_7_texto' => ['ordem' => 16, 'padrao' => 'Consulta de garantias e datas importantes'],

        'funcionalidade_8_titulo' => ['ordem' => 17, 'padrao' => 'Gestão de Utilizadores'],
        'funcionalidade_8_texto' => ['ordem' => 18, 'padrao' => 'Segurança na gestão da informação']
    ],

    'vantagens' => [
        'titulo' => ['ordem' => 1, 'padrao' => 'Vantagens'],
        'subtitulo' => ['ordem' => 2, 'padrao' => 'Benefícios da solução'],

        'beneficio_1' => ['ordem' => 3, 'padrao' => 'Redução de erros'],
        'beneficio_2' => ['ordem' => 4, 'padrao' => 'Informação única e organizada'],
        'beneficio_3' => ['ordem' => 5, 'padrao' => 'Acesso rápido à informação'],
        'beneficio_4' => ['ordem' => 6, 'padrao' => 'Melhor controlo tecnológico'],
        'beneficio_5' => ['ordem' => 7, 'padrao' => 'Maior eficiência operacional'],

        'area_1' => ['ordem' => 8, 'padrao' => 'Hospitais'],
        'area_2' => ['ordem' => 9, 'padrao' => 'Clínicas'],
        'area_3' => ['ordem' => 10, 'padrao' => 'Centros de saúde'],
        'area_4' => ['ordem' => 11, 'padrao' => 'Laboratórios']
    ],

    'contacto' => [
        'titulo' => ['ordem' => 1, 'padrao' => 'Fale Connosco'],
        'texto' => ['ordem' => 2, 'padrao' => 'Estamos disponíveis para esclarecer dúvidas sobre a plataforma MedCtrl e as suas funcionalidades.'],
        'morada' => ['ordem' => 3, 'padrao' => 'Rua de Cedofeita, nº 128'],
        'codigo_postal' => ['ordem' => 4, 'padrao' => '4050-173, Porto'],
        'horario_semana' => ['ordem' => 5, 'padrao' => '2ª a 6ª Feira: 9h — 17h'],
        'horario_adicional' => ['ordem' => 6, 'padrao' => 'Sábado e Feriados: 9h — 15h'],
        'email' => ['ordem' => 7, 'padrao' => 'info@medctrl.pt'],
        'telefone' => ['ordem' => 8, 'padrao' => '+351 912 345 678']
    ]
];

function obter_valor_conteudo($ligacao, $secao, $campo, $padrao) {
    $stmt = $ligacao->prepare("
        SELECT conteudo
        FROM conteudos_publicos
        WHERE secao = :secao
        AND campo = :campo
        AND ativo = 1
        ORDER BY ordem ASC, id ASC
        LIMIT 1
    ");

    $stmt->execute([
        'secao' => $secao,
        'campo' => $campo
    ]);

    $valor = $stmt->fetchColumn();

    return $valor !== false ? $valor : $padrao;
}

function guardar_valor_conteudo($ligacao, $secao, $campo, $conteudo, $ordem) {
    $stmt = $ligacao->prepare("
        SELECT id
        FROM conteudos_publicos
        WHERE secao = :secao
        AND campo = :campo
        LIMIT 1
    ");

    $stmt->execute([
        'secao' => $secao,
        'campo' => $campo
    ]);

    $id = $stmt->fetchColumn();

    if ($id) {
        $stmt_update = $ligacao->prepare("
            UPDATE conteudos_publicos
            SET
                conteudo = :conteudo,
                ordem = :ordem,
                ativo = 1
            WHERE id = :id
        ");

        $stmt_update->execute([
            'conteudo' => $conteudo,
            'ordem' => $ordem,
            'id' => $id
        ]);

    } else {
        $stmt_insert = $ligacao->prepare("
            INSERT INTO conteudos_publicos (
                secao,
                campo,
                titulo,
                conteudo,
                ordem,
                ativo
            ) VALUES (
                :secao,
                :campo,
                NULL,
                :conteudo,
                :ordem,
                1
            )
        ");

        $stmt_insert->execute([
            'secao' => $secao,
            'campo' => $campo,
            'conteudo' => $conteudo,
            'ordem' => $ordem
        ]);
    }
}

$valores = [];

foreach ($campos_conteudo as $secao => $campos) {
    foreach ($campos as $campo => $dados) {
        $valores[$secao][$campo] = obter_valor_conteudo(
            $ligacao,
            $secao,
            $campo,
            $dados['padrao']
        );
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $dados_post = $_POST['conteudos'] ?? [];
    $novos_valores = [];

    foreach ($campos_conteudo as $secao => $campos) {
        foreach ($campos as $campo => $dados) {
            $valor = trim($dados_post[$secao][$campo] ?? '');

            if ($valor === '') {
                $erro_formulario = 'Todos os campos são obrigatórios.';
                break 2;
            }

            $novos_valores[$secao][$campo] = $valor;
        }
    }

    if (empty($erro_formulario)) {
        $email_contacto = $novos_valores['contacto']['email'] ?? '';

        if (!filter_var($email_contacto, FILTER_VALIDATE_EMAIL)) {
            $erro_formulario = 'O email de contacto não é válido.';
        }
    }

    if (empty($erro_formulario)) {
        $telefone_contacto = $novos_valores['contacto']['telefone'] ?? '';

        if (!preg_match('/^[0-9+\s()-]{6,30}$/', $telefone_contacto)) {
            $erro_formulario = 'O telefone de contacto não é válido.';
        }
    }

    if (empty($erro_formulario)) {
        try {
            $ligacao->beginTransaction();

            foreach ($campos_conteudo as $secao => $campos) {
                foreach ($campos as $campo => $dados) {
                    guardar_valor_conteudo(
                        $ligacao,
                        $secao,
                        $campo,
                        $novos_valores[$secao][$campo],
                        $dados['ordem']
                    );
                }
            }

            $ligacao->commit();

            registar_log(
                $ligacao,
                'Atualizou conteúdos públicos',
                'conteudos_publicos',
                null,
                'Conteúdos da área pública do website atualizados.'
            );

            $valores = $novos_valores;
            $mensagem_sucesso = 'Conteúdos atualizados com sucesso.';

        } catch (PDOException $e) {
            if ($ligacao->inTransaction()) {
                $ligacao->rollBack();
            }

            $erro_formulario = 'Erro ao guardar os conteúdos.';
        }
    } else {
        $valores = $novos_valores + $valores;
    }
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
                    <i class="fa-solid fa-pen-to-square me-2"></i>Gestão de Conteúdos
                </h2>
            </div>

            <hr>

            <div class="search-card mb-4">
                <h5>
                    <i class="fa-solid fa-globe me-2"></i>Área Pública do Website
                </h5>

                <p class="text-muted mb-0">
                    Atualize os textos e informações apresentados na página pública da MedCtrl.
                </p>
            </div>

            <?php if (!empty($mensagem_sucesso)) : ?>
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check me-1"></i>
                    <?php echo htmlspecialchars($mensagem_sucesso); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($erro_formulario)) : ?>
                <div class="alert alert-danger">
                    <i class="fa-solid fa-circle-exclamation me-1"></i>
                    <?php echo htmlspecialchars($erro_formulario); ?>
                </div>
            <?php endif; ?>

            <form method="post">

                <div class="accordion" id="accordionConteudos">

                    <div class="accordion-item mb-3">
                        <h2 class="accordion-header" id="headingObjetivo">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseObjetivo">
                                <i class="fa-solid fa-bullseye me-2"></i> Secção Objetivo
                            </button>
                        </h2>

                        <div id="collapseObjetivo" class="accordion-collapse collapse show" data-bs-parent="#accordionConteudos">
                            <div class="accordion-body">
                                <div class="form-medctrl">
                                    <div class="mb-3">
                                        <label class="form-label">Título principal</label>
                                        <input type="text" name="conteudos[objetivo][titulo_principal]" class="form-control" value="<?php echo htmlspecialchars($valores['objetivo']['titulo_principal'] ?? ''); ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Texto introdutório</label>
                                        <textarea name="conteudos[objetivo][texto_introdutorio]" class="form-control" rows="4" required><?php echo htmlspecialchars($valores['objetivo']['texto_introdutorio'] ?? ''); ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item mb-3">
                        <h2 class="accordion-header" id="headingSolucao">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSolucao">
                                <i class="fa-solid fa-puzzle-piece me-2"></i> Secção Solução
                            </button>
                        </h2>

                        <div id="collapseSolucao" class="accordion-collapse collapse" data-bs-parent="#accordionConteudos">
                            <div class="accordion-body">
                                <div class="form-medctrl">
                                    <div class="row g-3">
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Título da secção</label>
                                            <input type="text" name="conteudos[solucao][titulo]" class="form-control" value="<?php echo htmlspecialchars($valores['solucao']['titulo'] ?? ''); ?>" required>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Subtítulo</label>
                                            <input type="text" name="conteudos[solucao][subtitulo]" class="form-control" value="<?php echo htmlspecialchars($valores['solucao']['subtitulo'] ?? ''); ?>" required>
                                        </div>

                                        <?php for ($i = 1; $i <= 8; $i++) : ?>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label">Funcionalidade <?php echo $i; ?> - Título</label>
                                                <input type="text" name="conteudos[solucao][funcionalidade_<?php echo $i; ?>_titulo]" class="form-control" value="<?php echo htmlspecialchars($valores['solucao']['funcionalidade_' . $i . '_titulo'] ?? ''); ?>" required>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label class="form-label">Funcionalidade <?php echo $i; ?> - Texto</label>
                                                <input type="text" name="conteudos[solucao][funcionalidade_<?php echo $i; ?>_texto]" class="form-control" value="<?php echo htmlspecialchars($valores['solucao']['funcionalidade_' . $i . '_texto'] ?? ''); ?>" required>
                                            </div>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item mb-3">
                        <h2 class="accordion-header" id="headingVantagens">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVantagens">
                                <i class="fa-solid fa-star me-2"></i> Secção Vantagens
                            </button>
                        </h2>

                        <div id="collapseVantagens" class="accordion-collapse collapse" data-bs-parent="#accordionConteudos">
                            <div class="accordion-body">
                                <div class="form-medctrl">
                                    <div class="row g-3">
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Título da secção</label>
                                            <input type="text" name="conteudos[vantagens][titulo]" class="form-control" value="<?php echo htmlspecialchars($valores['vantagens']['titulo'] ?? ''); ?>" required>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Subtítulo</label>
                                            <input type="text" name="conteudos[vantagens][subtitulo]" class="form-control" value="<?php echo htmlspecialchars($valores['vantagens']['subtitulo'] ?? ''); ?>" required>
                                        </div>

                                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label">Benefício <?php echo $i; ?></label>
                                                <input type="text" name="conteudos[vantagens][beneficio_<?php echo $i; ?>]" class="form-control" value="<?php echo htmlspecialchars($valores['vantagens']['beneficio_' . $i] ?? ''); ?>" required>
                                            </div>
                                        <?php endfor; ?>

                                        <?php for ($i = 1; $i <= 4; $i++) : ?>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label">Área de aplicação <?php echo $i; ?></label>
                                                <input type="text" name="conteudos[vantagens][area_<?php echo $i; ?>]" class="form-control" value="<?php echo htmlspecialchars($valores['vantagens']['area_' . $i] ?? ''); ?>" required>
                                            </div>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item mb-3">
                        <h2 class="accordion-header" id="headingContactos">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseContactos">
                                <i class="fa-solid fa-address-book me-2"></i> Secção Contactos
                            </button>
                        </h2>

                        <div id="collapseContactos" class="accordion-collapse collapse" data-bs-parent="#accordionConteudos">
                            <div class="accordion-body">
                                <div class="form-medctrl">
                                    <div class="mb-3">
                                        <label class="form-label">Título da secção</label>
                                        <input type="text" name="conteudos[contacto][titulo]" class="form-control" value="<?php echo htmlspecialchars($valores['contacto']['titulo'] ?? ''); ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Texto introdutório</label>
                                        <textarea name="conteudos[contacto][texto]" class="form-control" rows="3" required><?php echo htmlspecialchars($valores['contacto']['texto'] ?? ''); ?></textarea>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Morada</label>
                                            <input type="text" name="conteudos[contacto][morada]" class="form-control" value="<?php echo htmlspecialchars($valores['contacto']['morada'] ?? ''); ?>" required>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Código postal e localidade</label>
                                            <input type="text" name="conteudos[contacto][codigo_postal]" class="form-control" value="<?php echo htmlspecialchars($valores['contacto']['codigo_postal'] ?? ''); ?>" required>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Horário semanal</label>
                                            <input type="text" name="conteudos[contacto][horario_semana]" class="form-control" value="<?php echo htmlspecialchars($valores['contacto']['horario_semana'] ?? ''); ?>" required>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Horário adicional</label>
                                            <input type="text" name="conteudos[contacto][horario_adicional]" class="form-control" value="<?php echo htmlspecialchars($valores['contacto']['horario_adicional'] ?? ''); ?>" required>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="conteudos[contacto][email]" class="form-control" value="<?php echo htmlspecialchars($valores['contacto']['email'] ?? ''); ?>" required>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Telefone</label>
                                            <input type="text" name="conteudos[contacto][telefone]" class="form-control" value="<?php echo htmlspecialchars($valores['contacto']['telefone'] ?? ''); ?>" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 mb-4">
                    <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="btn btn-cancel">
                        <i class="fa-solid fa-xmark me-1"></i>Cancelar
                    </a>

                    <button type="submit" class="btn btn-save">
                        <i class="fa-solid fa-floppy-disk me-1"></i>Guardar alterações
                    </button>
                </div>

            </form>

        </main>
    
    </div>
</div>

<script src="<?php echo BASE_URL; ?>/assets/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>