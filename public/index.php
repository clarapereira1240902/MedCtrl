<?php
require_once __DIR__ . '/../config/ligacao.php';

date_default_timezone_set('Europe/Lisbon');

$campos_conteudo = [
    'objetivo' => [
        'titulo_principal' => 'Gestão Inteligente de Equipamentos Médicos',
        'texto_introdutorio' => "Organize e acompanhe todo o ciclo de vida dos equipamentos médicos numa única plataforma.\nSimplifique o inventário, melhore a rastreabilidade e tenha acesso rápido à informação essencial."
    ],

    'solucao' => [
        'titulo' => 'Solução',
        'subtitulo' => 'Funcionalidades do sistema',

        'funcionalidade_1_titulo' => 'Gestão de Equipamentos',
        'funcionalidade_1_texto' => 'Registo completo e estruturado',

        'funcionalidade_2_titulo' => 'Localização',
        'funcionalidade_2_texto' => 'Localização em tempo real no hospital',

        'funcionalidade_3_titulo' => 'Documentação',
        'funcionalidade_3_texto' => 'Acesso centralizado a documentos técnicos',

        'funcionalidade_4_titulo' => 'Fornecedores',
        'funcionalidade_4_texto' => 'Gestão integrada de fornecedores e contratos',

        'funcionalidade_5_titulo' => 'Pesquisa e Filtros Inteligentes',
        'funcionalidade_5_texto' => 'Acesso imediato à informação relevante',

        'funcionalidade_6_titulo' => 'Manutenção e Estado',
        'funcionalidade_6_texto' => 'Controlo do estado operacional dos equipamentos',

        'funcionalidade_7_titulo' => 'Gestão de Garantias e Contratos',
        'funcionalidade_7_texto' => 'Consulta de garantias e datas importantes',

        'funcionalidade_8_titulo' => 'Gestão de Utilizadores',
        'funcionalidade_8_texto' => 'Segurança na gestão da informação'
    ],

    'vantagens' => [
        'titulo' => 'Vantagens',
        'subtitulo' => 'Benefícios da solução',
        'beneficio_1' => 'Redução de erros',
        'beneficio_2' => 'Informação única e organizada',
        'beneficio_3' => 'Acesso rápido à informação',
        'beneficio_4' => 'Melhor controlo tecnológico',
        'beneficio_5' => 'Maior eficiência operacional',
        'area_1' => 'Hospitais',
        'area_2' => 'Clínicas',
        'area_3' => 'Centros de saúde',
        'area_4' => 'Laboratórios'
    ],

    'contacto' => [
        'titulo' => 'Fale Connosco',
        'texto' => 'Estamos disponíveis para esclarecer dúvidas sobre a plataforma MedCtrl e as suas funcionalidades.',
        'morada' => 'Rua de Cedofeita, nº 128',
        'codigo_postal' => '4050-173, Porto',
        'horario_semana' => '2ª a 6ª Feira: 9h — 17h',
        'horario_adicional' => 'Sábado e Feriados: 9h — 15h',
        'email' => 'info@medctrl.pt',
        'telefone' => '+351 912 345 678'
    ]
];

function obter_conteudo_publico($ligacao, $secao, $campo, $padrao) {
    try {
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

    } catch (PDOException $e) {
        return $padrao;
    }
}

function e($valor) {
    return htmlspecialchars($valor ?? '', ENT_QUOTES, 'UTF-8');
}

function texto_publico($valor) {
    return nl2br(e($valor));
}

$conteudos = [];

foreach ($campos_conteudo as $secao => $campos) {
    foreach ($campos as $campo => $padrao) {
        $conteudos[$secao][$campo] = obter_conteudo_publico($ligacao, $secao, $campo, $padrao);
    }
}

