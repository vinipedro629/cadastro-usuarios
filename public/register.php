
<?php
require_once '../config/database.php';
require_once '../src/User.php';

// --- Página de Registro de Usuário ---

$user = new User($pdo);
$errors = [];
$success = false;

// Se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validação simples
    if ($name === '') {
        $errors[] = 'O nome é obrigatório.';
    }
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Informe um e-mail válido.';
    }
    if ($password === '') {
        $errors[] = 'A senha é obrigatória.';
    }

    if (empty($errors)) {
        // Tenta criar o usuário
        try {
            $created = $user->create($name, $email, $password);
            if ($created) {
                $success = true;
            } else {
                $errors[] = 'Falha ao cadastrar usuário.';
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $errors[] = 'E-mail já cadastrado.';
            } else {
                $errors[] = 'Erro no banco de dados: ' . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Registrar Usuário</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h2>Registrar Novo Usuário</h2>
    <p><a href="login.php">&larr; Voltar para o login</a></p>
    <?php if ($success): ?>
        <div class="success">
            Usuário registrado com sucesso! <a href="login.php">Clique aqui para fazer login</a>.
        </div>
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

    <form action="register.php" method="post">
        <label>
            Nome:
            <input type="text" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
        </label>
        <br>
        <label>
            E-mail:
            <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
        </label>
        <br>
        <label>
            Senha:
            <input type="password" name="password" required>
        </label>
        <br>
        <button type="submit">Registrar</button>
    </form>
</body>
</html>
