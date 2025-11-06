<?php
include_once 'bancodedados.php';
$acao = $_GET['acao'] ?? '';

if ($acao == 'salvar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $quantidade = $_POST['quantidade'];
    $stmt = $conn->prepare('INSERT INTO produtos (nome, descricao, preco, quantidade) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('ssdi', $nome, $descricao, $preco, $quantidade);
    $stmt->execute();
    header('Location: index.php?pg=produtos');
    exit;
}

if ($acao == 'atualizar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $quantidade = $_POST['quantidade'];
    $stmt = $conn->prepare('UPDATE produtos SET nome=?, descricao=?, preco=?, quantidade=? WHERE id=?');
    $stmt->bind_param('ssdii', $nome, $descricao, $preco, $quantidade, $id);
    $stmt->execute();
    header('Location: index.php?pg=produtos');
    exit;
}

if ($acao == 'excluir') {
    $id = $_GET['id'];
    $stmt = $conn->prepare('DELETE FROM produtos WHERE id=?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    header('Location: index.php?pg=produtos');
    exit;
}
?>
<div class="container mt-3">
    <h2>Produtos</h2>

    <?php if (($acao ?? '') == 'editar' && isset($_GET['id'])):
        $id = intval($_GET['id']);
        $res = $conn->prepare('SELECT * FROM produtos WHERE id=?');
        $res->bind_param('i', $id);
        $res->execute();
        $result = $res->get_result();
        $produto = $result->fetch_assoc();
    ?>
    <form method="post" action="index.php?pg=produtos&acao=atualizar">
        <input type="hidden" name="id" value="<?= $produto['id'] ?>">
        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input class="form-control" type="text" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Descrição</label>
            <textarea class="form-control" name="descricao"><?= htmlspecialchars($produto['descricao']) ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Preço</label>
            <input class="form-control" type="number" step="0.01" name="preco" value="<?= $produto['preco'] ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Quantidade</label>
            <input class="form-control" type="number" name="quantidade" value="<?= $produto['quantidade'] ?>" required>
        </div>
        <button class="btn btn-primary" type="submit">Atualizar</button>
        <a class="btn btn-secondary" href="index.php?pg=produtos">Cancelar</a>
    </form>
    <?php else: ?>
    <form method="post" action="index.php?pg=produtos&acao=salvar" class="mb-4">
        <div class="row">
            <div class="col-md-3 mb-3">
                <input class="form-control" type="text" name="nome" placeholder="Nome" required>
            </div>
            <div class="col-md-4 mb-3">
                <input class="form-control" type="text" name="descricao" placeholder="Descrição">
            </div>
            <div class="col-md-2 mb-3">
                <input class="form-control" type="number" step="0.01" name="preco" placeholder="Preço" required>
            </div>
            <div class="col-md-2 mb-3">
                <input class="form-control" type="number" name="quantidade" placeholder="Quantidade" required>
            </div>
            <div class="col-md-1 mb-3">
                <button class="btn btn-success" type="submit">Salvar</button>
            </div>
        </div>
    </form>
    <?php endif; ?>

    <table class="table table-dark table-hover">
        <thead>
            <tr>
                <th>ID</th><th>Nome</th><th>Descrição</th><th>Preço</th><th>Quantidade</th><th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $res = $conn->query('SELECT * FROM produtos ORDER BY id DESC');
        while ($p = $res->fetch_assoc()):
        ?>
            <tr>
                <td><?= $p['id'] ?></td>
                <td><?= htmlspecialchars($p['nome']) ?></td>
                <td><?= htmlspecialchars($p['descricao']) ?></td>
                <td>R$ <?= number_format($p['preco'],2,',','.') ?></td>
                <td><?= $p['quantidade'] ?></td>
                <td>
                    <a class="btn btn-sm btn-primary" href="index.php?pg=produtos&acao=editar&id=<?= $p['id'] ?>">Editar</a>
                    <a class="btn btn-sm btn-danger" href="index.php?pg=produtos&acao=excluir&id=<?= $p['id'] ?>" onclick="return confirm('Excluir este produto?')">Excluir</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
