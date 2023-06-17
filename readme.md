:::::Criação da tabela Mysql:::::

CREATE TABLE `usuarios` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nome` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `senha` varchar(50) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `gm_level` int(1) NOT NULL DEFAULT 0,
  `logado_sql` tinyint(1) NOT NULL DEFAULT 0,
  `ultimo_login` datetime DEFAULT NULL,
  `char_criado` tinyint(1) NOT NULL DEFAULT 0,
  `energia_universal` varchar(7) NOT NULL DEFAULT 50
);

:::::Alterar o AUTO_INCREMENT de tabela `usuarios`:::::

ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;



CREATE TABLE 'personagem' (
  id INT PRIMARY KEY AUTO_INCREMENT,
  id_usuario INT,
  nome VARCHAR(50),
  vivo BOOLEAN DEFAULT TRUE,
  data_criacao DATETIME,
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);