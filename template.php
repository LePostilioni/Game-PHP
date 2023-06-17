<?php
// Versão do código
$code_version = '1.3.3';

// Define o tempo máximo de vida da sessão em segundos (30 minutos)
session_set_cookie_params(1800); // 1800 segundos = 30 minutos

// Inicie a sessão
session_start();

// Regenerar o ID da sessão a cada interação
session_regenerate_id(true);

$session_timeout = 900; // Tempo limite de inatividade em segundos

// Variáveis para armazenar as mensagens
$message2 = '';

// Conexão com o banco de dados
include('connection.php');

// Verifica se o tempo excedeu
if ($logado && isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > $session_timeout) {
    // Tempo limite de inatividade excedido, fazer logout ou redirecionar para a página de logout
    header("Location: logout.php");
    exit;
} else {
    // Atualize o tempo da última atividade
    $_SESSION['last_activity'] = time();
}

// Verifica se o "sair" foi enviado
if (isset($_POST['sair'])) {
    include('logout.php');
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
            background-color: rgba(30, 30, 30, 0.95);
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

        .laranjinha {
            color: black;
            background-color: #F8A762;
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
        <div class="informacoesdev"> Informações ao Desenvolvedor:<br>Logado: <?php echo (!$logado) ? "Não" : "Sim"; ?> / ADM: <?php echo (!$gm_level > 0) ? "Não" : "Sim"; ?> / Nome: <?php echo $nome; ?> / E-mail: <?php echo $email; ?> / Senha: <?php echo $senha; ?> / ID: <?php echo $_SESSION["id"]; ?> / Último login: <?php echo $ultimo_login; ?>
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
        <!-- Botão sair depois de logado -->
        <?php if ($logado) : ?>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="Sair" style="display: block;">
                <button type="submit" name="sair" class="btn btn-outline-dark botao_maior laranjinha">Sair</button>
            </form>
        <?php endif; ?>

        <div class="message2">
            <?php echo $message2; ?> - Versão: <?php echo $code_version; ?>
        </div>
        <hr>
        <div class="desenvolvido_por">
            Desenvolvido por Leandro Postilioni Aires - 2023
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