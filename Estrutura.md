cadastro-usuarios/
│
├── config/
│   ├── database.php        # Conexão PDO
│   └── auth.php            # Verificação de login (sessão)
│
├── public/
├── assets/style.css
│   ├── index.php           # Listagem de usuários
│   ├── create.php          # Cadastro de usuário
│   ├── edit.php            # Edição de usuário
│   ├── delete.php          # Exclusão de usuário
│   ├── register.php          # 
│   ├── login.php           # Login
│   ├── logout.php          # Logout

├── src/
│   └── User.php            # Classe com CRUD
│
├── assets/
│   └── style.css           # Estilos simples
│
├── database.sql            # Script do banco
│
└── .htaccess               # (opcional) segurança básica
