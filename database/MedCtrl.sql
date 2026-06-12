CREATE TABLE `perfis` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nome` varchar(50) UNIQUE NOT NULL
);

CREATE TABLE `utilizadores` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `perfil_id` int NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) UNIQUE NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `ativo` boolean NOT NULL DEFAULT true,
  `criado_em` datetime
);

CREATE TABLE `categorias` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nome` varchar(100) UNIQUE NOT NULL,
  `descricao` text
);

CREATE TABLE `estados_equipamento` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nome` varchar(50) UNIQUE NOT NULL
);

CREATE TABLE `criticidades` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nome` varchar(50) UNIQUE NOT NULL,
  `descricao` text
);

CREATE TABLE `tipos_entrada` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nome` varchar(50) UNIQUE NOT NULL
);

CREATE TABLE `localizacoes` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `edificio` varchar(100) NOT NULL,
  `piso` varchar(50) NOT NULL,
  `servico` varchar(100) NOT NULL,
  `sala` varchar(100) NOT NULL
);

CREATE TABLE `equipamentos` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `localizacao_id` int NOT NULL,
  `categoria_id` int NOT NULL,
  `estado_id` int NOT NULL,
  `criticidade_id` int NOT NULL,
  `tipo_entrada_id` int NOT NULL,
  `codigo_inventario` varchar(50) UNIQUE NOT NULL,
  `designacao` varchar(150) NOT NULL,
  `marca` varchar(100) NOT NULL,
  `modelo` varchar(100) NOT NULL,
  `numero_serie` varchar(100) NOT NULL,
  `fabricante` varchar(150),
  `data_aquisicao` date,
  `ano_fabrico` int,
  `custo_aquisicao` decimal(10,2),
  `observacoes` text,
  `ativo` boolean NOT NULL DEFAULT true
);

CREATE TABLE `tipos_fornecedor` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nome` varchar(80) UNIQUE NOT NULL
);

CREATE TABLE `fornecedores` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `tipo_fornecedor_id` int NOT NULL,
  `nome_empresa` varchar(150) NOT NULL,
  `nif` varchar(20) UNIQUE NOT NULL,
  `telefone` varchar(30),
  `email` varchar(150),
  `morada` varchar(255),
  `website` varchar(150),
  `pessoa_contacto` varchar(100),
  `telefone_contacto` varchar(30),
  `observacoes` text,
  `ativo` boolean NOT NULL DEFAULT true
);

CREATE TABLE `equipamento_fornecedor` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `equipamento_id` int NOT NULL,
  `fornecedor_id` int NOT NULL,
  `tipo_fornecedor_id` int NOT NULL
);

CREATE TABLE `tipos_documento` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nome` varchar(80) UNIQUE NOT NULL
);

CREATE TABLE `documentos` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `tipo_documento_id` int NOT NULL,
  `equipamento_id` int NOT NULL,
  `fornecedor_id` int,
  `nome` varchar(150) NOT NULL,
  `data_documento` date,
  `data_validade` date,
  `ficheiro_link` varchar(255) NOT NULL,
  `observacoes` text,
  `ativo` boolean NOT NULL DEFAULT true
);

CREATE TABLE `garantias_contratos` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `equipamento_id` int NOT NULL,
  `fornecedor_id` int,
  `inicio_garantia` date,
  `fim_garantia` date,
  `tem_contrato_manutencao` boolean NOT NULL DEFAULT false,
  `tipo_contrato` varchar(80),
  `entidade_responsavel` varchar(150),
  `periodicidade` varchar(80),
  `observacoes` text
);

CREATE TABLE `conteudos_publicos` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `secao` varchar(80) NOT NULL,
  `campo` varchar(80) NOT NULL,
  `titulo` varchar(150),
  `conteudo` text NOT NULL,
  `ordem` int DEFAULT 0,
  `ativo` boolean NOT NULL DEFAULT true
);

CREATE TABLE `mensagens_contacto` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mensagem` text NOT NULL,
  `data_envio` datetime,
  `lida` boolean NOT NULL DEFAULT false
);

CREATE TABLE `logs_sistema` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `utilizador_id` int,
  `acao` varchar(150) NOT NULL,
  `tabela_afetada` varchar(80),
  `registo_id` int,
  `data_evento` datetime,
  `detalhes` text
);

CREATE UNIQUE INDEX `equipamentos_index_0` ON `equipamentos` (`marca`, `modelo`, `numero_serie`);

CREATE UNIQUE INDEX `equipamento_fornecedor_index_1` ON `equipamento_fornecedor` (`equipamento_id`, `fornecedor_id`, `tipo_fornecedor_id`);

CREATE INDEX `conteudos_publicos_index_2` ON `conteudos_publicos` (`secao`, `campo`, `ordem`);

ALTER TABLE `utilizadores` ADD FOREIGN KEY (`perfil_id`) REFERENCES `perfis` (`id`);

ALTER TABLE `equipamentos` ADD FOREIGN KEY (`localizacao_id`) REFERENCES `localizacoes` (`id`);

ALTER TABLE `equipamentos` ADD FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);

ALTER TABLE `equipamentos` ADD FOREIGN KEY (`estado_id`) REFERENCES `estados_equipamento` (`id`);

ALTER TABLE `equipamentos` ADD FOREIGN KEY (`criticidade_id`) REFERENCES `criticidades` (`id`);

ALTER TABLE `equipamentos` ADD FOREIGN KEY (`tipo_entrada_id`) REFERENCES `tipos_entrada` (`id`);

ALTER TABLE `fornecedores` ADD FOREIGN KEY (`tipo_fornecedor_id`) REFERENCES `tipos_fornecedor` (`id`);

ALTER TABLE `equipamento_fornecedor` ADD FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`);

ALTER TABLE `equipamento_fornecedor` ADD FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedores` (`id`);

ALTER TABLE `equipamento_fornecedor` ADD FOREIGN KEY (`tipo_fornecedor_id`) REFERENCES `tipos_fornecedor` (`id`);

ALTER TABLE `documentos` ADD FOREIGN KEY (`tipo_documento_id`) REFERENCES `tipos_documento` (`id`);

ALTER TABLE `documentos` ADD FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`);

ALTER TABLE `documentos` ADD FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedores` (`id`);

ALTER TABLE `garantias_contratos` ADD FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`);

ALTER TABLE `garantias_contratos` ADD FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedores` (`id`);

ALTER TABLE `logs_sistema` ADD FOREIGN KEY (`utilizador_id`) REFERENCES `utilizadores` (`id`);
