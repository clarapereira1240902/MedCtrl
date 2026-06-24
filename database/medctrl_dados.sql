-- --------------------------------------------------------
-- Anfitrião:                    vsgate-s1.dei.isep.ipp.pt
-- Versão do servidor:           8.0.45 - MySQL Community Server - GPL
-- SO do servidor:               Linux
-- HeidiSQL Versão:              12.17.0.7270
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- A despejar dados para tabela db1240902.categorias: ~4 rows (aproximadamente)
INSERT INTO `categorias` (`id`, `nome`, `descricao`) VALUES
	(1, 'Monitorização', 'Equipamentos para monitorização de sinais vitais'),
	(2, 'Suporte de vida', 'Equipamentos críticos de suporte vital'),
	(3, 'Terapia', 'Equipamentos utilizados em tratamentos'),
	(4, 'Diagnóstico', 'Equipamentos de apoio ao diagnóstico');

-- A despejar dados para tabela db1240902.conteudos_publicos: ~41 rows (aproximadamente)
INSERT INTO `conteudos_publicos` (`id`, `secao`, `campo`, `titulo`, `conteudo`, `ordem`, `ativo`) VALUES
	(1, 'objetivo', 'titulo', 'Gestão Inteligente de Equipamentos Médicos', 'Gestão Inteligente de Equipamentos Médicos', 1, 1),
	(2, 'objetivo', 'texto', NULL, 'Organize e acompanhe todo o ciclo de vida dos equipamentos médicos numa única plataforma.', 2, 1),
	(3, 'contacto', 'morada', 'Morada', 'Rua de Cedofeita, nº 128, 4050-173, Porto', 3, 1),
	(4, 'contacto', 'email', 'Email', 'info@medctrl.pt', 7, 1),
	(5, 'contacto', 'telefone', 'Telefone', '+351 912 345 678', 8, 1),
	(6, 'objetivo', 'titulo_principal', NULL, 'Gestão Inteligente de Equipamentos Médicos', 1, 1),
	(7, 'objetivo', 'texto_introdutorio', NULL, 'Organize e acompanhe todo o ciclo de vida dos equipamentos médicos numa única plataforma.\r\nSimplifique o inventário, melhore a rastreabilidade e tenha acesso rápido à informação essencial.', 2, 1),
	(8, 'solucao', 'titulo', NULL, 'Solução', 1, 1),
	(9, 'solucao', 'subtitulo', NULL, 'Funcionalidades do sistema', 2, 1),
	(10, 'solucao', 'funcionalidade_1_titulo', NULL, 'Gestão de Equipamentos', 3, 1),
	(11, 'solucao', 'funcionalidade_1_texto', NULL, 'Registo completo e estruturado', 4, 1),
	(12, 'solucao', 'funcionalidade_2_titulo', NULL, 'Localização', 5, 1),
	(13, 'solucao', 'funcionalidade_2_texto', NULL, 'Localização em tempo real no hospital', 6, 1),
	(14, 'solucao', 'funcionalidade_3_titulo', NULL, 'Documentação', 7, 1),
	(15, 'solucao', 'funcionalidade_3_texto', NULL, 'Acesso centralizado a documentos técnicos', 8, 1),
	(16, 'solucao', 'funcionalidade_4_titulo', NULL, 'Fornecedores', 9, 1),
	(17, 'solucao', 'funcionalidade_4_texto', NULL, 'Gestão integrada de fornecedores e contratos', 10, 1),
	(18, 'solucao', 'funcionalidade_5_titulo', NULL, 'Pesquisa e Filtros Inteligentes', 11, 1),
	(19, 'solucao', 'funcionalidade_5_texto', NULL, 'Acesso imediato à informação relevante', 12, 1),
	(20, 'solucao', 'funcionalidade_6_titulo', NULL, 'Manutenção e Estado', 13, 1),
	(21, 'solucao', 'funcionalidade_6_texto', NULL, 'Controlo do estado operacional dos equipamentos', 14, 1),
	(22, 'solucao', 'funcionalidade_7_titulo', NULL, 'Gestão de Garantias e Contratos', 15, 1),
	(23, 'solucao', 'funcionalidade_7_texto', NULL, 'Consulta de garantias e datas importantes', 16, 1),
	(24, 'solucao', 'funcionalidade_8_titulo', NULL, 'Gestão de Utilizadores', 17, 1),
	(25, 'solucao', 'funcionalidade_8_texto', NULL, 'Segurança na gestão da informação', 18, 1),
	(26, 'vantagens', 'titulo', NULL, 'Vantagens', 1, 1),
	(27, 'vantagens', 'subtitulo', NULL, 'Benefícios da solução', 2, 1),
	(28, 'vantagens', 'beneficio_1', NULL, 'Redução de erros', 3, 1),
	(29, 'vantagens', 'beneficio_2', NULL, 'Informação única e organizada', 4, 1),
	(30, 'vantagens', 'beneficio_3', NULL, 'Acesso rápido à informação', 5, 1),
	(31, 'vantagens', 'beneficio_4', NULL, 'Melhor controlo tecnológico', 6, 1),
	(32, 'vantagens', 'beneficio_5', NULL, 'Maior eficiência operacional', 7, 1),
	(33, 'vantagens', 'area_1', NULL, 'Hospitais', 8, 1),
	(34, 'vantagens', 'area_2', NULL, 'Clínicas', 9, 1),
	(35, 'vantagens', 'area_3', NULL, 'Centros de saúde', 10, 1),
	(36, 'vantagens', 'area_4', NULL, 'Laboratórios', 11, 1),
	(37, 'contacto', 'titulo', NULL, 'Fale Connosco', 1, 1),
	(38, 'contacto', 'texto', NULL, 'Estamos disponíveis para esclarecer dúvidas sobre a plataforma MedCtrl e as suas funcionalidades.', 2, 1),
	(39, 'contacto', 'codigo_postal', NULL, '4050-173, Porto', 4, 1),
	(40, 'contacto', 'horario_semana', NULL, '2ª a 6ª Feira: 9h — 17h', 5, 1),
	(41, 'contacto', 'horario_adicional', NULL, 'Sábado e Feriados: 9h — 15h', 6, 1);

