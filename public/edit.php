<?php
// Edição de usuário

require_once '../config/auth.php';
require_once '../config/database.php';

// Verifica se está autenticado
check_auth();

// Obtém o ID do usuário a editar
$userId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if (!$userId) {
    header('Location: index.php');
    exit;
}

// Busca dados do usuário atual
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    header('Location: index.php');
    exit;
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validação simples
    if ($name === '') {
        $errors[] = 'O nome é obrigatório.';
    }
    if ($email === '') {
        $errors[] = 'O e-mail é obrigatório.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Formato de e-mail inválido.';
    }

    // Verificar se email já existe para outro usuário
    $stmtMail = $pdo->prepare('SELECT id FROM users WHERE email = ? AND id <> ?');
    $stmtMail->execute([$email, $userId]);
    if ($stmtMail->fetch()) {
        $errors[] = 'Este e-mail já está cadastrado para outro usuário.';
    }

    if (!$errors) {
        try {
            if ($password !== '') {
                // Atualiza com a senha (criptografa novamente)
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmtUp = $pdo->prepare('UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?');
                $stmtUp->execute([$name, $email, $hashed, $userId]);
            } else {
                // Atualiza sem alterar senha
                $stmtUp = $pdo->prepare('UPDATE users SET name = ?, email = ? WHERE id = ?');
                $stmtUp->execute([$name, $email, $userId]);
            }
            $success = true;
            // Atualizar os dados do usuário para exibição após alteração
            $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
        } catch (PDOException $e) {
            $errors[] = 'Erro ao atualizar usuário: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuário</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h2>Editar Usuário</h2>
    <p><a href="index.php">&larr; Voltar para a listagem</a></p>
    <?php if ($success): ?>
        <div class="success">Usuário atualizado com sucesso!</div>
    <?php endif; ?>

    <?php if ($errors): ?>
        <div class="errors">
            <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="edit.php?id=<?= urlencode($userId) ?>" method="post">
        <label>
            Nome:
            <input type="text" name="name" value="<?= htmlspecialchars($_POST['name'] ?? $user['name']) ?>" required>
        </label>
        <br>
        <label>
            E-mail:
            <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? $user['email']) ?>" required>
        </label>
        <br>
        <label>
            Nova senha:
            <input type="password" name="password">
            <small>(Preencha apenas se desejar alterar)</small>
        </label>
        <br>
        <button type="submit">Salvar Alterações</button>
    </form>
</body>
</html>
