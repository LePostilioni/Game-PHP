<?php
include_once 'template.php';

// Processar o formulário de cadastro
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["cadastro"])) {
    if (isset($_POST['nome'])) {
        $nome = $_POST['nome'];
    } else {
        $nome = "";
    }

    if (isset($_POST['email'])) {
        $email = $_POST['email'];
    } else {
        $email = "";
    }

    if (isset($_POST['senha'])) {
        $senha = $_POST['senha'];
    } else {
        $senha = "";
    }

    // Formatar o nome do usuário
    $nome = ucwords(strtolower($nome));

    // Verificar se o email já está cadastrado
    $query = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message = "<span style='color: #f34336;'>Este email já está cadastrado. Por favor, tente novamente.</span>";
        $_SESSION["message"] = $message;
    } else {
        // Inserir os dados do usuário no banco de dados
        $query = "INSERT INTO usuarios (nome, email, senha, gm_level, criado_em) VALUES (?, ?, ?, 0, CURRENT_TIMESTAMP)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $nome, $email, $senha);

        if ($stmt->execute()) {
            $message = "Cadastro realizado com sucesso!";
            $_SESSION["message"] = $message;
        } else {
            $message = "Erro no cadastro: " . $stmt->error;
            $_SESSION["message"] = $message;
        }
    }
    $stmt->close();
}

// Processar o formulário de login
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["login"])) {
    $email = $_POST["email-login"];
    $senha = $_POST["senha-login"];

    // Verificar as credenciais do usuário
    $query = "SELECT * FROM usuarios WHERE email = ? AND senha = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email, $senha);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $logado = true;
        $_SESSION["logado"] = $logado;
        $message = "Login realizado com sucesso!";
        $_SESSION["message"] = $message;

        // Recuperar os dados do usuário do banco de dados
        $row = $result->fetch_assoc();
        $nome = $row["nome"];
        $gm_level = $row["gm_level"];
        $senha = $row["senha"];
        $email = $row["email"];
        $ultimo_login = $row["ultimo_login"];
        $_SESSION["nome"] = $nome;
        $_SESSION["gm_level"] = $gm_level;
        $_SESSION["senha"] = $senha;
        $_SESSION["email"] = $email;
        $_SESSION["ultimo_login"] = date("d/m/Y - H:i:s", strtotime($ultimo_login)); // Formata a data no formato brasileiro

        $message2 = "<br><span style='font-weight: bold; color: green;'>Você está logado</span>";

        // Atualizar as colunas logado_sql e ultimo_login
        $id_usuario = $row["id"];
        $_SESSION["id_usuario"] = $id_usuario;
        $atualizarLoginSql = "UPDATE usuarios SET logado_sql = 1 WHERE id = $id_usuario";
        $conn->query($atualizarLoginSql);

        $atualizarUltimoLoginSql = "UPDATE usuarios SET ultimo_login = NOW() WHERE id = $id_usuario";
        $conn->query($atualizarUltimoLoginSql);

        // Redirecionar para a página atual
        echo '<script>window.location.href = window.location.href;</script>';
        exit();
    } else {
        $message = "<span style='color: #f34336;'>Email ou senha inválidos. Por favor, tente novamente.</span>";
        $_SESSION["message"] = $message;
    }
    $stmt->close();
}


// Verificar se o formulário de mudança de senha foi enviado
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['mudar-senha'])) {
    if (isset($_POST['senha-atual'])) {
        $senha_atual = $_POST['senha-atual'];
    } else {
        $senha_atual = "";
    }

    if (isset($_POST['nova-senha'])) {
        $nova_senha = $_POST['nova-senha'];
    } else {
        $nova_senha = "";
    }

    if (isset($_POST['confirmar-senha'])) {
        $confirmar_senha = $_POST['confirmar-senha'];
    } else {
        $confirmar_senha = "";
    }

    // Verificar se a senha atual está correta
    $query = "SELECT senha FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($senha_banco);
        $stmt->fetch();

        // Verificar se a senha atual está correta
        if ($senha_atual === $senha_banco) {
            // A senha atual está correta, você pode prosseguir com a atualização da senha
            // Verificar se a nova senha e a confirmação da nova senha coincidem
            if ($nova_senha === $confirmar_senha) {
                // Preparar a consulta para atualizar a senha no banco de dados
                $query_atualizar = "UPDATE usuarios SET senha = ? WHERE email = ?";
                $stmt_atualizar = $conn->prepare($query_atualizar);
                $stmt_atualizar->bind_param("ss", $nova_senha, $email);

                // Executar a atualização
                if ($stmt_atualizar->execute()) {
                    $message = "Senha alterada com sucesso!";
                    $_SESSION["message"] = $message;
                } else {
                    $message = "<span style='color: #f34336;'>Ocorreu um erro ao atualizar a senha. Tente novamente.</span>";
                    $_SESSION["message"] = $message;
                }

                // Fechar a declaração de atualização
                $stmt_atualizar->close();
            } else {
                // Exibir mensagem de erro caso as senhas não coincidam
                $message = "<span style='color: #f34336;'>As senhas não coincidem. Tente novamente.</span>";
                $_SESSION["message"] = $message;
            }
        } else {
            $message = "<span style='color: #f34336;'>A senha atual está incorreta. Tente novamente.</span>";
            $_SESSION["message"] = $message;
        }
    } else {
        // Usuário não encontrado ou múltiplos registros encontrados (algo está errado)
        $message = "<span style='color: #f34336;'>O usuário não foi encontrado ou houve um erro no banco de dados.</span>";
        $_SESSION["message"] = $message;
    }
    $stmt->close();
}