-- A despejar dados para tabela db1240902.criticidades: ~4 rows (aproximadamente)
INSERT INTO `criticidades` (`id`, `nome`, `descricao`) VALUES
	(1, 'Baixa', 'Baixo impacto clínico'),
	(2, 'Média', 'Impacto clínico moderado'),
	(3, 'Alta', 'Equipamento importante para diagnóstico ou tratamento'),
	(4, 'Suporte de vida', 'Equipamento crítico para manutenção da vida');

-- A despejar dados para tabela db1240902.documentos: ~4 rows (aproximadamente)
INSERT INTO `documentos` (`id`, `tipo_documento_id`, `equipamento_id`, `fornecedor_id`, `nome`, `data_documento`, `data_validade`, `ficheiro_link`, `observacoes`, `ativo`) VALUES
	(1, 1, 1, 1, 'Manual Monitor Philips MX450', '2024-03-10', '2027-03-10', '/sibdas/1240902/medctrl/uploads/documentos/doc_6a3b1a6def9fd9.77958977.pdf', 'Documento oficial do fabricante.', 1),
	(3, 3, 3, 4, 'Relatório Manutenção ECG TC70', '2025-02-01', NULL, '/sibdas/1240902/medctrl/uploads/documentos/doc_6a39b0cb74a0f4.11648641.pdf', 'Relatório de manutenção preventiva.', 1),
	(4, 4, 2, 5, 'Ficha Ventilador', '2026-06-20', '2027-11-09', '/sibdas/1240902/medctrl/uploads/documentos/doc_6a3b1e00af32a7.51432037.pdf', '', 1),
	(5, 2, 2, 2, 'Certificado Ventilador V500', '2024-01-15', '2026-11-25', '/sibdas/1240902/medctrl/uploads/documentos/doc_6a3b1df39c4701.03678116.pdf', '', 0);

