<?php
// logout.php: efetua logout destruindo a sessão do usuário

session_start();
session_unset();
session_destroy();

// Redireciona para a tela de login
header('Location: login.php');
exit;
