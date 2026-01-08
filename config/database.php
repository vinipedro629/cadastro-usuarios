<?php
// Configurações do banco de dados
$host = 'localhost'; // Endereço do servidor MySQL
$db = 'cadastro_usuarios'; // Nome do banco de dados
$user = 'root'; // Usuário do banco de dados
$pass = '1234'; // Senha do banco de dados
$charset = 'utf8mb4'; // Conjunto de caracteres utilizado

// DSN (Data Source Name) para conexão PDO com MySQL
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Array de opções para configuração do PDO
$options = [
    // Define o modo de erro para lançar exceções
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    // Define o modo de busca padrão para ARRAY associativo
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    // Cria uma nova instância PDO para conexão com o banco de dados
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Em caso de erro na conexão, exibe mensagem e encerra o script
    die('Erro na conexão: ' . $e->getMessage());
}