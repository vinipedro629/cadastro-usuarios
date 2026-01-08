<?php
require_once '../config/database.php';
require_once '../config/auth.php';

check_auth();

// Busca lista de usuários
try {
    $stmt = $pdo->query('SELECT id, name, email, created_at FROM users ORDER BY id DESC');
    $usuarios = $stmt->fetchAll();
} catch (PDOException $e) {
    $erro = 'Erro ao buscar usuários: ' . $e->getMessage();
    $usuarios = [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Usuários Cadastrados</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="container">
        <h2>Usuários cadastrados</h2>
        <p>
            Olá, <?= htmlspecialchars(current_user_name()) ?>!
            <a href="logout.php" class="button">Sair</a>
        </p>
        <p>
            <a href="create.php" class="button">Cadastrar novo usuário</a>
        </p>

        <?php if (!empty($erro)): ?>
            <div class="errors"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <?php if (!$usuarios): ?>
            <p>Nenhum usuário cadastrado ainda.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Data de Cadastro</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?= htmlspecialchars($usuario['id']) ?></td>
                            <td><?= htmlspecialchars($usuario['name']) ?></td>
                            <td><?= htmlspecialchars($usuario['email']) ?></td>
                            <td><?= htmlspecialchars($usuario['created_at']) ?></td>
                            <td>
                                <a href="edit.php?id=<?= urlencode($usuario['id']) ?>">Editar</a>
                                <a href="delete.php?id=<?= urlencode($usuario['id']) ?>" class="danger" onclick="return confirm('Tem certeza que deseja excluir este usuário?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
