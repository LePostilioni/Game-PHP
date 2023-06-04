<?php
include_once 'template.php';

// Consulta SQL para contar os usuários logados
$sql = "SELECT COUNT(*) as total FROM usuarios WHERE logado_sql = 1";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$numero_usuarios_logados = $row['total'];
?>

<head>
    <title>Página de Administração</title>
</head>

<body>
    <!-- Verifica se é administrador -->
    <?php if (!isset($_SESSION["logado"]) || !isset($_SESSION["gm_level"]) || $_SESSION["gm_level"] <= 0) : ?>
        <div class="container text-center position-absolute top-50 start-50 translate-middle">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <br>
                    <h1 class="font-weight-bold text-danger">Essa área é somente para administradores!</h1>
                    <h1 class="font-weight-bold small">Você não está logado como um...</h1>
                    <hr>
                    <a class="btn btn-outline-dark botao_maior" href="index.php">Voltar</a>
                    <br><br><br><br>
                </div>
            </div>
        </div>
    <?php else : ?>
        <!-- Se for administrador -->
        <div class="container text-center">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <h1>Página de Administração</h1>
                    <h3>Seja muito bem-vindo(a) administrador, <?php echo $nome; ?>!</h3>
                    <h4>Seu Nível de Acesso: <?php echo $gm_level; ?> / 9</h4>
                    <h4>Usuários logados no momento: <?php echo $numero_usuarios_logados; ?></h4>
                    <h4>Seu último login: <?php echo $_SESSION["ultimo_login"]; ?></h4>
                    <hr>
                    <div class="text-center">
                        <a class="btn btn-outline-dark botao_menor" href="game.php">Jogar</a>
                        <a class="btn btn-outline-dark botao_menor" href="index.php" >Voltar</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

</body>

</html>