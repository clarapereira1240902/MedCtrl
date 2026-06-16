<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
$menu_ativo = 'equipamentos';

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
                        <i class="fa-solid fa-pen-to-square me-2"></i> Editar Equipamento
                    </h2>

                    <a href="lista.php" class="btn btn-cancel btn-sm">
                        <i class="fa-solid fa-arrow-left me-1"></i> Voltar
                    </a>
                </div>

                <form class="form-medctrl">
                    <div class="row">

                        <!-- Informação geral do equipamento -->
                        <div class="col-12 mb-3">
                            <h4><i class="fa-solid fa-circle-info me-2"></i>Informação Geral</h4>
                            <hr>
                        </div>

                        <!-- Coluna esquerda -->
                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Código de Inventário</label>
                                <input type="text" class="form-control" value="EQUIP-2024-001">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Designação</label>
                                <input type="text" class="form-control" value="Monitor de Sinais Vitais">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Categoria</label>
                                <input type="text" class="form-control" value="Monitorização">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Marca</label>
                                <input type="text" class="form-control" value="Philips">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Modelo</label>
                                <input type="text" class="form-control" value="IntelliVue MX450">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Número de Série</label>
                                <input type="text" class="form-control" value="SN-78451236">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Fabricante</label>
                                <input type="text" class="form-control" value="Philips Healthcare">
                            </div>
                        </div>

                        <!-- Coluna direita -->
                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Data de Aquisição</label>
                                <input type="date" class="form-control" value="2023-06-15">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Ano de Fabrico</label>
                                <input type="number" class="form-control" value="2023">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Custo de Aquisição</label>
                                <input type="text" class="form-control" value="12500">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tipo de Entrada</label>
                                <select class="form-control">
                                    <option selected>Compra</option>
                                    <option>Doação</option>
                                    <option>Aluguer</option>
                                    <option>Empréstimo</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Estado Atual</label>
                                <select class="form-control">
                                    <option selected>Operacional</option>
                                    <option>Em manutenção</option>
                                    <option>Inativo</option>
                                    <option>Em calibração</option>
                                    <option>Em quarentena</option>
                                    <option>Abatido</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Criticidade</label>
                                <select class="form-control">
                                    <option>Baixa</option>
                                    <option selected>Média</option>
                                    <option>Alta</option>
                                    <option>Suporte de vida</option>
                                </select>
                            </div>
                        </div>

                    </div>


                    <!-- Localização do equipamento -->
                    <div class="mt-4 mb-4">
                        <h5 class="mb-3">
                            <i class="fa-solid fa-location-dot me-2"></i>Localização
                        </h5>
                        <hr>

                        <div class="row">
                            <div class="col-12 col-md-3 mb-3">
                                <label class="form-label">Edifício</label>
                                <input type="text" class="form-control">
                            </div>

                            <div class="col-12 col-md-3 mb-3">
                                <label class="form-label">Piso</label>
                                <input type="text" class="form-control">
                            </div>

                            <div class="col-12 col-md-3 mb-3">
                                <label class="form-label">Departamento</label>
                                <input type="text" class="form-control">
                            </div>

                            <div class="col-12 col-md-3 mb-3">
                                <label class="form-label">Sala</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                    </div>


                    <!-- Fornecedores do equipamento -->
                    <div class="mt-4 mb-4">
                        <h5 class="mb-3">
                            <i class="fa-solid fa-handshake me-2"></i>Fornecedores
                        </h5>
                        <hr>

                        <div class="row">
                            <div class="col-12 col-md-4 mb-3">
                                <label class="form-label">Fabricante</label>
                                <select class="form-select">
                                    <option>Siemens Healthineers</option>
                                    <option selected>Philips Healthcare</option>
                                    <option>Dräger Portugal</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-4 mb-3">
                                <label class="form-label">Distribuidor</label>
                                <select class="form-select">
                                    <option selected>MedTech Solutions</option>
                                    <option>Medical Partners</option>
                                    <option>BioMedical Portugal</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-4 mb-3">
                                <label class="form-label">Assistência Técnica</label>
                                <select class="form-select">
                                    <option selected>TechRepair Medical</option>
                                    <option>BioServiços</option>
                                    <option>MedSupport</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <!-- Garantia do equipamento  -->
                    <div class="mt-4 mb-4">
                        <h5 class="mb-3">
                            <i class="fa-solid fa-shield-halved me-2"></i>
                            Garantias e Contratos
                        </h5>
                        <hr>

                        <div class="row">
                            <div class="col-12 col-md-4 mb-3">
                                <label class="form-label">Início Garantia</label>
                                <input type="date" class="form-control">
                            </div>

                            <div class="col-12 col-md-4 mb-3">
                                <label class="form-label">Fim Garantia</label>
                                <input type="date" class="form-control">
                            </div>

                            <div class="col-12 col-md-4 mb-3">
                                <label class="form-label">Contrato de Manutenção</label>
                                <select class="form-select">
                                    <option>Sim</option>
                                    <option>Não</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label">Tipo de Contrato</label>
                                <select class="form-select">
                                    <option>Preventivo</option>
                                    <option>Corretivo</option>
                                    <option>Full Service</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label">Entidade Responsável</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                    </div>


                    <!-- Observações -->
                    <div class="mt-3">
                        <label class="form-label">Observações</label>
                        <textarea class="form-control" rows="3">Equipamento em excelente estado. Última manutenção Janeiro 2026.</textarea>
                    </div>

                    <!-- Botões -->
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="lista.php" class="btn btn-cancel btn-sm">Cancelar</a>

                        <button type="submit" class="btn btn-save btn-sm">
                            <i class="fa-regular fa-floppy-disk me-1"></i>Guardar alterações
                        </button>
                    </div>

                </form>

            </main>
        
        </div>
    </div>

    
<!-- Bootstrap JS and custom JS --> 
<script src="../../../assets/bootstrap/bootstrap.bundle.min.js"></script> 

</body>
</html>