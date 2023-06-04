<?php
include_once 'template.php';
// numero do dado lançado
$dadolancado = "0";

// Variáveis ainda não implementadas
$char_criado = false;

?>

<head>
    <title>Página do jogo</title>
</head>

<body>
    <!-- Verifica se está logado -->
    <?php if (!isset($_SESSION["logado"])) : ?>
        <div class="container text-center position-absolute top-50 start-50 translate-middle">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <br>
                    <h1 class="font-weight-bold text-danger">Você não está logado!</h1>
                    <hr>
                    <a class="btn btn-outline-dark botao_maior" href="index.php">Voltar</a>
                    <br><br><br><br>
                </div>
            </div>
        </div>
    <?php else : ?>
        <?php if (!isset($_SESSION["char_criado"])) : ?>
            <div class="container text-center position-absolute top-50 start-50 translate-middle">
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <br>
                        <h1 class="font-weight-bold text-danger">Você não tem personagem para jogar!</h1>
                        <hr>
                        <a class="btn btn-outline-dark botao_menor" href="index.php">Criar agora!</a>
                        <a class="btn btn-outline-dark botao_menor" href="index.php">Voltar</a>
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
                        <div class="text-center">
                            <a href="index.php" class="btn btn-outline-dark botao_maior">Voltar</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</body>


</html>