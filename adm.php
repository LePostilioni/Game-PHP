<?php
include_once 'template.php';
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
                    <h3>Bem-vindo(a), <?php echo $nome; ?>!</h3>
                    <h4>Nível de Acesso: <?php echo $gm_level; ?></h4>
                    <h4>Email: <?php echo $email; ?></h4>
                    <h4>Senha: <?php echo $senha; ?></h4>
                    <div class="text-center">
                        <a href="index.php" class="btn btn-outline-dark botao_maior">Voltar</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

</body>

</html>