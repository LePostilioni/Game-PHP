<?php
include_once 'template.php';
// numero do dado lançado
$dado_lancado = "0";

// Verifica se está logado
if (!isset($_SESSION["logado"])) {
    // Se não estiver logado
    echo '
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
    ';
} elseif ($_SESSION["char_criado"] == 0) {
    // Se não tiver personagem criado
    echo '
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
    ';
} else {
    // Se já tiver logado e com char criado
    // Obtém informações do personagem
    $query = "SELECT nome_personagem, sobrenome_materno, sobrenome_paterno, sexo, vivo, vida_personagem FROM personagem WHERE id_usuario = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $_SESSION["id"]);
    $stmt->execute();
    $stmt->bind_result($nome_personagem, $sobrenome_materno, $sobrenome_paterno, $sexo, $vivo, $vida_personagem);
    $stmt->fetch();
    $stmt->close();

    $sexo_texto = ($sexo === 0) ? "Fêmea" : "Macho";
    $nome_completo = $nome_personagem . " " . $sobrenome_materno . " " . $sobrenome_paterno;

    // Lógica de suicídio do personagem
    if (isset($_POST["suicidio"])) {
        // Atualiza o personagem no banco de dados
        $query = "UPDATE personagem SET vivo = 0, vida_personagem = 0 WHERE id_usuario = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $_SESSION["id"]);
        $stmt->execute();
        $stmt->close();
    }

    echo '
        <div class="container text-center">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <h1>Bora jogar!</h1>
                    <h3>Bem-vindo(a), ' . $nome_completo . '!</h3>
                    <h4>Sexo: ' . $sexo_texto . '</h4>
                    <h4>Vivo: ' . ($vivo ? "Sim" : "Não") . '</h4>
                    <h4>Vida: ' . $vida_personagem . ' / 99.9</h4>
                    <br>';
                    if ($vivo) {
                        echo '
                    <form method="post">
                        <button type="submit" name="suicidio" class="btn btn-danger">Suicidar-se</button>
                        </form>
                        <br>
                        ';
}
    echo '

                    <div class="text-center">
                        <a href="index.php" class="btn btn-outline-dark botao_maior">Voltar</a>
                    </div>
                </div>
            </div>
        </div>
    ';
}
?>