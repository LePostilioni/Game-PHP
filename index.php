<?php
// Inicie a sessão
session_start();

// Versão do código
$code_version = '1.1.0';

// Variáveis para armazenar as mensagens
$message = '';
$message2 = '';

// Variáveis ainda não implementadas
$logado_sql = false; // Variável para controlar quantos usuários estão logados pelo servidor
$char_criado = false; // Varável de controle de administrador

// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "leandro27**";
$dbname = "leandrophpgame";

// Verifique se as variaveis de sessão estão definidas
if (isset($_SESSION["logado"])) {
    $logado = $_SESSION["logado"];
} else {
    $logado = false; // Variável para controlar se o usuário está logado
}
if (isset($_SESSION["gm_level"])) {
    $gm_level = $_SESSION["gm_level"];
} else {
    $gm_level = '0'; // Varável de controle de administrador
}
if (isset($_SESSION["email"])) {
    $email = $_SESSION["email"];
} else {
    $email = 'nenhum';
}
if (isset($_SESSION["nome"])) {
    $nome = $_SESSION["nome"];
} else {
    $nome = 'nenhum';
}
if (isset($_SESSION["senha"])) {
    $senha = $_SESSION["senha"];
} else {
    $senha = 'nenhum';
}
if (isset($_SESSION["senha_atual"])) {
    $senha_atual = $_SESSION["senha_atual"];
} else {
    $senha_atual = 'nenhum';
}
if (isset($_SESSION["nova_senha"])) {
    $nova_senha = $_SESSION["nova_senha"];
} else {
    $nova_senha = 'nenhum';
}
if (isset($_SESSION["confirmar_senha"])) {
    $confirmar_senha = $_SESSION["confirmar_senha"];
} else {
    $confirmar_senha = 'nenhum';
}

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
} else {
    if (!$logado) {
        $message2 = "<br>Servidor <span style='font-weight: bold; color: green;'>Online</span> - Você não está logado";
    } else {
        $message2 = "<br>Servidor <span style='font-weight: bold; color: green;'>Online</span> - Você está logado";
    }
}

// Processar o formulário de cadastro
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["cadastro"])) {
    if (isset($_POST['nome'])) {
        $nome = $_POST['nome'];
    } else {
        $nome = "";
    }

    if (isset($_POST['email'])) {
        $email = $_POST['email'];
    } else {
        $email = "";
    }

    if (isset($_POST['senha'])) {
        $senha = $_POST['senha'];
    } else {
        $senha = "";
    }

    // Formatar o nome do usuário
    $nome = ucwords(strtolower($nome));

    // Verificar se o email já está cadastrado
    $query = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message = "<span style='color: #f34336;'>Este email já está cadastrado. Por favor, tente novamente.</span>";
    } else {
        // Inserir os dados do usuário no banco de dados
        $query = "INSERT INTO usuarios (nome, email, senha, gm_level, criado_em) VALUES (?, ?, ?, 0, CURRENT_TIMESTAMP)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $nome, $email, $senha);

        if ($stmt->execute()) {
            $message = "Cadastro realizado com sucesso!";
        } else {
            $message = "Erro no cadastro: " . $stmt->error;
        }
    }
    $stmt->close();
}

// Processar o formulário de login
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["login"])) {
    $email = $_POST["email-login"];
    $senha = $_POST["senha-login"];

    // Verificar as credenciais do usuário
    $query = "SELECT * FROM usuarios WHERE email = ? AND senha = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email, $senha);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $logado = true;
        $_SESSION["logado"] = $logado;
        $message = "Login realizado com sucesso!";

        // Recuperar os dados do usuário do banco de dados
        $row = $result->fetch_assoc();
        $nome = $row["nome"];
        $gm_level = $row["gm_level"];
        $senha = $row["senha"];
        $email = $row["email"];
        $_SESSION["nome"] = $nome;
        $_SESSION["gm_level"] = $gm_level;
        $_SESSION["senha"] = $senha;
        $_SESSION["email"] = $email;

        $message2 = "<br><span style='font-weight: bold; color: green;'>Você está logado</span>";
    } else {
        $message = "<span style='color: #f34336;'>Email ou senha inválidos. Por favor, tente novamente.</span>";
    }
    $stmt->close();
}

// Verifica se o "sair" foi enviado
if (isset($_POST['sair'])) {
    // Limpar os dados da sessão
    $_SESSION = array();
    session_destroy();

    // Redireciona para a página de login
    header("Location: index.php");
    exit;
}

