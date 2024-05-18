<?php
$servername = "localhost"; // Endereço do servidor MySQL
$username = "root"; // Nome de usuário do MySQL
$password = "ceuma"; // Senha do MySQL
//$dbname = "seu_banco_de_dados"; // Nome do banco de dados

// Estabelecer conexão
$conn = new mysqli($servername, $username, $password, /*$dbname*/);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
echo "Conexão bem-sucedida";
?>
