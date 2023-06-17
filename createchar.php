<?php
include_once 'template.php';
// numero do dado lançado
$dadolancado = "0";
?>

<head>
    <title>Criação de personagem</title>
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
                    <a class="btn btn-outline-dark botao_maior" href="game.php">Voltar</a>
                    <br><br><br><br>
                </div>
            </div>
        </div>
    <?php elseif ($_SESSION["char_criado"] == 0) : ?>
        <!-- Se não tiver personagem criado -->
        <div class="container text-center">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <br>
                    <h1 class="font-weight-bold">Vamos criar seu personagem!</h1>
                    <hr>
                    <h4>Faça sua escolha com sabedoria!
                    <br>Vamos lá:</h4>
                    <h6>Sua Energia Universal atual é:</h6>
                    <div class="laranjinha">$<?php echo $_SESSION["energia_universal"]?></div>
                    <hr>Energia universal: $0 - 
                    <a class="btn btn-outline-dark botao_menor" href="createchar.php">Personagem Aleatório</a>
                    <hr>Energia universal: $100 - 
                    <a class="btn btn-outline-dark botao_menor laranjinha" href="createchar.php">Personagem com sorte</a>
                    <hr>Energia universal: $500 - 
                    <a class="btn btn-outline-dark botao_menor" href="createchar.php">Personagem abençoado</a>
                    <hr>Energia universal: $1500 - 
                    <a class="btn btn-outline-dark botao_menor laranjinha" href="createchar.php">Personagem como quer</a>
                    <hr>
                    <a class="btn btn-outline-dark botao_menor" href="game.php">Voltar</a>
                    <br><br><br><br><br><br><br><br>
                </div>
            </div>
        </div>
    <?php else : ?>
        <!-- Se for administrador -->
        <div class="container text-center position-absolute top-50 start-50 translate-middle">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <br>
                    <h1 class="font-weight-bold text-danger">Você já tem personagem criado!</h1>
                    <hr>
                    <a class="btn btn-outline-dark botao_maior" href="game.php">Voltar</a>
                </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</body>
</html>