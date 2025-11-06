Projeto de um CRUD chamado "Produtos" feito por Mikael Abdias e João Gabriel Lacerda

//A pagina "Produtos" tem a funcionalidade de adicionar itens, exclui-los e editá-los igual a de clientes//
//Para rodar o projeto basta iniciá-lo pelo localhost no menu e logo verá uma das abas chamada "Produtos"//

Banco de Dados criado:

CREATE DATABASE IF NOT EXISTS loja;
USE loja;

CREATE TABLE IF NOT EXISTS produtos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  descricao TEXT,
  preco DECIMAL(10,2),
  quantidade INT DEFAULT 0
);

USE loja;

CREATE TABLE IF NOT EXISTS clientes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  telefone VARCHAR(20),
  endereco VARCHAR(255)
);


