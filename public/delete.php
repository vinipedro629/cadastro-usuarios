<?php
require_once '../config/auth.php';
require_once '../config/database.php';

// Protege rota: somente usuários autenticados podem acessar
check_auth();

// Verifica se o ID do usuário foi informado via GET
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$userId = (int)$_GET['id'];

// Busca os dados do usuário a ser excluído (apenas para exibir confirmação)
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    // Usuário não encontrado
    header('Location: index.php');
    exit;
}

$deleted = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Confirma exclusão
    try {
        $del = $pdo->prepare('DELETE FROM users WHERE id = ?');
        $del->execute([$userId]);
        $deleted = true;
    } catch (PDOException $e) {
        $error = 'Erro ao excluir usuário: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Excluir Usuário</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h2>Excluir Usuário</h2>
    <p><a href="index.php">&larr; Voltar para a listagem</a></p>
    <?php if ($deleted): ?>
        <div class="success">
            Usuário excluído com sucesso! <a href="index.php">Voltar para a listagem</a>
        </div>
    <?php elseif ($error): ?>
        <div class="errors"><?= htmlspecialchars($error) ?></div>
    <?php else: ?>
        <p>Tem certeza que deseja excluir o usuário <strong><?= htmlspecialchars($user['name']) ?></strong>?</p>
        <form method="post">
            <button type="submit" class="danger">Sim, excluir</button>
            <a href="index.php" class="button">Cancelar</a>
        </form>
    <?php endif; ?>
</body>
</html>
