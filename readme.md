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


:::::Criação da tabela PERSONAGEM Mysql:::::

CREATE TABLE personagem (
  id_personagem INT PRIMARY KEY AUTO_INCREMENT,
  id_usuario INT,
  nome_personagem VARCHAR(30),
  sobrenome_materno VARCHAR(30),
  sobrenome_paterno VARCHAR(30),
  sexo BOOLEAN DEFAULT TRUE,
  vida_personagem DECIMAL(3,1) NOT NULL DEFAULT 99.9,
  vivo BOOLEAN DEFAULT TRUE,
  data_criacao DATETIME,
  data_desencarne DATETIME DEFAULT NULL,
  local_id INT(5) DEFAULT 1,
  indo_para INT(5) DEFAULT NULL,
  coordenada_x DOUBLE DEFAULT 0,
  coordenada_y DOUBLE DEFAULT 0,
  d20_rolado_terra INT(2) DEFAULT 0,
  d20_rolado_agua INT(2) DEFAULT 0,
  d20_rolado_fogo INT(2) DEFAULT 0,
  d20_rolado_ar INT(2) DEFAULT 0,
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
  FOREIGN KEY (local_id) REFERENCES mapa(id_local)
);


:::::DELETAR tabela PERSONAGEM Mysql:::::
DROP TABLE personagem;

:::::Criar tabela de nomes e sobrenomes PERSONAGEM Mysql:::::

CREATE TABLE nomes_sobrenomes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nomes_femininos VARCHAR(50),
  nomes_masculinos VARCHAR(50),
  sobrenomes VARCHAR(50)
);

:::::Trigger para matar o PERSONAGEM Mysql:::::
DELIMITER //
CREATE TRIGGER trg_atualiza_vivo BEFORE UPDATE ON personagem
FOR EACH ROW
BEGIN
    IF NEW.vida_personagem <= 0 THEN
        SET NEW.vivo = FALSE;
    END IF;
END //
DELIMITER ;


:::::Adicionar nomes:::::
INSERT INTO nomes_sobrenomes (nomes_femininos, nomes_masculinos, sobrenomes) VALUES
  ('Fulana', 'Beltrano', 'Ciclano');


:::::Criação da tabela "MAPA" MySql:::::
  CREATE TABLE mapa (
  id_local INT(5) PRIMARY KEY AUTO_INCREMENT,
  nome_local VARCHAR(255),
  coordenada_x DOUBLE,
  coordenada_y DOUBLE,
  id_reino INT(5),
  reino_pertencente VARCHAR(255)
);

-- Inserção de registros na tabela "mapa"
INSERT INTO mapa (id_local, nome_local, coordenada_x, coordenada_y, id_reino, reino_pertencente) VALUES
(0,'Local A', 0, 0, 0, 'Reino 1');