// Verificar se o formulário de mudança de senha foi enviado
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['mudar-senha'])) {
    if (isset($_POST['senha-atual'])) {
        $senha_atual = $_POST['senha-atual'];
    } else {
        $senha_atual = "";
    }

    if (isset($_POST['nova-senha'])) {
        $nova_senha = $_POST['nova-senha'];
    } else {
        $nova_senha = "";
    }

    if (isset($_POST['confirmar-senha'])) {
        $confirmar_senha = $_POST['confirmar-senha'];
    } else {
        $confirmar_senha = "";
    }

    // Verificar se a senha atual está correta
    $query = "SELECT senha FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($senha_banco);
        $stmt->fetch();

        // Verificar se a senha atual está correta
        if ($senha_atual === $senha_banco) {
            // A senha atual está correta, você pode prosseguir com a atualização da senha
            // Verificar se a nova senha e a confirmação da nova senha coincidem
            if ($nova_senha === $confirmar_senha) {
                // Preparar a consulta para atualizar a senha no banco de dados
                $query_atualizar = "UPDATE usuarios SET senha = ? WHERE email = ?";
                $stmt_atualizar = $conn->prepare($query_atualizar);
                $stmt_atualizar->bind_param("ss", $nova_senha, $email);

                // Executar a atualização
                if ($stmt_atualizar->execute()) {
                    $message = "Senha alterada com sucesso!";
                } else {
                    $message = "<span style='color: #f34336;'>Ocorreu um erro ao atualizar a senha. Tente novamente.</span>";
                }

                // Fechar a declaração de atualização
                $stmt_atualizar->close();
            } else {
                // Exibir mensagem de erro caso as senhas não coincidam
                $message = "<span style='color: #f34336;'>As senhas não coincidem. Tente novamente.</span>";
            }
        } else {
            $message = "<span style='color: #f34336;'>A senha atual está incorreta. Tente novamente.</span>";
        }
    } else {
        // Usuário não encontrado ou múltiplos registros encontrados (algo está errado)
        $message = "<span style='color: #f34336;'>O usuário não foi encontrado ou houve um erro no banco de dados.</span>";
    }
    $stmt->close();
}

