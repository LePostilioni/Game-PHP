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
    $query = "SELECT nome_personagem, sobrenome_materno, sobrenome_paterno, sexo, vivo, vida_personagem FROM personagem WHERE id_usuario = ? ORDER BY id_personagem DESC LIMIT 1";
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

    if ($vivo) {
        echo '
            <div class="container text-center">
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <h1>Bora jogar!</h1>
                        <h3>Bem-vindo(a), ' . $nome_completo . '!</h3>
                        <h4>Sexo: ' . $sexo_texto . '</h4>
                        <h4>Vivo: ' . ($vivo ? "Sim" : "Não") . '</h4>
                        <h4>Vida: ' . $vida_personagem . ' / 99.9</h4>
                        <br>
                        <form method="post">
                            <button type="submit" name="suicidio" class="btn btn-danger">Suicidar-se</button>
                        </form>
                        <br>
                        <div class="text-center">
                            <a href="index.php" class="btn btn-outline-dark botao_maior">Voltar</a>
                        </div>
                    </div>
                </div>
            </div>
        ';
    } else {
        // Personagem está "morto"
        echo '
            <div class="container text-center">
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <h3>Bem-vindo(a), ' . $nome_completo . '!</h3>
                        <h4>Sexo: ' . $sexo_texto . '</h4>
                        <h4>Vivo: ' . ($vivo ? "Sim" : "Não") . '</h4>
                        <h4>Vida: ' . $vida_personagem . ' / 99.9</h4>
                        <h1>Seu personagem está morto!</h1>
                        <h3>Deseja reencarnar?</h3>
                        <form method="post">
                            <button type="submit" name="reencarnar" class="btn btn-success">Reencarnar</button>
                        </form>
                        <br>
                        <div class="text-center">
                            <a href="index.php" class="btn btn-outline-dark botao_maior">Voltar</a>
                        </div>
                    </div>
                </div>
            </div>
        ';

        if (isset($_POST["reencarnar"])) {
            // Atualiza a data de desencarne do personagem
            $query = "UPDATE personagem SET data_desencarne = NOW() WHERE id_usuario = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $_SESSION["id"]);
            $stmt->execute();
            $stmt->close();

            // Atualiza a flag de personagem criado do usuário
            $query = "UPDATE usuarios SET char_criado = 0 WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $_SESSION["id"]);
            $stmt->execute();
            $stmt->close();
            $_SESSION["char_criado"] = "0";

    // Redirecionar para a página atual
    echo '<script>window.location.href = window.location.href;</script>';
        }
    }
}
?>
