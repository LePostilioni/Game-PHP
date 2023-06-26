<?php
include_once 'template.php';

$message = "";

// Consulta SQL para contar os usuários logados
$sql = "SELECT COUNT(*) as total FROM usuarios WHERE logado_sql = 1";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$numero_usuarios_logados = $row['total'];

// Adiciona os nomes ao banco de dados
if (isset($_POST['adicionar_nomes'])) {
    $nomes_femininos = $_POST['nomes_femininos'];
    $nomes_masculinos = $_POST['nomes_masculinos'];
    $sobrenomes = $_POST['sobrenomes'];

    // Verifica se os campos foram preenchidos
    if (!empty($nomes_femininos) && !empty($nomes_masculinos) && !empty($sobrenomes)) {
        // Verifica se o nome já existe na tabela nomes_sobrenomes
        $query = "SELECT * FROM nomes_sobrenomes WHERE nomes_femininos = ? OR nomes_masculinos = ? OR sobrenomes = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $nomes_femininos, $nomes_masculinos, $sobrenomes);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            // Verifica o padrão dos campos de nome
            $pattern = "/^[A-Za-zÀ-ÿ\s-]{4,20}$/";
            if (preg_match($pattern, $nomes_femininos) && preg_match($pattern, $nomes_masculinos) && preg_match($pattern, $sobrenomes)) {
                // Insere os nomes na tabela nomes_sobrenomes
                $query = "INSERT INTO nomes_sobrenomes (nomes_femininos, nomes_masculinos, sobrenomes) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("sss", $nomes_femininos, $nomes_masculinos, $sobrenomes);
                $stmt->execute();
                $stmt->close();
                $message = "Nomes adicionados com sucesso!";
            } else {
                $message = "<span style='color: #f34336;'>Os nomes devem conter entre 4 e 20 caracteres, aceitando apenas letras, espaços, traços e acentos.</span>";
            }
        } else {
            $message = "<span style='color: #f34336;'>Os nomes já existem na tabela.</span>";
        }
        $_SESSION["message"] = $message;
    }
}

$nome_feminino_api = gerarnome_feminino_api();
$nome_masculino_api = gerarnome_masculino_api();
$sobrenome_api = gerarsobrenome_api();
// Função para gerar um nome feminino aleatório
function gerarnome_feminino_api()
{
    $url = "https://randomuser.me/api/?gender=female&nat=br";
    $data = file_get_contents($url);
    $result = json_decode($data, true);
    return $result['results'][0]['name']['first'];
}

// Função para gerar um nome masculino aleatório
function gerarnome_masculino_api()
{
    $url = "https://randomuser.me/api/?gender=male&nat=br";
    $data = file_get_contents($url);
    $result = json_decode($data, true);
    return $result['results'][0]['name']['first'];
}

