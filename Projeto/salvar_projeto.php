<?php
$servername = "localhost";
$username = "root";
$password = "ceuma";

// Conexão ao MySQL
$conn = new mysqli($servername, $username, $password);

// Verifica conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Nome do banco de dados fornecido pelo usuário
$dbname = $_POST['nome_projeto'];

// Criação do banco de dados se não existir
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";

if ($conn->query($sql) === TRUE) {
    echo "Banco de dados '$dbname' criado com sucesso ou já existe.<br>";
} else {
    echo "Erro ao criar banco de dados: " . $conn->error . "<br>";
}

// Seleciona o banco de dados
$conn->select_db($dbname);

// Criação da tabela de projetos
$sql = "CREATE TABLE IF NOT EXISTS projetos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_projeto VARCHAR(255) NOT NULL,
    ramo VARCHAR(255) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Tabela de projetos criada com sucesso ou já existe.<br>";
    // Redireciona para a página "tabela"
    header("Location: tabela.php");
    exit(); // Certifica-se de que o script não continue a ser executado após o redirecionamento
} else {
    echo "Erro ao criar tabela de projetos: " . $conn->error . "<br>";
}

$conn->close();
?>
