<?php
require_once '../config/database.php';
require_once '../config/auth.php';

$errors = [];

// Processar POST de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $errors[] = 'Informe o e-mail e a senha.';
    } else {
        try {
            $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                login_user($user);
                header('Location: index.php');
                exit;
            } else {
                $errors[] = 'E-mail ou senha incorretos.';
            }
        } catch (PDOException $e) {
            $errors[] = 'Erro ao acessar o banco de dados.';
        }
    }
}

// Verifica se o usuário já está logado
$is_logged_in = !empty($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <div style="margin-bottom: 1em;">
            <a href="create.php" class="button">Cadastrar novo usuário</a>
            <?php if ($is_logged_in): ?>
                <a href="logout.php" class="button">Sair</a>
            <?php endif; ?>
        </div>
        <?php if ($errors): ?>
            <div class="errors">
                <ul>
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <form method="post" action="login.php">
            <label>
                E-mail:
                <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </label>
            <label>
                Senha:
                <input type="password" name="password" required>
            </label>
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>
