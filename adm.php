<?php
include_once 'template.php';

// Consulta SQL para contar os usuários logados
$sql = "SELECT COUNT(*) as total FROM usuarios WHERE logado_sql = 1";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$numero_usuarios_logados = $row['total'];
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
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="adm_tela" style="display: block;">
                        <h1>Página de Administração</h1>
                        <h3>Seja muito bem-vindo(a) administrador, <?php echo $nome; ?>!</h3>
                        <h4>Seu Nível de Acesso: <?php echo $gm_level; ?> / 9</h4>
                        <h4>Usuários logados no momento: <?php echo $numero_usuarios_logados; ?></h4>
                        <h4>Seu último login: <?php echo $_SESSION["ultimo_login"]; ?></h4>
                        <h4>Sua última atividade: <?php echo $_SESSION["last_activity"]; ?></h4>
                        <button type="button" class="btn btn-outline-dark botao_menor" onclick="show_mologin_form()">Mostrar Usuários</button>
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
            }

            function show_mologin_form() {
                document.getElementById('adm_tela').style.display = 'none';
                document.getElementById('lista-usuarios').style.display = 'block';
            }
        </script>
    <?php endif; ?>

</body>

</html>