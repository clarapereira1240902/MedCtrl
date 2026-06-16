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
                        <i class="fa-solid fa-plus me-2"></i>
                        Novo Equipamento
                    </h2>
                </div>

                <hr>

                <form class="form-medctrl">

                    <div class="row">
                        
                        <!--Informações gerais do equipamento-->
                        <div class="col-12 mb-3">
                            <h4><i class="fa-solid fa-circle-info me-2"></i>Informação Geral</h4>
                            <hr>
                        </div>

                        <div class="col-12 col-md-4 mb-3">
                            <label class="form-label">Código de Inventário</label>
                            <input type="text" class="form-control" placeholder="Ex: INV-001">
                        </div>

                        <div class="col-12 col-md-8 mb-3">
                            <label class="form-label">Designação</label>
                            <input type="text" class="form-control" placeholder="Ex: Monitor Multiparamétrico">
                        </div>

                        <div class="col-12 col-md-4 mb-3">
                            <label class="form-label">Categoria</label>
                            <input type="text" class="form-control">
                        </div>

                        <div class="col-12 col-md-4 mb-3">
                            <label class="form-label">Marca</label>
                            <input type="text" class="form-control">
                        </div>

                        <div class="col-12 col-md-4 mb-3">
                            <label class="form-label">Modelo</label>
                            <input type="text" class="form-control">
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label">Número de Série</label>
                            <input type="text" class="form-control">
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label">Fabricante</label>
                            <input type="text" class="form-control">
                        </div>

                        <div class="col-12 col-md-4 mb-3">
                            <label class="form-label">Data de Aquisição</label>
                            <input type="date" class="form-control">
                        </div>

                        <div class="col-12 col-md-4 mb-3">
                            <label class="form-label">Ano de Fabrico</label>
                            <input type="number" class="form-control">
                        </div>

                        <div class="col-12 col-md-4 mb-3">
                            <label class="form-label">Custo (€)</label>
                            <input type="number" class="form-control">
                        </div>

                        <div class="col-12 col-md-4 mb-3">
                            <label class="form-label">Tipo de Entrada</label>
                            <select class="form-select">
                                <option>Compra</option>
                                <option>Doação</option>
                                <option>Aluguer</option>
                                <option>Empréstimo</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-4 mb-3">
                            <label class="form-label">Estado</label>
                            <select class="form-select">
                                <option>Operacional</option>
                                <option>Em manutenção</option>
                                <option>Inativo</option>
                                <option>Em calibração</option>
                                <option>Em quarentena</option>
                                <option>Abatido</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-4 mb-3">
                            <label class="form-label">Criticidade</label>
                            <select class="form-select">
                                <option>Baixa</option>
                                <option>Média</option>
                                <option>Alta</option>
                                <option>Suporte de vida</option>
                            </select>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Observações</label>
                            <textarea class="form-control" rows="3"></textarea>
                        </div>
                        
                        <!--Localização do equipamento-->
                        <div class="col-12 mt-4">
                            <h4><i class="fa-solid fa-location-dot me-2"></i>Localização</h4>
                            <hr>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Localização</label>
                            <select class="form-select">
                                <option selected>Selecionar localização</option>

                                <option>
                                    Hospital Central - Piso 2 - Cardiologia - Sala 12
                                </option>

                                <option>
                                    Hospital Norte - Piso 1 - Urgência - Sala 3
                                </option>

                                <option>
                                    Hospital Sul - Piso 0 - Radiologia - Sala RX02
                                </option>
                            </select>
                        </div>


                        <!-- Fornecedores do equipamento -->
                        <div class="col-12 mt-4">
                            <h4>
                                <i class="fa-solid fa-handshake me-2"></i>Fornecedores
                            </h4>
                            <hr>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Fabricante</label>
                            <select class="form-select">
                                <option selected>Selecionar fornecedor</option>
                                <option>Philips Healthcare</option>
                                <option>Siemens Healthineers</option>
                                <option>Dräger Portugal</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Distribuidor</label>
                            <select class="form-select">
                                <option selected>Selecionar fornecedor</option>
                                <option>MedTech Solutions</option>
                                <option>Medical Partners</option>
                                <option>BioMedical Portugal</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Assistência Técnica</label>
                            <select class="form-select">
                                <option selected>Selecionar fornecedor</option>
                                <option>TechRepair Medical</option>
                                <option>BioServiços</option>
                                <option>MedSupport</option>
                            </select>
                        </div>


                        <!--Garantias do equipamento-->
                        <div class="col-12 mt-4">
                            <h4><i class="fa-solid fa-shield-halved me-2"></i>Garantia e Manutenção</h4>
                            <hr>
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label">Data início garantia</label>
                            <input type="date"class="form-control">
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label">Data fim garantia</label>
                            <input type="date"class="form-control">
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label">Contrato de manutenção</label>
                            <select class="form-select">
                                <option selected>Escolher</option>
                                <option>Sim</option>
                                <option>Não</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label">Tipo contrato</label>
                            <select class="form-select">
                                <option selected>Escolher</option>
                                <option>Preventivo</option>
                                <option>Corretivo</option>
                                <option>Preventivo + Corretivo</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label">Periodicidade</label>
                            <select class="form-select">
                                <option selected>Escolher</option>
                                <option>Mensal</option>
                                <option>Trimestral</option>
                                <option>Semestral</option>
                                <option>Anual</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label">Entidade responsável</label>
                            <input type="text" class="form-control">
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Observações da garantia</label>
                            <textarea class="form-control" rows="3"></textarea>
                        </div>


                        <!-- Botões -->
                        <div class="d-flex justify-content-end gap-2 mb-4"> 
                            <a href="lista.php" class="btn btn-cancel btn-sm"> 
                                <i class="fa-solid fa-xmark me-1"></i> Cancelar 
                            </a> 
                            <button type="submit" class="btn btn-save btn-sm"> 
                                <i class="fa-regular fa-floppy-disk me-1"></i> Guardar 
                            </button> 
                        </div>

                    </div>

                </form>

            </main>
        
        </div>
    </div>

    
<!-- Bootstrap JS and custom JS --> 
<script src="<?php echo BASE_URL; ?>/assets/bootstrap/bootstrap.bundle.min.js"></script>

</body>
</html>