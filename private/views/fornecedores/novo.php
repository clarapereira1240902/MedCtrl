<?php
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../../config/ligacao.php';

redirect_if_not_logged();

$menu_ativo = 'fornecedores';

$tipos_fornecedor = $ligacao->query("SELECT id, nome FROM tipos_fornecedor ORDER BY nome")->fetchAll(PDO::FETCH_OBJ);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {
        $sql = "
            INSERT INTO fornecedores (
                tipo_fornecedor_id,
                nome_empresa,
                nif,
                telefone,
                email,
                morada,
                website,
                pessoa_contacto,
                telefone_contacto,
                observacoes
            ) VALUES (
                :tipo_fornecedor_id,
                :nome_empresa,
                :nif,
                :telefone,
                :email,
                :morada,
                :website,
                :pessoa_contacto,
                :telefone_contacto,
                :observacoes
            )
        ";

        $stmt = $ligacao->prepare($sql);

        $stmt->execute([
            'tipo_fornecedor_id' => (int) $_POST['tipo_fornecedor_id'],
            'nome_empresa' => trim($_POST['nome_empresa']),
            'nif' => trim($_POST['nif']),
            'telefone' => trim($_POST['telefone']),
            'email' => trim($_POST['email']),
            'morada' => trim($_POST['morada']),
            'website' => trim($_POST['website']),
            'pessoa_contacto' => trim($_POST['pessoa_contacto']),
            'telefone_contacto' => trim($_POST['telefone_contacto']),
            'observacoes' => trim($_POST['observacoes'])
        ]);

        header('Location: lista.php');
        exit;

    } catch (PDOException $e) {
        die('Erro ao criar fornecedor.');
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
                    <i class="fa-solid fa-plus me-2"></i>
                    Novo Fornecedor
                </h2>
            </div>

            <hr>

            <form class="form-medctrl" method="post">

                <div class="row">

                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nome da Empresa</label>
                            <input type="text" name="nome_empresa" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">NIF</label>
                            <input type="text" name="nif" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Telefone</label>
                            <input type="text" name="telefone" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Morada</label>
                            <input type="text" name="morada" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Website</label>
                            <input type="url" name="website" class="form-control">
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Pessoa de Contacto</label>
                            <input type="text" name="pessoa_contacto" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Telefone da Pessoa de Contacto</label>
                            <input type="text" name="telefone_contacto" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tipo de Fornecedor</label>
                            <select name="tipo_fornecedor_id" class="form-select" required>
                                <option value="">Selecione</option>

                                <?php foreach ($tipos_fornecedor as $tipo) : ?>
                                    <option value="<?php echo $tipo->id; ?>">
                                        <?php echo htmlspecialchars($tipo->nome); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!--
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
                        -->

                        <div class="mb-3">
                            <label class="form-label">Observações</label>
                            <textarea name="observacoes" class="form-control" rows="5"></textarea>
                        </div>
                    </div>
                
                </div>

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

<script src="<?php echo BASE_URL; ?>/assets/bootstrap/bootstrap.bundle.min.js"></script>

</body>
</html>