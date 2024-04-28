<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Saída dos Dados</title>
    <link rel="stylesheet" href="gg.css">
</head>

<body>
    <h2>Saída dos Dados</h2>
    <table border="1">
        <tr>
            <th>Grupo</th>
            <th>Tipo de Carga</th>
            <th>Descrição</th>
            <th>Quantidade</th>
            <th>Potência (W)</th>
            <th>Fator de Potência (FP)</th>
            <th>Potência VA</th>
            <th>Potência W Total</th>
            <th>Potência VA Total</th>
            <th>Fator de Demanda (FD)</th>
        </tr>
        <?php
        // Verifica se existem dados na URL
        if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET)) {
            // Obtém o número total de itens
            $total_items = count($_GET['grupo']);
            $pot_va_total_geral = 0;
            $pot_w_total_geral = 0;

            // Itera sobre os índices para exibir os dados
            for ($i = 0; $i < $total_items; $i++) {
                // Verifica se o grupo atual possui o índice correspondente
                if (isset($_GET['grupo'][$i])) {
                    echo "<tr>";
                    echo "<td>" . $_GET['grupo'][$i] . "</td>";
                    echo "<td>" . $_GET['tipo_carga'][$i] . "</td>";
                    echo "<td>" . $_GET['descricao'][$i] . "</td>";
                    echo "<td>" . $_GET['qtd'][$i] . "</td>";
                    echo "<td>" . $_GET['pot_w'][$i] . "</td>";
                    // Verifica se o valor do FP é "digite" para exibir o valor digitado
                    if ($_GET['fp'][$i] === 'digite') {
                        echo "<td>" . $_GET['fp_valor'][$i] . "</td>";
                    } else {
                        echo "<td>" . $_GET['fp'][$i] . "</td>";
                    }
                    // Calcula potência VA
                    $pot_va = $_GET['pot_w'][$i] / $_GET['fp'][$i];
                    echo "<td>" . $pot_va * $_GET['qtd'][$i] . "</td>";
                    // Calcula a potência W total
                    $pot_w_total = $_GET['pot_w'][$i] * $_GET['qtd'][$i];
                    echo "<td>" . $pot_w_total . "</td>";
                    // Adiciona a potência VA da linha ao total geral
                    $pot_va_total_geral += $pot_va * $_GET['qtd'][$i];
                    // Adiciona a potência W da linha ao total geral
                    $pot_w_total_geral += $pot_w_total;
                    echo "<td>" . ($pot_va * $_GET['qtd'][$i]) . "</td>";
                    // Adicionei um espaço para o Fator de Demanda (FD)
                    echo "<td>0</td>";
                    echo "</tr>";
                }
            }
            // Mostra a linha do total geral
            echo "<tr>";
            echo "<td colspan='7'>Total Geral</td>";
            echo "<td>" . $pot_w_total_geral . "</td>";
            echo "<td>" . $pot_va_total_geral . "</td>";
            echo "<td></td>"; // Espaço para o fator de Demanda
            echo "</tr>";
        } else {
            echo "<tr><td colspan='10'>Nenhum dado recebido.</td></tr>";
        }
        ?>
    </table>
</body>

</html>
