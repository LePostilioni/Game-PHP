<?php
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
if (isset($_GET['deslogou']) && $_GET['deslogou'] == 1) {
    $message = "<span style='color: #f34336;'>Você foi desconectado.</span>";
    $_SESSION["message"] = $message;
} else {
    $deslogou = "0";
}
if (isset($_SESSION["message"])) {
    $message = $_SESSION["message"];
} else {
    $message = " ";
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
if (isset($_SESSION["id_usuario"])) {
    $id_usuario = $_SESSION["id_usuario"];
} else {
    $id_usuario = 'nenhum';
}
if (isset($_SESSION["ultimo_login"])) {
    $ultimo_login = $_SESSION["ultimo_login"];
} else {
    $ultimo_login = 'nenhum';
}

// Estabelecer a conexão
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
} else {
    if (!$logado) {
        $message2 = "<br>Você não está logado - Servidor <span style='font-weight: bold; color: green;'>Online</span>";
    } else {
        $message2 = "<br>Você está logado - Servidor <span style='font-weight: bold; color: green;'>Online</span>";
    }
}

// Verificar se ocorreu algum erro na conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
