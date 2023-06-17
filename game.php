<?php
include_once 'template.php';
// numero do dado lançado
$dadolancado = "0";
?>

<head>
    <title>Página do jogo</title>
</head>

<body>
    <!-- Verifica se está logado -->
    <?php if (!isset($_SESSION["logado"])) : ?>
        <!-- Se não estiver logado -->
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
        <?php elseif ($_SESSION["char_criado"] == 0) : ?>
        <!-- Se não tiver personagem criado -->
        <div class="container text-center position-absolute top-50 start-50 translate-middle">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <br>
                    <h1 class="font-weight-bold text-danger">Você não tem personagem vivo para jogar!</h1>
                    <hr>
                    <a class="btn btn-outline-dark botao_menor" href="createchar.php">Criar agora!</a>
                    <a class="btn btn-outline-dark botao_menor" href="index.php">Voltar</a>
                    <br><br><br><br>
                </div>
            </div>
        </div>
    <?php else : ?>
        <!-- Se Já tiver logado e com char criado -->
        <div class="container text-center">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <h1>Bora jogar!</h1>
                    <h3>Bem-vindo(a), <?php echo $_SESSION["nome_completo"]; ?>!</h3>
                    <div class="text-center">
                        <a href="index.php" class="btn btn-outline-dark botao_maior">Voltar</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</body>
</html>