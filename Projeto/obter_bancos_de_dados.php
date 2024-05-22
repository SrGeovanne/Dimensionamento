<?php
// Incluir o arquivo de conexão
include 'conexao.php';

// Consultar a lista de bancos de dados
$sql = "SHOW DATABASES";
$result = $conn->query($sql);

// Verificar se a consulta retornou resultados
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['Database'] . "'>" . $row['Database'] . "</option>";
    }
} else {
    echo "<option value=''>Nenhum banco de dados encontrado</option>";
}

// Fechar a conexão
$conn->close();
?>