// Função para gerar um sobrenome aleatório
function gerarsobrenome_api()
{
    $url = "https://randomuser.me/api/?nat=br";
    $data = file_get_contents($url);
    $result = json_decode($data, true);
    return $result['results'][0]['name']['last'];
}

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
                    <div class="message">
                        <?php echo $message; ?>
                    </div>
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="adm_tela" style="display: block;">
                        <h1>Página de Administração</h1>
                        <h3>Seja muito bem-vindo(a) administrador, <?php echo $nome; ?>!</h3>
                        <h4>Seu Nível de Acesso: <?php echo $gm_level; ?> / 9</h4>
                        <h4>Usuários logados no momento: <?php echo $numero_usuarios_logados; ?></h4>
                        <h4>Seu último login: <?php echo $_SESSION["ultimo_login"]; ?></h4>
                        <h4>Sua última atividade: <?php echo $_SESSION["last_activity"]; ?></h4>
                        <br>
                        <button type="button" class="btn btn-outline-dark botao_menor" onclick="show_mologin_form()">Mostrar Usuários</button>
                        <br><br>
                        <button type="button" class="btn btn-outline-dark botao_menor laranjinha" onclick="show_nomes_tela()">Adicionar Nomes e Sobrenomes ao banco</button>
                        <br>
                    </form>

                    <div id="lista-usuarios" style="display: none;">
                        <?php
                        // Conexão com o banco de dados
                        include('connection.php');

                        // Consulta SQL para obter os usuários
                        $query = "SELECT * FROM usuarios";
                        $result = $conn->query($query);

                        // Verifica se existem registros retornados
                        if ($result->num_rows > 0) {
                            echo "<h2>Lista de Usuários</h2>";
                            echo "<div class='table-responsive'>";
                            echo "<table class='table'>";
                            echo "<thead>";
                            echo "<tr>";
                            echo "<th>Nome</th>";
                            echo "<th>E-mail</th>";
                            echo "<th>Senha</th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            // Loop através dos registros retornados
                            while ($row = $result->fetch_assoc()) {
                                $nome = $row["nome"];
                                $email = $row["email"];
                                $senha = $row["senha"];
                                echo "<tr>";
                                echo "<td>$nome</td>";
                                echo "<td>$email</td>";
                                echo "<td>$senha</td>";
                                echo "</tr>";
                            }
                            echo "</tbody>";
                            echo "</table>";
                            echo "</div>";
                        } else {
                            echo "Nenhum usuário encontrado.";
                        }

                        // Fechar a conexão com o banco de dados
                        $conn->close();
                        ?>
                        <button type="button" class="btn btn-outline-dark botao_menor" onclick="show_adm_tela()">Voltar</button>
                    </div>

                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="nomes_tela" style="display: none;">
                        <h1>Adicionar Nomes</h1>
                        <div class="form-group">
                            <i class="fa-solid fa-user"></i>
                            <label for="nomes_femininos">Nome Feminino:</label>
                            <input type="text" class="form-control" id="nomes_femininos" name="nomes_femininos" placeholder="Fulana" value="<?php echo $nome_feminino_api; ?>" pattern="[A-Za-zÀ-ÿ\s-]{4,20}">
                        </div>
                        <div class="form-group">
                            <i class="fa-solid fa-user"></i>
                            <label for="nomes_masculinos">Nome Masculino:</label>
                            <input type="text" class="form-control" id="nomes_masculinos" name="nomes_masculinos" placeholder="Beltrano" value="<?php echo $nome_masculino_api; ?>" required pattern="[A-Za-zÀ-ÿ\s-]{4,20}">
                        </div>
                        <div class="form-group">
                            <i class="fa-solid fa-user"></i>
                            <label for="sobrenomes">Sobrenome:</label>
                            <input type="text" class="form-control" id="sobrenomes" name="sobrenomes" placeholder="De Tal" value="<?php echo $sobrenome_api; ?>" required pattern="[A-Za-zÀ-ÿ\s-]{4,20}">
                        </div>
                        <small>Somente letras e acentos. Mínimo de 4 caracteres e máximo de 20.</small>
                        <br>
                        <button type="submit" class="btn btn-outline-dark botao_menor" name="adicionar_nomes">Adicionar Nomes</button>
                        <br><br>
                        <button type="button" class="btn btn-outline-dark botao_menor" onclick="show_adm_tela()">Voltar</button>
                    </form>

                    <hr>
                    <div class="text-center">
                        <a class="btn btn-outline-dark botao_menor" href="game.php">Jogar</a>
                        <a class="btn btn-outline-dark botao_menor" href="index.php">Ínicio</a>
                        <br><br><br><br><br><br><br>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function show_adm_tela() {
                document.getElementById('adm_tela').style.display = 'block';
                document.getElementById('lista-usuarios').style.display = 'none';
                document.getElementById('nomes_tela').style.display = 'none';
            }

            function show_mologin_form() {
                document.getElementById('adm_tela').style.display = 'none';
                document.getElementById('lista-usuarios').style.display = 'block';
                document.getElementById('nomes_tela').style.display = 'none';
            }

            function show_nomes_tela() {
                document.getElementById('adm_tela').style.display = 'none';
                document.getElementById('lista-usuarios').style.display = 'none';
                document.getElementById('nomes_tela').style.display = 'block';
            }
        </script>
    <?php endif; ?>

</body>