$erro_contacto = '';
$sucesso_contacto = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['formulario_contacto'] ?? '') === '1') {

    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mensagem = trim($_POST['mensagem'] ?? '');

    if ($nome === '' || $email === '' || $mensagem === '') {
        $erro_contacto = 'Preencha todos os campos do formulário.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro_contacto = 'Introduza um email válido.';
    } else {
        try {
            $stmt = $ligacao->prepare("
                INSERT INTO mensagens_contacto (
                    nome,
                    email,
                    mensagem,
                    data_envio,
                    lida
                ) VALUES (
                    :nome,
                    :email,
                    :mensagem,
                    :data_envio,
                    0
                )
            ");

            $stmt->execute([
                'nome' => $nome,
                'email' => $email,
                'mensagem' => $mensagem,
                'data_envio' => date('Y-m-d H:i:s')
            ]);

            $sucesso_contacto = 'A sua mensagem foi enviada com sucesso.';

        } catch (PDOException $e) {
            $erro_contacto = 'Não foi possível enviar a mensagem. Tente novamente mais tarde.';
        }
    }
}

$funcionalidades = [
    ['icone' => 'fa-box', 'titulo' => 'funcionalidade_1_titulo', 'texto' => 'funcionalidade_1_texto'],
    ['icone' => 'fa-location-dot', 'titulo' => 'funcionalidade_2_titulo', 'texto' => 'funcionalidade_2_texto'],
    ['icone' => 'fa-file-lines', 'titulo' => 'funcionalidade_3_titulo', 'texto' => 'funcionalidade_3_texto'],
    ['icone' => 'fa-handshake', 'titulo' => 'funcionalidade_4_titulo', 'texto' => 'funcionalidade_4_texto'],
    ['icone' => 'fa-magnifying-glass', 'titulo' => 'funcionalidade_5_titulo', 'texto' => 'funcionalidade_5_texto'],
    ['icone' => 'fa-screwdriver-wrench', 'titulo' => 'funcionalidade_6_titulo', 'texto' => 'funcionalidade_6_texto'],
    ['icone' => 'fa-clipboard-check', 'titulo' => 'funcionalidade_7_titulo', 'texto' => 'funcionalidade_7_texto'],
    ['icone' => 'fa-users', 'titulo' => 'funcionalidade_8_titulo', 'texto' => 'funcionalidade_8_texto']
];
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedCtrl</title>

    <link rel="shortcut icon" href="../assets/img/logo.png" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com"> 
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> 
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,300;0,700;1,400&display=swap" rel="stylesheet"> 

    <link rel="stylesheet" href="../assets/fontawesome/all.min.css">
    <link rel="stylesheet" href="../assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/1240902_public.css">
</head>

<body>

    <nav class="bng-navbar">
        <div>
            <img src="../assets/img/logo.png" alt="Logo da empresa">
            <h3>MedCtrl</h3>
        </div>

        <div class="container-navegacao">
            <a href="#objetivo">Objetivo</a>
            <a href="#solucao">Solução</a>
            <a href="#vantagens">Vantagens</a>
            <a href="#contacto">Contacto</a> 
        </div>

        <button class="btn-menu-public" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuPublic">
            <i class="fa-solid fa-bars"></i>
        </button>

        <div class="nav-cliente">
            <a href="login.php">Iniciar Sessão</a>
        </div>

        <div class="scroll-bar"></div>
    </nav>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="menuPublic">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">MedCtrl</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>

        <div class="offcanvas-body menu-public-body">
            <a href="#objetivo">Objetivo</a>
            <a href="#solucao">Solução</a>
            <a href="#vantagens">Vantagens</a>
            <a href="#contacto">Contacto</a>
            <a href="login.php" class="menu-public-login">Iniciar Sessão</a>
        </div>
    </div>

    <section class="container-texto-generico seccao-objetivo" id="objetivo"> 
        <div class="objetivo-content"> 
            <div class="objetivo-texto">
                <h1><?php echo e($conteudos['objetivo']['titulo_principal']); ?></h1>
                <p>
                    <?php echo texto_publico($conteudos['objetivo']['texto_introdutorio']); ?>
                </p>
            </div>

            <div class="botao-contactar">
                <a href="#contacto" class="button-saber-mais">Saber Mais</a>
            </div> 
        </div>
    </section>

    <section class="seccao-solucao" id="solucao">
        <h2><?php echo e($conteudos['solucao']['titulo']); ?></h2>
        <p class="subtitulo-solucao">
            <?php echo e($conteudos['solucao']['subtitulo']); ?>
        </p>

        <div class="solucao-container">
            <?php foreach ($funcionalidades as $funcionalidade) : ?>
                <div class="cartao-solucao">
                    <h3>
                        <i class="fa-solid <?php echo e($funcionalidade['icone']); ?>"></i>
                        <?php echo e($conteudos['solucao'][$funcionalidade['titulo']]); ?>
                    </h3>

                    <p>
                        <?php echo e($conteudos['solucao'][$funcionalidade['texto']]); ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="seccao-vantagens" id="vantagens">
        <h2><?php echo e($conteudos['vantagens']['titulo']); ?></h2>
        <p class="subtitulo">
            <?php echo e($conteudos['vantagens']['subtitulo']); ?>
        </p>

        <div class="vantagens-container">

            <div class="caixa-vantagens">
                <h3>Benefícios</h3>

                <?php for ($i = 1; $i <= 5; $i++) : ?>
                    <p>
                        <i class="fa-solid fa-check"></i>
                        <?php echo e($conteudos['vantagens']['beneficio_' . $i]); ?>
                    </p>
                <?php endfor; ?>
            </div>

            <div class="caixa-vantagens">
                <h3>Áreas de Aplicação</h3>

                <?php for ($i = 1; $i <= 4; $i++) : ?>
                    <p class="areas">
                        <?php echo e($conteudos['vantagens']['area_' . $i]); ?>
                    </p>
                <?php endfor; ?>
            </div>

        </div>
    </section>

    <section id="contacto"> 
        <h2><?php echo e($conteudos['contacto']['titulo']); ?></h2>

        <p>
            <?php echo texto_publico($conteudos['contacto']['texto']); ?>
        </p>

        <div class="contacto-container">

            <div class="contacto-info">
                <h3>Informações de Contacto</h3>

                <div class="contacto-item">
                    <i class="fa-solid fa-location-dot"></i>
                    <div>
                        <span><?php echo e($conteudos['contacto']['morada']); ?></span>
                        <span><?php echo e($conteudos['contacto']['codigo_postal']); ?></span>
                    </div>
                </div>

                <div class="contacto-item">
                    <i class="fa-solid fa-clock"></i>
                    <div>
                        <span><?php echo e($conteudos['contacto']['horario_semana']); ?></span>
                        <span><?php echo e($conteudos['contacto']['horario_adicional']); ?></span>
                    </div>
                </div>

                <div class="contacto-item">
                    <i class="fa-solid fa-envelope"></i>
                    <div>
                        <span><?php echo e($conteudos['contacto']['email']); ?></span>
                    </div>
                </div>

                <div class="contacto-item">
                    <i class="fa-solid fa-phone"></i>
                    <div>
                        <span><?php echo e($conteudos['contacto']['telefone']); ?></span>
                    </div>
                </div>
            </div>

            <form id="contactForm" method="post" action="#contacto">
                <input type="hidden" name="formulario_contacto" value="1">

                <?php if (!empty($sucesso_contacto)) : ?>
                    <div class="alert alert-success">
                        <?php echo e($sucesso_contacto); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($erro_contacto)) : ?>
                    <div class="alert alert-danger">
                        <?php echo e($erro_contacto); ?>
                    </div>
                <?php endif; ?>

                <label for="nome">Nome:</label> 
                <input type="text" id="nome" name="nome" required> 

                <label for="email">Email:</label> 
                <input type="email" id="email" name="email" required> 

                <label for="mensagem">Mensagem:</label> 
                <textarea id="mensagem" name="mensagem" rows="4" required></textarea> 

                <button type="submit">Enviar</button> 
            </form> 

        </div>

    </section>

<script>
window.addEventListener("scroll", () => {
    let scrollTop = document.documentElement.scrollTop;
    let height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
    let scrolled = (scrollTop / height) * 100;

    document.querySelector(".scroll-bar").style.width = scrolled + "%";
});
</script>

<script src="../assets/bootstrap/bootstrap.bundle.min.js"></script>

</body>
</html>