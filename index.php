<?php
// Variável para armazenar as mensagens
$message = '';
$message2 = '';
$logado = false; // Variável para controlar se o usuário está logado
$logado_sql = false; // Variável para controlar quantos usuários estão logados pelo servidor
$char_criado = false; // Varável de controle de administrador
$gm_level = '0'; // Varável de controle de administrador

// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "leandro27**";
$dbname = "leandrophpgame";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
} else {
    if ($logado == false) {
        $message2 = "<br>Servidor <span style='font-weight: bold; color: green;'>Online</span> - Você não está logado";
    } else {
        $message2 = "<br>Servidor <span style='font-weight: bold; color: green;'>Online</span> - Você está logado";
    }
}

// Processar o formulário de cadastro
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["cadastro"])) {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = $_POST["senha"];

    // Verificar se o email já está cadastrado
    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $message = "<span style='color: red;'>Este email já está cadastrado. Por favor, tente novamente.</span>";
    } else {
        // Inserir os dados do usuário no banco de dados
        $sql = "INSERT INTO usuarios (nome, email, senha, gm_level, criado_em) VALUES ('$nome', '$email', '$senha', 0, CURRENT_TIMESTAMP)";
        if ($conn->query($sql) === TRUE) {
            $message = "Cadastro realizado com sucesso!";
        } else {
            $message = "Erro no cadastro: " . $conn->error;
        }
    }
}

// Processar o formulário de login
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["login"])) {
    $email = $_POST["email-login"];
    $senha = $_POST["senha-login"];

    // Verificar as credenciais do usuário
    $sql = "SELECT * FROM usuarios WHERE email = '$email' AND senha = '$senha'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $logado = true;
        $message = "Login realizado com sucesso!";
        $message2 = "<br><span style='font-weight: bold; color: green;'>Você está logado</span>";
    } else {
        $message = "<span style='color: red;'>Email ou senha inválidos. Por favor, tente novamente.</span>";
    }
}
// Rodapé
$footerText = "Desenvolvido por Leandro Postilioni Aires - 2023";
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.css">
    <!-- Para usar os icones do Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .container {
            margin-top: 1px;
        }

        h1,
        h3 {
            color: #f58220;
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group small {
            color: #fff;
        }

        .btn-shake {
            animation: shake 0.5s;
        }

        @keyframes shake {
            0% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            50% {
                transform: translateX(5px);
            }

            75% {
                transform: translateX(-5px);
            }

            100% {
                transform: translateX(0);
            }
        }

        footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
        }

        footer div {
            display: inline-block;
            animation: marquee 15s linear infinite;
        }

        button {
            width: 150px;
        }

        @keyframes marquee {
            0% {
                transform: translateX(100%);
            }

            50% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }
    </style>
    <title>Página Inicial</title>
</head>

<body data-bs-theme="dark">
<!-- Botão de mudar o tema dark ou light -->
<div class="form-check form-switch mx-4">
    <i class="fa-solid fa-circle-half-stroke"></i>
      <input
        class="form-check-input p-2"
        type="checkbox"
        role="switch"
        id="flexSwitchCheckChecked"
        checked
        onclick="myFunction()"
      />
    </div>
    <!-- Conteúdo da página -->
    <div class="container text-center">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h1>Bem-vindo ao Meu Projeto</h1>
                <p>Divirta-se com nosso incrível game!</p>
                <hr>

                <div class="message">
                    <?php echo $message; ?>
                    <?php echo $message2; ?>
                </div>

                <?php if (!$logado) : ?>
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="login-form">
                      <h3 id="login-heading">Login</h3>
                        <div class="form-group">
                            <i class="fa-solid fa-envelope"></i>
                            <label for="email-login">Email:</label>
                            <input type="email" class="form-control" id="email-login" name="email-login" required>
                        </div>
                        <div class="form-group">
                            <i class="fa-solid fa-lock"></i>
                            <label for="senha-login">Senha:</label>
                            <input type="password" class="form-control" id="senha-login" name="senha-login" required>
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-primary" name="login" onclick="addShakeEffect(this)">Entrar</button>
                        <button type="button" class="btn btn-primary" onclick="showCadastroForm()">Cadastrar-se</button>
                    </form>

                        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="cadastro-form" style="display: none;">
                            <h3>Cadastre-se</h3>
                            <div class="form-group">
                                <i class="fa-solid fa-user"></i>
                                <label for="nome">Nome:</label>
                                <input type="text" class="form-control" id="nome" name="nome" required pattern="[A-Za-z\s-]{10,30}">
                                <small>Nome e sobrenome, somente letras. Mínimo de 10 caracteres e máximo de 30.</small>
                            </div>
                            <div class="form-group">
                                <i class="fa-solid fa-envelope"></i>
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <i class="fa-solid fa-lock"></i>
                                <label for="senha">Senha:</label>
                                <input type="password" class="form-control" id="senha" name="senha" required pattern="[A-Za-z0-9\-]{8,30}">
                                <small>Somente caracteres e números, sem espaços. Mínimo de 8 caracteres e máximo de 30.</small>
                            </div>
                            <button type="submit" class="btn btn-primary" name="cadastro" onclick="addShakeEffect(this)">Cadastrar</button>
                            <button type="button" class="btn btn-primary" onclick="showLoginForm()">Fazer Login</button>
                        </form>

                    <script>
                        function showCadastroForm() {
                          document.getElementById('login-form').style.display = 'none';
                          document.getElementById('cadastro-form').style.display = 'block';
                        }

                        function showLoginForm() {
                          document.getElementById('login-form').style.display = 'block';
                          document.getElementById('cadastro-form').style.display = 'none';
                        }
                    </script>
                <?php endif; ?>

            </div>
        </div>
    </div>
    <!-- Rodapé da página -->
    <footer>
        <div>
            <?php echo $footerText; ?>
        </div>
    </footer>
    <!-- Script do dark theme do bootstrap -->
    <script>
      function myFunction() {
        var element = document.body;
        element.dataset.bsTheme =
          element.dataset.bsTheme == "light" ? "dark" : "light";
      }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="css/bootstrap.js"></script>

</body>

</html>
