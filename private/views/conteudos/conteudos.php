<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

$menu_ativo = 'conteudos';

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/navbar.php';
?>

    <div class="container-fluid">
        <div class="row">

            <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

            <!-- Conteúdo Principal -->
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

                <div class="accordion" id="accordionConteudos">

                    <!-- OBJETIVO -->
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
                                        <input type="text" class="form-control" value="Gestão Inteligente de Equipamentos Médicos">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Texto introdutório</label>
                                        <textarea class="form-control" rows="4">Organize e acompanhe todo o ciclo de vida dos equipamentos médicos numa única plataforma. Simplifique o inventário, melhore a rastreabilidade e tenha acesso rápido à informação essencial.</textarea>
                                    </div>

                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="button" class="btn btn-cancel">
                                            Cancelar
                                        </button>
                                        <button type="button" class="btn btn-save">
                                            <i class="fa-solid fa-floppy-disk me-1"></i> Guardar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SOLUÇÃO -->
                    <div class="accordion-item mb-3">
                        <h2 class="accordion-header" id="headingSolucao">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSolucao">
                                <i class="fa-solid fa-puzzle-piece me-2"></i> Secção Solução
                            </button>
                        </h2>

                        <div id="collapseSolucao" class="accordion-collapse collapse" data-bs-parent="#accordionConteudos">
                            <div class="accordion-body">
                                <div class="form-medctrl">
                                    <div class="mb-3">
                                        <label class="form-label">Título da secção</label>
                                        <input type="text" class="form-control" value="Solução">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Subtítulo</label>
                                        <input type="text" class="form-control" value="Funcionalidades do sistema">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Funcionalidades apresentadas</label>
                                        <textarea class="form-control" rows="6">
Gestão de Equipamentos
Localização
Documentação
Fornecedores
Pesquisa e Filtros Inteligentes
Manutenção e Estado
Gestão de Garantias e Contratos
Gestão de Utilizadores
                                        </textarea>
                                    </div>

                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="button" class="btn btn-cancel">
                                            Cancelar
                                        </button>
                                        <button type="button" class="btn btn-save">
                                            <i class="fa-solid fa-floppy-disk me-1"></i> Guardar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- VANTAGENS -->
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
                                            <label class="form-label">Benefício 1</label>
                                            <input type="text" class="form-control" value="Redução de erros">
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Benefício 2</label>
                                            <input type="text" class="form-control" value="Informação única e organizada">
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Benefício 3</label>
                                            <input type="text" class="form-control" value="Acesso rápido à informação">
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Benefício 4</label>
                                            <input type="text" class="form-control" value="Melhor controlo tecnológico">
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Área de aplicação 1</label>
                                            <input type="text" class="form-control" value="Hospitais">
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Área de aplicação 2</label>
                                            <input type="text" class="form-control" value="Clínicas">
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Área de aplicação 3</label>
                                            <input type="text" class="form-control" value="Centros de saúde">
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Área de aplicação 4</label>
                                            <input type="text" class="form-control" value="Laboratórios">
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end gap-2 mt-4">
                                        <button type="button" class="btn btn-cancel">
                                            Cancelar
                                        </button>
                                        <button type="button" class="btn btn-save">
                                            <i class="fa-solid fa-floppy-disk me-1"></i> Guardar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CONTACTOS -->
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
                                        <input type="text" class="form-control" value="Fale Connosco">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Texto introdutório</label>
                                        <textarea class="form-control" rows="3">Estamos disponíveis para esclarecer dúvidas sobre a plataforma MedCtrl e as suas funcionalidades.</textarea>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Morada</label>
                                            <input type="text" class="form-control" value="Rua de Cedofeita, nº 128">
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Código postal e localidade</label>
                                            <input type="text" class="form-control" value="4050-173, Porto">
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Horário semanal</label>
                                            <input type="text" class="form-control" value="2ª a 6ª Feira: 9h — 17h">
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Horário adicional</label>
                                            <input type="text" class="form-control" value="Sábado e Feriados: 9h — 15h">
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" value="info@medctrl.pt">
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Telefone</label>
                                            <input type="text" class="form-control" value="+351 912 345 678">
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end gap-2 mt-4">
                                        <button type="button" class="btn btn-cancel">
                                            Cancelar
                                        </button>
                                        <button type="button" class="btn btn-save">
                                            <i class="fa-solid fa-floppy-disk me-1"></i> Guardar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </main>
        
        </div>
    </div>

    <!-- Bootstrap JS --> 
    <script src="../../../assets/bootstrap/bootstrap.bundle.min.js"></script> 
</body>
</html>