$conn->close();
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
        h1,
        h3 {
            color: #f58220;
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
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

        .form-group small {
            font-size: 12px;
        }

        footer {
            text-align: center;
        }

        .desenvolvido_por {
            position: sticky;
            display: inline-block;
            animation: marquee 15s linear infinite;
        }

        .desenvolvido_por:hover {
            animation-play-state: paused;
        }

        .botao_menor {
            width: 200px;
            background-color: #f58220;
        }

        .botao_menor:hover {
            border-color: #495057;
        }

        .botao_maior {
            width: 400px;
            background-color: #f58220;
        }

        .botao_maior:hover {
            border-color: #495057;
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

        .informacoesdev {
            font-size: 12px;
            color: greenyellow;
            background-color: #303030;
        }
    </style>
    <title>Página Inicial</title>
</head>

<body data-bs-theme="dark">
    <!-- Se for administrador vai aparecer informações em cima -->
    <?php if ($gm_level > 0) : ?>
        <div class="informacoesdev"> Informações ao Desenvolvedor:<br>Logado: <?php echo (!$logado) ? "Não" : "Sim"; ?> / ADM: <?php echo (!$gm_level > 0) ? "Não" : "Sim"; ?> / Nome: <?php echo $nome; ?> / E-mail: <?php echo $email; ?> / Senha: <?php echo $senha; ?>
            <br>Senha atual: <?php echo $senha_atual; ?> / Nova senha: <?php echo $nova_senha; ?> / Confirma senha: <?php echo $confirmar_senha; ?>
        </div>
    <?php endif; ?>
    <!-- Botão de mudar o tema dark ou light -->
    <div class="form-check form-switch mx-1">
        <i class="fa-solid fa-circle-half-stroke"></i>
        <input class="form-check-input p-2" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked onclick="Temabootstrap()" />
    </div>
    <!-- Conteúdo da página -->
    <div class="container text-center">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <br><br>
                <h1>Bem-vindo(a) ao Projeto</h1>
                <p>Divirta-se com nosso game!</p>
                <hr>

                <div class="message">
                    <?php echo $message; ?>
                </div>
                <!-- Formularios de login e cadastro -->
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
                        <br>
                        <button type="submit" class="btn btn-outline-dark botao_menor" name="login" onclick="addShakeEffect(this)">Entrar</button>
                        <button type="button" class="btn btn-outline-warning botao_menor" disabled onclick="showEsqueciForm()">Esqueci a senha</button> <!-- Ainda não implementado -->
                        <hr>
                        <button type="button" class="btn btn-outline-dark botao_maior" onclick="showCadastroForm()">Cadastrar-se</button>
                    </form>

                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="cadastro-form" style="display: none;">
                        <h3>Cadastre-se</h3>
                        <div class="form-group">
                            <i class="fa-solid fa-user"></i>
                            <label for="nome">Nome:</label>
                            <input type="text" class="form-control" id="nome" name="nome" placeholder="Fulano de Tal" required pattern="[A-Za-zÀ-ÿ\s-]{10,30}">
                            <small>Nome e sobrenome, somente letras. Mínimo de 10 caracteres e máximo de 30.</small>
                        </div>
                        <div class="form-group">
                            <i class="fa-solid fa-envelope"></i>
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="fulano@exemplo.com" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                        </div>
                        <div class="form-group">
                            <i class="fa-solid fa-lock"></i>
                            <label for="senha">Senha:</label>
                            <input type="password" class="form-control" id="senha" name="senha" placeholder="Ex:Abcd123*&" required pattern="[A-Za-zÀ-ÿ0-9\-]{8,30}">
                            <small>Sem espaços. Mínimo de 8 caracteres e máximo de 30.</small>
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-outline-dark botao_menor" name="cadastro" onclick="addShakeEffect(this)">Cadastrar</button>
                        <button type="button" class="btn btn-outline-dark botao_menor" onclick="showLoginForm()">Fazer Login</button>
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

                <?php else : ?>
                    <!-- Formulários depois de logado -->
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="bemvindo" style="display: block;">
                        <h3>Seja muito bem-vindo(a), <?php echo $_SESSION['nome']; ?>!</h3>
                        <br>
                        <a class="btn btn-outline-dark botao_menor" href="game.php">Jogar</a>
                        <br><br>
                        <?php if ($_SESSION['gm_level'] > 0) : ?>
                            <a class="btn btn-outline-dark botao_menor" href="adm.php">Administração</a>
                            <br><br>
                        <?php endif; ?>
                        <button type="button" class="btn btn-outline-dark botao_menor" onclick="showChangePasswordForm()">Mudar Senha</button>
                        <br><br>
                        <hr>
                        <button type="submit" name="sair" class="btn btn-outline-dark botao_maior">Sair</button>
                    </form>

                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="change-password-form" style="display: none;">
                        <h3>Mudar Senha</h3>
                        <div class="form-group">
                            <i class="fa-solid fa-lock"></i>
                            <label for="senha-atual">Senha Atual:</label>
                            <input type="password" class="form-control" id="senha-atual" name="senha-atual" required>
                        </div>
                        <div class="form-group">
                            <i class="fa-solid fa-lock"></i>
                            <label for="nova-senha">Nova Senha:</label>
                            <input type="password" class="form-control" id="nova-senha" name="nova-senha" required pattern="[A-Za-zÀ-ÿ0-9\-]{8,30}">
                        </div>
                        <div class="form-group">
                            <i class="fa-solid fa-lock"></i>
                            <label for="confirmar-senha">Confirmar Nova Senha:</label>
                            <input type="password" class="form-control" id="confirmar-senha" name="confirmar-senha" required>
                            <small>*Sem espaços. Mínimo de 8 caracteres e máximo de 30.</small>
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-outline-dark botao_menor" name="mudar-senha" onclick="addShakeEffect(this)">Salvar</button>
                        <button type="button" class="btn btn-outline-dark botao_menor" onclick="cancelChangePassword()">Cancelar</button>
                    </form>

                    <script>
                        function showBemvindo() {
                            document.getElementById('change-password-form').style.display = 'none';
                            document.getElementById('login-form').style.display = 'none';
                        }

                        function showChangePasswordForm() {
                            document.getElementById('bemvindo').style.display = 'none';
                            document.getElementById('change-password-form').style.display = 'block';
                        }

                        function cancelChangePassword() {
                            document.getElementById('bemvindo').style.display = 'block';
                            document.getElementById('change-password-form').style.display = 'none';
                        }
                    </script>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <br>
    <!-- Rodapé da página -->
    <footer>
        <div class="message">
            <?php echo $message2; ?>
        </div>
        <hr>
        <div class="desenvolvido_por">
            Desenvolvido por Leandro Postilioni Aires - 2023 / Versão: <?php echo $code_version; ?>
        </div>
    </footer>
    <script>
        // Função para adicionar efeito de shake nos botões
        function addShakeEffect(button) {
            button.classList.add('btn-shake');
            setTimeout(function() {
                button.classList.remove('btn-shake');
            }, 500);
        }

        // Script do dark theme do bootstrap
        function Temabootstrap() {
            var element = document.body;
            element.dataset.bsTheme =
                element.dataset.bsTheme == "light" ? "dark" : "light";
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="css/bootstrap.js"></script>

</body>

</html>