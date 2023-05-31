<?php
// Inicie a sessão
session_start();

// Versão do código
$code_version = '1.2.0';

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

// Verifica se o "sair" foi enviado
if (isset($_POST['sair'])) {
    // Limpar os dados da sessão
    $_SESSION = array();
    session_destroy();

    // Redireciona para a página de login
    header("Location: index.php");
    exit;
}

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
            margin-bottom: 10px;
        }

        .message2 {
            text-align: center;
            padding-top: -111px;
            padding-bottom: -111px;
            margin-bottom: -5px;
            margin-top: -25px;
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
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.1);
        }

        .desenvolvido_por {
            position: sticky;
            display: inline-block;
            animation: marquee 15s linear infinite;
            padding-bottom: 5px;
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

    <!-- Rodapé da página -->
    <footer>
        <div class="message2">
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