-- A despejar dados para tabela db1240902.equipamento_fornecedor: ~9 rows (aproximadamente)
INSERT INTO `equipamento_fornecedor` (`id`, `equipamento_id`, `fornecedor_id`, `tipo_fornecedor_id`) VALUES
	(27, 9, 6, 1),
	(30, 11, 3, 2),
	(31, 12, 5, 4),
	(32, 13, 1, 1),
	(33, 1, 4, 3),
	(34, 1, 5, 4),
	(35, 1, 3, 2),
	(36, 1, 1, 1),
	(37, 2, 3, 2),
	(38, 2, 2, 1),
	(39, 3, 4, 3),
	(40, 3, 1, 1);

-- A despejar dados para tabela db1240902.equipamentos: ~6 rows (aproximadamente)
INSERT INTO `equipamentos` (`id`, `localizacao_id`, `categoria_id`, `estado_id`, `criticidade_id`, `tipo_entrada_id`, `codigo_inventario`, `designacao`, `marca`, `modelo`, `numero_serie`, `fabricante`, `data_aquisicao`, `ano_fabrico`, `custo_aquisicao`, `observacoes`, `ativo`) VALUES
	(1, 6, 1, 2, 2, 2, 'EQ001', 'Monitor de Sinais Vitais', 'Philips', 'IntelliVue MX450', 'SN-78451', 'Philips Healthcare', '2023-01-12', 2022, 126.00, 'Equipamento em excelente estado.', 1),
	(2, 7, 2, 1, 4, 1, 'EQ002', 'Ventilador Pulmonar', 'Dräger', 'Evita V500', 'EV500-9934', 'Dräger Portugal', '2022-02-10', 2021, 28000.00, 'Equipamento crítico da UCI.', 1),
	(3, 5, 4, 3, 1, 1, 'EQ003', 'ECG Philips TC70', 'Philips', 'TC70', 'ECG-4455', 'Philips Healthcare', '2022-09-20', 2022, 7200.00, 'Em manutenção preventiva.', 1),
	(4, 10, 1, 1, 1, 1, 'EQ004', 'Bomba de Infusão Volumétrica', 'B. Braun', 'Infusomat Space', 'IS-2026-458921', 'B. Braun', '2026-01-15', 2025, 200.00, 'Equipamento em ótimo estado.', 1),
	(9, 1, 2, 1, 4, 4, 'EQ005', 'Desfibrilhador', 'ZOLL', 'AED Plus', 'AED-78452', 'ZOLL Medical', '2025-01-14', 2024, NULL, '', 1),
	(10, 3, 4, 1, 2, 1, 'EQ006', 'Ecógrafo', 'Samsung', 'HS40', 'ECO-34219', 'Samsung Medison', NULL, NULL, 200.00, '', 0),
	(11, 1, 4, 1, 2, 1, 'EQ007', 'Oxímetro Portátil', 'MedTech', 'OX100', 'OX-2026-01', 'MedTech', '2024-02-10', 2023, 420.00, 'Equipamento portátil para medição de saturação.', 1),
	(12, 10, 1, 1, 1, 1, 'EQ008', 'Termómetro Digital', 'MedSupply', 'TD50', 'TD-2026-08', 'MedSupply', '2023-09-15', 2023, 85.00, 'Termómetro digital para uso clínico.', 1),
	(13, 7, 2, 2, 3, 1, 'EQ009', 'Aspirador Cirúrgico', 'Philips', 'AC200', 'AC-2026-09', 'Philips Healthcare', '2022-11-20', 2022, 1250.00, 'Equipamento em manutenção preventiva.', 1);

-- A despejar dados para tabela db1240902.estados_equipamento: ~6 rows (aproximadamente)
INSERT INTO `estados_equipamento` (`id`, `nome`) VALUES
	(1, 'Operacional'),
	(2, 'Em manutenção'),
	(3, 'Inativo'),
	(4, 'Em calibração'),
	(5, 'Em quarentena'),
	(6, 'Abatido');

