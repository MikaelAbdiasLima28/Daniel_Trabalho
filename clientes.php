<?php
include_once("bancodedados.php");

$acao = $_GET['acao'] ?? '';
$id = $_GET['id'] ?? '';


if ($acao == "salvar") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];

    $sql = "INSERT INTO clientes (nome, email, telefone, endereco) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nome, $email, $telefone, $endereco);
    $stmt->execute();

    header("Location: ?pg=clientes");
    exit;
}

if ($acao == "excluir" && $id) {
    $sql = "DELETE FROM clientes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: ?pg=clientes");
    exit;
}


if ($acao == "editar" && $id) {
    $sql = "SELECT * FROM clientes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $clienteEdit = $resultado->fetch_assoc();
}


if ($acao == "atualizar") {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];

    $sql = "UPDATE clientes SET nome = ?, email = ?, telefone = ?, endereco = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $nome, $email, $telefone, $endereco, $id);
    $stmt->execute();

    header("Location: ?pg=clientes");
    exit;
}
?>

<div class="container mt-4">
    <h2 class="text-black mb-4">Gerenciar Clientes</h2>

    <div class="card bg-dark text-light p-4 mb-4">
        <form method="POST" action="?pg=clientes&acao=<?= isset($clienteEdit) ? 'atualizar' : 'salvar' ?>">
            <?php if (isset($clienteEdit)): ?>
                <input type="hidden" name="id" value="<?= $clienteEdit['id'] ?>">
            <?php endif; ?>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Nome</label>
                    <input type="text" name="nome" class="form-control" required value="<?= $clienteEdit['nome'] ?? '' ?>">
                </div>
                <div class="col-md-6">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required value="<?= $clienteEdit['email'] ?? '' ?>">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Telefone</label>
                    <input type="text" name="telefone" class="form-control" value="<?= $clienteEdit['telefone'] ?? '' ?>">
                </div>
                <div class="col-md-6">
                    <label>Endereço</label>
                    <input type="text" name="endereco" class="form-control" value="<?= $clienteEdit['endereco'] ?? '' ?>">
                </div>
            </div>

            <button type="submit" class="btn btn-success">
                <?= isset($clienteEdit) ? 'Atualizar Cliente' : 'Adicionar Cliente' ?>
            </button>
            <?php if (isset($clienteEdit)): ?>
                <a href="?pg=clientes" class="btn btn-secondary">Cancelar</a>
            <?php endif; ?>
        </form>
    </div>

    <table class="table table-dark table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Endereço</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM clientes ORDER BY id DESC";
            $resultado = $conn->query($sql);

            if ($resultado->num_rows > 0):
                while ($cliente = $resultado->fetch_assoc()):
            ?>
                <tr>
                    <td><?= $cliente['id'] ?></td>
                    <td><?= $cliente['nome'] ?></td>
                    <td><?= $cliente['email'] ?></td>
                    <td><?= $cliente['telefone'] ?></td>
                    <td><?= $cliente['endereco'] ?></td>
                    <td>
                        <a href="?pg=clientes&acao=editar&id=<?= $cliente['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="?pg=clientes&acao=excluir&id=<?= $cliente['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Deseja realmente excluir este cliente?')">Excluir</a>
                    </td>
                </tr>
            <?php
                endwhile;
            else:
            ?>
                <tr>
                    <td colspan="6" class="text-center">Nenhum cliente cadastrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
