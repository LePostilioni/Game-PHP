<?php
include_once 'template.php';

// Obter o número de usuários logados
$NumeroUsuariosLogados = count($_SESSION);
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
                    <h4>Usuarios Logados: <?php echo $NumeroUsuariosLogados; ?></h4>
                    <br><hr>
                    <div class="text-center">
                        <a href="index.php" class="btn btn-outline-dark botao_maior">Voltar</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

</body>

</html>