-- A despejar dados para tabela db1240902.fornecedores: ~6 rows (aproximadamente)
INSERT INTO `fornecedores` (`id`, `tipo_fornecedor_id`, `nome_empresa`, `nif`, `telefone`, `email`, `morada`, `website`, `pessoa_contacto`, `telefone_contacto`, `observacoes`, `ativo`) VALUES
	(1, 1, 'Philips Healthcare', '507111111', '229111111', 'geral@philips.pt', 'Rua da Tecnologia, Porto', 'https://www.philips.pt', 'Ana Silva', '912111111', 'Fornecedor de equipamentos de monitorização.', 1),
	(2, 1, 'Dräger Portugal', '507123456', '229876543', 'geral@drager.pt', 'Rua da Saúde, Porto', 'www.drager.pt', 'João Costa', '912345678', 'Fornecedor principal de equipamentos críticos.', 0),
	(3, 2, 'MedTech Solutions', '507222222', '229222222', 'contacto@medtech.pt', 'Avenida Central, Lisboa', 'www.medtech.pt', 'Carlos Mendes', '913222222', 'Distribuidor nacional.', 1),
	(4, 3, 'TechRepair Medical', '507333333', '229333333', 'suporte@techrepair.pt', 'Rua da Manutenção, Braga', 'www.techrepair.pt', 'Mariana Lopes', '914333333', 'Empresa de assistência técnica.', 1),
	(5, 4, 'MedSupply', '517654321', '251 908 427', 'geral@medsupply.pt', '', '', 'Maria Costa', '967 812 509', '', 1),
	(6, 1, 'Samsung', '817250954', '253780427', 'info@samsung.com', '', 'https://www.samsung.pt', 'João Silva', '914686092', '', 1);

-- A despejar dados para tabela db1240902.garantias_contratos: ~5 rows (aproximadamente)
INSERT INTO `garantias_contratos` (`id`, `equipamento_id`, `fornecedor_id`, `inicio_garantia`, `fim_garantia`, `tem_contrato_manutencao`, `tipo_contrato`, `entidade_responsavel`, `periodicidade`, `observacoes`) VALUES
	(1, 1, 1, '2023-06-15', '2026-06-15', 1, 'Preventivo', 'Philips Healthcare', 'Anual', ''),
	(2, 2, 2, '2022-02-11', '2026-12-01', 0, 'Preventivo + Corretivo', 'Dräger Portugal', 'Trimestral', ''),
	(3, 3, 4, '2022-09-20', '2024-09-20', 1, 'Corretivo', 'TechRepair Medical', 'Anual', ''),
	(4, 9, NULL, '2025-01-14', '2026-11-05', 0, 'Preventivo', 'ZOLL Medical', 'Semestral', ''),
	(5, 10, NULL, NULL, NULL, 0, '', '', '', ''),
	(6, 11, NULL, '2024-02-10', '2027-02-10', 1, 'Manutenção preventiva', 'MedTech Solutions', 'Anual', 'Garantia ativa com manutenção preventiva anual.'),
	(7, 12, NULL, '2023-09-15', '2026-09-15', 0, NULL, 'MedSupply', NULL, 'Garantia ativa sem contrato de manutenção.'),
	(8, 13, NULL, '2022-11-20', '2025-11-20', 1, 'Manutenção corretiva e preventiva', 'Philips Healthcare', 'Semestral', 'Equipamento com contrato de manutenção semestral.'),
	(9, 4, NULL, NULL, NULL, 0, '', '', '', '');

-- A despejar dados para tabela db1240902.localizacoes: ~4 rows (aproximadamente)
INSERT INTO `localizacoes` (`id`, `edificio`, `piso`, `servico`, `sala`, `ativo`) VALUES
	(1, 'Hospital Sul', '1º Piso', 'Urgência', 'Sala 12', 0),
	(2, 'Hospital Norte', '1º Piso', 'Urgência', 'Sala 3', 0),
	(3, 'Hospital Central', '3º Piso', 'UCI', 'Sala 8', 0),
	(4, 'Hospital Central', '2º Piso', 'Obstretícia', 'Sala 2', 0),
	(5, 'Edifício A', '1', 'UCI', 'UCI-02', 1),
	(6, 'Edifício A', '0', 'Urgência', 'TR-01', 0),
	(7, 'Edifício B', '2', 'Bloco Operatório', 'BO-01', 1),
	(8, 'Edifício B', '1', 'Imagiologia', 'RX-01', 1),
	(9, 'Edifício C', '0', 'Laboratório', 'LAB-02', 1),
	(10, 'Edifício A', '2', 'Pediatria', 'PED-01', 1);

