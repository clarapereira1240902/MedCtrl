<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

$menu_ativo = 'fornecedores';

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
                        Novo Fornecedor
                    </h2>
                </div>

                <hr>

                <form class="form-medctrl">

                    <div class="row">

                        <!-- Coluna esquerda -->
                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nome da Empresa</label>
                                <input type="text" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">NIF</label>
                                <input type="text" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Telefone</label>
                                <input type="text" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Morada</label>
                                <input type="text" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Website</label>
                                <input type="url" class="form-control">
                            </div>
                        </div>

                        <!-- Coluna direita -->
                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Pessoa de Contacto</label>
                                <input type="text" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Telefone da Pessoa de Contacto</label>
                                <input type="text" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tipo de Fornecedor</label>
                                <select class="form-select">
                                    <option selected>Selecione</option>
                                    <option>Fabricante</option>
                                    <option>Distribuidor / Fornecedor Comercial</option>
                                    <option>Assistência Técnica</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Equipamentos Associados</label>
                                <div class="border rounded p-3 bg-white">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="eq1">
                                        <label class="form-check-label" for="eq1">
                                            Monitor de Sinais Vitais MX450
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="eq2">
                                        <label class="form-check-label" for="eq2">
                                            Ventilador Pulmonar V500
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="eq3">
                                        <label class="form-check-label" for="eq3">
                                            ECG Philips TC70
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Observações</label>
                                <textarea class="form-control" rows="5"></textarea>
                            </div>
                        </div>
                    
                    </div>


                    <!-- Botões -->
                    <div class="d-flex justify-content-end gap-2 mb-4"> 
                        <a href="lista.php" class="btn btn-cancel"> 
                            <i class="fa-solid fa-xmark me-1"></i> Cancelar 
                        </a> 
                        <button type="submit" class="btn btn-save"> 
                            <i class="fa-regular fa-floppy-disk me-1"></i> Guardar 
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