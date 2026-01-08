<?php
// Verificação de login utilizando sessão

session_start();

/**
 * Verifica se o usuário está autenticado.
 * Se não estiver, redireciona para a tela de login.
 */
function check_auth()
{
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Realiza o login do usuário, salvando o ID e nome na sessão.
 */
function login_user($user)
{
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
}

/**
 * Realiza o logout do usuário, destruindo a sessão.
 */
function logout_user()
{
    session_unset();
    session_destroy();
}
 
/**
 * Retorna o nome do usuário autenticado ou null.
 */
function current_user_name()
{
    return $_SESSION['user_name'] ?? null;
}