-- A despejar dados para tabela db1240902.logs_sistema: ~0 rows (aproximadamente)
INSERT INTO `logs_sistema` (`id`, `utilizador_id`, `acao`, `tabela_afetada`, `registo_id`, `data_evento`, `detalhes`) VALUES
	(1, 1, 'Criação inicial de dados de teste', 'sistema', NULL, '2026-06-12 12:04:52', 'Dados inseridos para testes do projeto.');

-- A despejar dados para tabela db1240902.mensagens_contacto: ~0 rows (aproximadamente)
INSERT INTO `mensagens_contacto` (`id`, `nome`, `email`, `mensagem`, `data_envio`, `lida`) VALUES
	(1, 'Maria Santos', 'maria@email.pt', 'Gostaria de saber mais sobre a plataforma.', '2026-06-12 12:04:52', 0),
	(2, 'Leonor Alves', 'leonor.alves@gmail.com', 'Gostaria de marcar uma reunião online para tirar dúvidas.', '2026-06-22 11:35:31', 1),
	(6, 'Gonçalo Santos', 'goncalo.santos@gmail.com', 'Queria receber mais informações das funcionalidades.', '2026-06-23 01:06:47', 0);

-- A despejar dados para tabela db1240902.perfis: ~4 rows (aproximadamente)
INSERT INTO `perfis` (`id`, `nome`) VALUES
	(1, 'Administrador'),
	(2, 'Técnico'),
	(3, 'Consulta'),
	(7, 'Profissional de saúde');

-- A despejar dados para tabela db1240902.tipos_documento: ~6 rows (aproximadamente)
INSERT INTO `tipos_documento` (`id`, `nome`) VALUES
	(1, 'Manual Técnico'),
	(2, 'Certificado'),
	(3, 'Relatório de Manutenção'),
	(4, 'Ficha Técnica'),
	(5, 'Contrato'),
	(6, 'Garantia');

-- A despejar dados para tabela db1240902.tipos_entrada: ~4 rows (aproximadamente)
INSERT INTO `tipos_entrada` (`id`, `nome`) VALUES
	(1, 'Compra'),
	(2, 'Doação'),
	(3, 'Aluguer'),
	(4, 'Empréstimo');

-- A despejar dados para tabela db1240902.tipos_fornecedor: ~4 rows (aproximadamente)
INSERT INTO `tipos_fornecedor` (`id`, `nome`) VALUES
	(1, 'Fabricante'),
	(2, 'Distribuidor'),
	(3, 'Assistência Técnica'),
	(4, 'Consumíveis'),
	(5, 'Manutenção');

-- A despejar dados para tabela db1240902.utilizadores: ~3 rows (aproximadamente)
INSERT INTO `utilizadores` (`id`, `perfil_id`, `nome`, `email`, `password_hash`, `ativo`, `criado_em`) VALUES
	(1, 1, 'Clara Pereira', 'clara.pereira@medctrl.pt', '$2y$12$HVJB0lQwkmoBIJiblvkpGeiVAZ4DK9/Mc6CuvHxYYYq/AQzlwQAey', 1, '2026-06-12 12:04:52'),
	(2, 2, 'Miguel Santos', 'miguel.santos@medctrl.pt', '$2y$12$NPKLk0kQr5CN8f6J41.CoOk.sgNSMMKP/pPjhzU7MB1.ZwHsLOOE.', 1, '2026-06-12 12:04:52'),
	(3, 7, 'Ana Ribeiro', 'ana.ribeiro@medctrl.pt', '$2y$12$MDrSvB0kq12gJ4JMbgTc6.QIZ5NVC9Gmaa6HG/QAuVPbbC0gpwPQS', 1, '2026-06-23 15:51:49');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
