<?php
include_once 'template.php';

// Função para criar um personagem aleatório
function criarPersonagemAleatorio($conn, $id) {
    echo "Função criarPersonagemAleatorio() chamada!";
    // Defina aqui a lógica para criar um personagem aleatório
    $nomes = array("Alice", "Bob", "Carol", "Dave", "Eve", "Frank");
    $nomePersonagem = $nomes[array_rand($nomes)];

    // Exemplo: Inserindo um personagem aleatório na tabela "personagem"
    $query = "INSERT INTO personagem (id_usuario, nome, vivo, data_criacao) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $vivo = true;
    $dataCriacao = date("Y-m-d H:i:s");
    $stmt->bind_param("isss", $id, $nomePersonagem, $vivo, $dataCriacao);
    $stmt->execute();
    $stmt->close();

    // Atualizar o valor da coluna "char_criado" na tabela "usuarios"
    $query = "UPDATE usuarios SET char_criado = 1 WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

        // Redirecionar para a página atual
        echo '<script>window.location.href = window.location.href;</script>';
        exit();
}

// Verificar se o usuário está logado
if (!isset($_SESSION["logado"])) {
    // Se não estiver logado
    echo '
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
    ';
} elseif ($_SESSION["char_criado"] == 0) {
    // Se não tiver personagem criado
    echo '
        <div class="container text-center">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <br>
                    <h1 class="font-weight-bold">Vamos criar seu personagem!</h1>
                    <h5>Você foi convocado pela espiritualidade maior para 
                    encarnar nesse planeta, sua missão principal é ajuda-lo a evoluir!
                    <br>Mas lembre-se, esse é um mundo primitivo e muito perigoso. Cada escolha sua terá consequências!</h5>
                    <hr>
                    <h3>Faça sua escolha com sabedoria!<br>Vamos lá:</h3>
                    <h6>Sua Energia Universal atual é:</h6>
                    <div class="laranjinha">$' . $_SESSION["energia_universal"] . '</div>
                    <hr>Energia universal: $0 - <a class="btn btn-outline-dark botao_menor" href="createchar.php?personagem_aleatorio">Personagem Aleatório</a>
                    <hr>Energia universal: $100 - <a class="btn btn-outline-dark botao_menor laranjinha" href="createchar.php?personagem_sorte">Personagem com sorte</a>
                    <hr>Energia universal: $500 - <a class="btn btn-outline-dark botao_menor" href="createchar.php?personagem_abencoado">Personagem abençoado</a>
                    <hr>Energia universal: $1500 - <a class="btn btn-outline-dark botao_menor laranjinha" href="createchar.php?personagem_como_quer">Personagem como quer</a>
                    <hr>
                    <a class="btn btn-outline-dark botao_menor" href="game.php">Voltar</a>
                    <br><br><br><br><br><br><br><br>
                </div>
            </div>
        </div>
    ';

    // Verificar se foi clicado em algum botão de personagem aleatório
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["personagem_aleatorio"])) {
        // Verificar se o usuário já possui um personagem vivo
        $query = "SELECT COUNT(*) FROM personagem WHERE id_usuario = ? AND vivo = TRUE";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $_SESSION["id"]);
        $stmt->execute();
        $stmt->bind_result($numPersonagensVivos);
        $stmt->fetch();
        $stmt->close();

        if ($numPersonagensVivos > 0) {
            // Se o usuário já possui um personagem vivo
            echo '
                <div class="container text-center position-absolute top-50 start-50 translate-middle">
                    <div class="row">
                        <div class="col-md-6 offset-md-3">
                            <br>
                            <h1 class="font-weight-bold text-danger">Você já possui um personagem vivo!</h1>
                            <hr>
                            <a class="btn btn-outline-dark botao_maior" href="game.php">Voltar</a>
                        </div>
                    </div>
                </div>
            ';
        } else {
            // Se o usuário não possui nenhum personagem vivo, criar personagem aleatório
            criarPersonagemAleatorio($conn, $_SESSION["id"]);
        }
    }
} else {
    // Se o usuário já tem personagem criado
    echo '
        <div class="container text-center position-absolute top-50 start-50 translate-middle">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <br>
                    <h1 class="font-weight-bold text-danger">Você já tem um personagem criado!</h1>
                    <hr>
                    <a class="btn btn-outline-dark botao_maior" href="game.php">Voltar</a>
                </div>
            </div>
        </div>
    ';
}
?>

</body>
<br><br><br><br><br><br><br><br><br><br><br><br>
</html>
