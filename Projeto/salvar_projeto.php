


<?php

//apagar depois 


// Inclui o arquivo de conexão com o banco de dados
include 'conexao.php';

// Verifica se o método de requisição é POST e se o campo 'nome_projeto' foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nome_projeto'])) {
    // Nome do banco de dados fornecido pelo usuário
    $dbname = $_POST['nome_projeto'];

    // Escapa caracteres especiais para evitar SQL Injection
    $dbname = $conn->real_escape_string($dbname);

    // Criação do banco de dados se não existir
    $sql_create_db = "CREATE DATABASE IF NOT EXISTS $dbname";

    if ($conn->query($sql_create_db) === TRUE) {
        echo "Banco de dados '$dbname' criado com sucesso ou já existe.<br>";
    } else {
        echo "Erro ao criar banco de dados: " . $conn->error . "<br>";
    }

    // Seleciona o banco de dados
    if (!$conn->select_db($dbname)) {
        echo "Erro ao selecionar o banco de dados: " . $conn->error . "<br>";
        exit();
    }

    // Criação da tabela de projetos
    $sql_create_table = "CREATE TABLE IF NOT EXISTS projeto (
        id INT AUTO_INCREMENT PRIMARY KEY,
        ramo VARCHAR(255) NOT NULL,
        Grupo VARCHAR(255),
        Carga VARCHAR(255),
        Descricoes VARCHAR(255),
        QTD INT,
        Pot_W INT,
        FP DECIMAL(5, 2)
    )";

    if ($conn->query($sql_create_table) === TRUE) {
        echo "Tabela de projetos criada com sucesso ou já existe.<br>";
        // Redireciona para a página "tabela"
        header("Location: tabela.php");
        exit(); // Certifica-se de que o script não continue a ser executado após o redirecionamento
    } else {
        echo "Erro ao criar tabela de projetos: " . $conn->error . "<br>";
    }

    $conn->close();
} else {
    // Se o método de requisição não for POST ou 'nome_projeto' não for enviado, redireciona de volta para a página anterior
    header("Location: index.php");
    exit();
}
?>
