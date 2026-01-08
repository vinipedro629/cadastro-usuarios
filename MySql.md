--- Abra o prompt de comando e acesse o MySQL com seu usuário e senha:
mysql -u seu_usuario -p

-- Após acessar o MySQL, digite os seguintes comandos linha por linha:

CREATE DATABASE cadastro_usuarios CHARACTER SET utf8mb4;
USE cadastro_usuarios;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Pronto! O banco de dados e a tabela foram criados.


