<?php
// Conexão com o banco de dados
include('connection.php');

    // Atualizar o status de login no banco de dados
    $query = "UPDATE usuarios SET logado_sql = 0 WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->close();

// Limpar os dados da sessão
$_SESSION = array();
session_unset();
session_destroy();



// Redirecionar para a página de login
$deslogou = "1";
$url = "index.php?deslogou=" . urlencode($deslogou);
header("Location: " . $url);
exit;

?>