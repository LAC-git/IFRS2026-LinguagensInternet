-- ============================================================
-- BANCO DE DADOS: LOJINHA
-- ENGINE: InnoDB
-- ============================================================
-- COMO FUNCIONAM AS REGRAS DE SEGURANCA NAS FOREIGN KEYS:
-- 
-- 1. ON DELETE CASCADE (itens_pedido -> pedidos):
--    - Se voce apagar um pedido, todos os itens dele sao apagados 
--      junto automaticamente. Isso evita itens "orfaos" no banco.
--    - Em vez de apagar um pedido, apenas mude 
--      o status dele para 'cancelado' para nao perder o historico.
--
-- 2. ON DELETE RESTRICT (produtos -> categorias / pedidos -> clientes):
--    - Não deixa apagar um cliente que ja fez pedidos,
--      nem uma categoria que ja tem produtos associados.
--
-- 3. ON DELETE SET NULL (pedidos -> funcionarios):
--    - Se um funcionario for apagado do sistema, o pedido continua 
--      salvo normalmente, apenas deixando o campo do funcionario em branco.
-- ============================================================


CREATE DATABASE lojinha
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE lojinha;


-- 1. TABELAS PRINCIPAIS (Não dependem de nenhuma outra)
-- ============================================================

-- Categorias
CREATE TABLE IF NOT EXISTS categorias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL UNIQUE,
  descricao TEXT,
  criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Clientes
CREATE TABLE IF NOT EXISTS clientes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(150) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  hash_senha VARCHAR(255) NOT NULL,
  telefone VARCHAR(30),
  endereco TEXT,
  criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Funcionarios
CREATE TABLE IF NOT EXISTS funcionarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(150) NOT NULL,
  cpf VARCHAR(14) NOT NULL UNIQUE,
  cargo VARCHAR(50) NOT NULL,
  telefone VARCHAR(30),
  email VARCHAR(255) NOT NULL UNIQUE,
  hash_senha VARCHAR(255) NOT NULL,
  criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


-- 2. TABELAS COM LIGACOES (Dependem das tabelas acima)
-- ============================================================

-- Lista de produtos (Cada produto precisa ter 1 categoria)
-- RESTRICT: Não deixa apagar uma categoria se houver produtos nela
CREATE TABLE IF NOT EXISTS produtos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  categoria_id INT NOT NULL,
  nome VARCHAR(150) NOT NULL,
  descricao TEXT,
  preco DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  estoque INT NOT NULL DEFAULT 0,
  sku VARCHAR(100) NOT NULL UNIQUE,
  criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Pedidos (Conecta o cliente e o funcionario responsável)
-- RESTRICT: Não deixa apagar o cliente se ele já fez algum pedido
-- SET NULL: Se o funcionario for apagado, o pedido continua salvo sem funcionario
CREATE TABLE IF NOT EXISTS pedidos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cliente_id INT NOT NULL,
  funcionario_id INT,
  data_pedido DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  status ENUM('pendente','pago','enviado','concluido','cancelado') NOT NULL DEFAULT 'pendente',
  total DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE RESTRICT ON UPDATE CASCADE,
  FOREIGN KEY (funcionario_id) REFERENCES funcionarios(id) ON DELETE SET NULL ON UPDATE CASCADE
);


-- 3. TABELA N PRA N (Conecta Pedidos e Produtos)
-- ============================================================

-- Guarda quais produtos estao em cada pedido e as quantidades
-- CASCADE: Se apagar o pedido, apaga automaticamente os itens dele
-- RESTRICT: Não deixa apagar um produto do sistema se ele já foi vendido em algum pedido
CREATE TABLE IF NOT EXISTS itens_pedido (
  id INT AUTO_INCREMENT PRIMARY KEY,
  pedido_id INT NOT NULL,
  produto_id INT NOT NULL,
  quantidade INT NOT NULL DEFAULT 1,
  preco_unitario DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  subtotal DECIMAL(12,2) AS (quantidade * preco_unitario) STORED,
  criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE RESTRICT ON UPDATE CASCADE
);