$conn->close();
?>

<head>
    <title>Página Inicial</title>
</head>

<body>
    <!-- Conteúdo da página -->
    <div class="container text-center">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <br><br>
                <h1>Bem-vindo(a) ao Projeto</h1>
                <p>Divirta-se com nosso game!</p>
                <hr>

                <div class="message">
                    <?php echo $message; ?>
                </div>
                <!-- Formularios de login e cadastro -->
                <?php if (!$logado) : ?>
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="login-form">
                        <h3 id="login-heading">Login</h3>
                        <div class="form-group">
                            <i class="fa-solid fa-envelope"></i>
                            <label for="email-login">Email:</label>
                            <input type="email" class="form-control" id="email-login" name="email-login" required>
                        </div>
                        <div class="form-group">
                            <i class="fa-solid fa-lock"></i>
                            <label for="senha-login">Senha:</label>
                            <input type="password" class="form-control" id="senha-login" name="senha-login" required>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-outline-dark botao_menor" name="login" onclick="addShakeEffect(this)">Entrar</button>
                        <button type="button" class="btn btn-outline-warning botao_menor" disabled onclick="showEsqueciForm()">Esqueci a senha</button> <!-- Ainda não implementado -->
                        <hr>
                        <button type="button" class="btn btn-outline-dark botao_maior" onclick="showCadastroForm()">Cadastrar-se</button>
                    </form>

                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="cadastro-form" style="display: none;">
                        <h3>Cadastre-se</h3>
                        <div class="form-group">
                            <i class="fa-solid fa-user"></i>
                            <label for="nome">Nome:</label>
                            <input type="text" class="form-control" id="nome" name="nome" placeholder="Fulano de Tal" required pattern="[A-Za-zÀ-ÿ\s-]{10,30}">
                            <small>Nome e sobrenome, somente letras. Mínimo de 10 caracteres e máximo de 30.</small>
                        </div>
                        <div class="form-group">
                            <i class="fa-solid fa-envelope"></i>
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="fulano@exemplo.com" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                        </div>
                        <div class="form-group">
                            <i class="fa-solid fa-lock"></i>
                            <label for="senha">Senha:</label>
                            <input type="password" class="form-control" id="senha" name="senha" placeholder="Ex:Abcd123*&" required pattern="[A-Za-zÀ-ÿ0-9\-]{8,30}">
                            <small>Sem espaços. Mínimo de 8 caracteres e máximo de 30.</small>
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-outline-dark botao_menor" name="cadastro" onclick="addShakeEffect(this)">Cadastrar</button>
                        <button type="button" class="btn btn-outline-dark botao_menor" onclick="showLoginForm()">Fazer Login</button>
                    </form>
                    <script>
                        function showCadastroForm() {
                            document.getElementById('login-form').style.display = 'none';
                            document.getElementById('cadastro-form').style.display = 'block';
                        }

                        function showLoginForm() {
                            document.getElementById('login-form').style.display = 'block';
                            document.getElementById('cadastro-form').style.display = 'none';
                        }
                    </script>

                <?php else : ?>
                    <!-- Formulários depois de logado -->
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="bemvindo" style="display: block;">
                        <h3>Seja muito bem-vindo(a), <?php echo $_SESSION['nome']; ?>!</h3>
                        <br>
                        <a class="btn btn-outline-dark botao_menor" href="game.php">Jogar</a>
                        <br><br>
                        <?php if ($_SESSION['gm_level'] > 0) : ?>
                            <a class="btn btn-outline-dark botao_menor" href="adm.php">Administração</a>
                            <br><br>
                        <?php endif; ?>
                        <button type="button" class="btn btn-outline-dark botao_menor" onclick="showChangePasswordForm()">Mudar Senha</button>
                    </form>

                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="change-password-form" style="display: none;">
                        <h3>Mudar Senha</h3>
                        <div class="form-group">
                            <i class="fa-solid fa-lock"></i>
                            <label for="senha-atual">Senha Atual:</label>
                            <input type="password" class="form-control" id="senha-atual" name="senha-atual" required>
                        </div>
                        <div class="form-group">
                            <i class="fa-solid fa-lock"></i>
                            <label for="nova-senha">Nova Senha:</label>
                            <input type="password" class="form-control" id="nova-senha" name="nova-senha" required pattern="[A-Za-zÀ-ÿ0-9\-]{8,30}">
                        </div>
                        <div class="form-group">
                            <i class="fa-solid fa-lock"></i>
                            <label for="confirmar-senha">Confirmar Nova Senha:</label>
                            <input type="password" class="form-control" id="confirmar-senha" name="confirmar-senha" required>
                            <small>*Sem espaços. Mínimo de 8 caracteres e máximo de 30.</small>
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-outline-dark botao_menor" name="mudar-senha" onclick="addShakeEffect(this)">Salvar</button>
                        <button type="button" class="btn btn-outline-dark botao_menor" onclick="cancelChangePassword()">Cancelar</button>
                    </form>

                    <script>
                        function showBemvindo() {
                            document.getElementById('change-password-form').style.display = 'none';
                            document.getElementById('login-form').style.display = 'none';
                        }

                        function showChangePasswordForm() {
                            document.getElementById('bemvindo').style.display = 'none';
                            document.getElementById('change-password-form').style.display = 'block';
                        }

                        function cancelChangePassword() {
                            document.getElementById('bemvindo').style.display = 'block';
                            document.getElementById('change-password-form').style.display = 'none';
                        }
                    </script>
                <?php endif; ?>
                <br><br><br><br><br><br><br><br>
            </div>
        </div>
    </div>
    <br>
</body>

</html>