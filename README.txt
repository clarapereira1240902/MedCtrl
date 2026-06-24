# MedCtrl #

## Descrição do projeto:

O **MedCtrl** é uma aplicação web desenvolvida para apoiar a gestão de equipamentos biomédicos numa unidade de saúde.
A aplicação permite organizar informação relacionada com equipamentos, fornecedores, localizações, documentação técnica, garantias/contratos e mensagens recebidas através da área pública. O sistema inclui uma área pública acessível a qualquer utilizador e uma área privada protegida por autenticação.
Na área privada existem diferentes perfis de utilizador, permitindo controlar o acesso às funcionalidades de acordo com o tipo de utilizador autenticado.


## Tecnologias utilizadas:
* PHP
* MySQL
* PDO
* HTML
* CSS
* Bootstrap
* FontAwesome
* HeidiSQL


## Funcionalidades principais:
* Área pública com apresentação do sistema.
* Formulário de contacto público.
* Área privada protegida por login.
* Gestão de equipamentos biomédicos.
* Gestão de fornecedores.
* Gestão de localizações.
* Gestão de documentação técnica.
* Upload de ficheiros de documentação.
* Gestão de conteúdos públicos.
* Gestão de mensagens de contacto.
* Dashboard com indicadores gerais.
* Pesquisa, filtros e ordenação.
* Controlo de permissões por perfil.
* Registo de eventos em ficheiro de logs.
* Exportação de dados em CSV, JSON e PDF/impressão.


## Área privada

A área privada está protegida por autenticação. Apenas utilizadores registados conseguem aceder às funcionalidades internas da aplicação.
Após o login, o sistema guarda em sessão os dados principais do utilizador, como o nome, email e perfil. Estes dados são utilizados para personalizar a interface e controlar o acesso às diferentes funcionalidades.


## Perfis de utilizador

A aplicação possui três perfis principais:
O **Administrador** pode aceder a todas as funcionalidades do sistema.
O **Técnico** pode gerir os módulos operacionais, como equipamentos, fornecedores, localizações e documentação, mas não tem acesso à gestão dos conteúdos públicos nem às mensagens de contacto.
O **Profissional de saúde** tem um perfil de consulta. Pode visualizar o dashboard, os equipamentos e a documentação, mas não pode criar, editar, inativar, reativar ou exportar dados.


## Exportações

A aplicação permite exportar informação em diferentes formatos:
* **CSV** nos módulos de equipamentos, fornecedores, localizações e documentação.
* **JSON** no módulo de equipamentos.
* **PDF/impressão** através de relatórios imprimíveis.


## Logs

A aplicação regista eventos relevantes do sistema em ficheiro de log.

O ficheiro de logs encontra-se em:
private/logs/sistema.log

São registadas ações como login, tentativas de login falhadas, logout, criação, edição, inativação, reativação e exportação de dados.


## Credenciais de teste

### Administrador
Email: [clara.pereira@medctrl.pt]
Password: Clara@2026

### Técnico
Email: [miguel.santos@medctrl.pt]
Password: Miguel@2026

### Profissional de saúde
Email: [ana.ribeiro@medctrl.pt]
Password: Ana@2026


## Instalação e execução

O projeto deve ser colocado na pasta do servidor local respeitando a estrutura obrigatória definida no enunciado:
http://127.0.0.1/sibdas/<numeroaluno>/<nome_diretoria_projeto>

No caso deste projeto, a estrutura utilizada é:

http://127.0.0.1/sibdas/1240902/medctrl

Exemplo de estrutura de pastas:


pasta_do_servidor/
└── sibdas/
    └── 1240902/
        └── medctrl/
            ├── public/
            ├── private/
            ├── config/
            ├── assets/
            └── uploads/


## Base de dados

Antes de executar a aplicação, deve ser criada uma base de dados MySQL e importado o ficheiro `.sql` fornecido com o projeto.

Após a importação, devem ser confirmados os dados de ligação no ficheiro:
config/ligacao.php


## Configuração do BASE_URL

No ficheiro:
config/config.php

deve ser confirmado o valor da constante `BASE_URL`:
define('BASE_URL', 'http://127.0.0.1/sibdas/1240902/medctrl');


## Pasta de uploads

A aplicação utiliza a seguinte pasta para guardar os ficheiros enviados no módulo de documentação:
uploads/documentos/

Esta pasta deve existir no projeto.


## Execução da aplicação

Depois de configurar a base de dados e o `BASE_URL`, a aplicação pode ser executada no browser através do endereço:

http://127.0.0.1/sibdas/1240902/medctrl/public/index.php


## Autora

Clara Pereira — 1240902
Projeto: MedCtrl
SIBDAS — Sistemas de Informação e Bases de Dados Aplicados à Saúde
Ano letivo 2025/2026