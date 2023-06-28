<?php
include_once 'template.php';

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
    // Obtém informações do último personagem criado
    $query = "SELECT nome_personagem, sobrenome_materno, sobrenome_paterno, sexo, vivo, vida_personagem, local_id, indo_para, coordenada_x, coordenada_y FROM personagem WHERE id_usuario = ? ORDER BY id_personagem DESC LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $_SESSION["id"]);
    $stmt->execute();
    $stmt->bind_result($nome_personagem, $sobrenome_materno, $sobrenome_paterno, $sexo, $vivo, $vida_personagem, $local_id, $indo_para, $coordenada_x, $coordenada_y);
    $stmt->fetch();
    $stmt->close();    

    $sexo_texto = ($sexo === 0) ? "Fêmea" : "Macho";
    $nome_completo = $nome_personagem . " " . $sobrenome_materno . " " . $sobrenome_paterno;

}
?>
