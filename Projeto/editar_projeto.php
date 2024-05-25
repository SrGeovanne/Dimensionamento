<?php
// Inclui o arquivo de conexão
include 'conexao.php';

// Verifica se o nome do banco de dados foi passado como parâmetro na URL
if (isset($_GET['projeto_nome'])) {
    $dbname = $_GET['projeto_nome'];

    // Seleciona o banco de dados
    if (!$conn->select_db($dbname)) {
        die("Erro ao selecionar o banco de dados: " . $conn->error);
    }

    // Consulta para obter os dados da tabela 'projeto'
    $sql = "SELECT * FROM projeto";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h2>Dados do Projeto - Banco de dados: " . htmlspecialchars($dbname) . "</h2>";
        echo "<table border='1'>
                <tr>
                    <th>ID</th>
                    <th>Ramo</th>
                    <th>Grupo</th>
                    <th>Carga</th>
                    <th>Descrições</th>
                    <th>QTD</th>
                    <th>Potência (W)</th>
                    <th>FP</th>
                </tr>";
        // Itera sobre os resultados e exibe os dados na tabela
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['id']) . "</td>
                    <td>" . htmlspecialchars($row['ramo']) . "</td>
                    <td>" . htmlspecialchars($row['Grupo']) . "</td>
                    <td>" . htmlspecialchars($row['Carga']) . "</td>
                    <td>" . htmlspecialchars($row['Descricoes']) . "</td>
                    <td>" . htmlspecialchars($row['QTD']) . "</td>
                    <td>" . htmlspecialchars($row['Pot_W']) . "</td>
                    <td>" . htmlspecialchars($row['FP']) . "</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "Nenhum dado encontrado na tabela 'projeto'.";
    }
} else {
    echo "Nome do banco de dados não fornecido!";
    exit();
}

// Fecha a conexão
$conn->close();
?>
