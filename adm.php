<?php
include_once 'template.php';

if (!isset($_SESSION["logado"]) || !isset($_SESSION["gm_level"]) || $_SESSION["gm_level"] <= 0) {
    // Verifica se o usuário não está logado ou não tem um nível de administrador válido
    echo "Essa área é somente para administradores.";
    echo "<br>";
    echo "<button onclick='history.back()'>Voltar</button>";
    exit;
}

?>

<head>
    <title>Página de Administração</title>
</head>

<body>
<div class="container text-center">
        <div class="row">
            <div class="col-md-6 offset-md-3">
        <h1>Página de Administração</h1>
        <h3>Bem-vindo(a), <?php echo $nome; ?>!</h3>
        <h4>Nível de Acesso: <?php echo $gm_level; ?></h4>
        <h4>Email: <?php echo $email; ?></h4>
        <h4>Senha: <?php echo $senha; ?></h4>

        <div class="message">
            <?php
            if (isset($_SESSION["mensagem"])) {
                echo $_SESSION["mensagem"];
                unset($_SESSION["mensagem"]);
            }
            ?>
        </div>


        <div class="text-center">
            <a href="index.php" class="btn btn-outline-dark botao_maior">Voltar</a>
        </div>
        </div>
        </div>
    </div>

</body>

</html>