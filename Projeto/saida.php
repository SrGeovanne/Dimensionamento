<?php
// Inicia a sessão
session_start();

// Função para calcular os fatores de demanda para todos os grupos
function calcularFatoresDemanda($potencias, $ramo_selecionado)
{
    $fatores_demanda = [];

    foreach ($potencias as $grupo => $potencia) {
        if ($grupo === 'E') {
            $maior_potencia = max($potencia);
            foreach ($potencia as $p) {
                $fatores_demanda[$grupo][] = ($p == $maior_potencia) ? 1 : 0.6;
            }
        } elseif ($grupo === 'D') {
            $maior_potencia = max($potencia);
            foreach ($potencia as $p) {
                $fatores_demanda[$grupo][] = ($p == $maior_potencia) ? 1 : 0.7;
            }
        } elseif ($grupo === 'A') {
            // Define o fator de demanda do grupo A de acordo com o ramo selecionado
            $ramos_com_fator_1 = ['auditorio', 'bancos', 'barbearias', 'clubes', 'garagem', 'igreja', 'restaurante'];
            $fator_demanda_grupo_A = in_array($ramo_selecionado, $ramos_com_fator_1) ? 1 : 0;
            $fatores_demanda[$grupo] = $fator_demanda_grupo_A;
        } else {
            $fatores_demanda[$grupo] = 0; // Define o fator de demanda inicial como 0 para os grupos B, C e D
        }
    }

    return $fatores_demanda;
}

// Verifica se os dados foram enviados por GET
if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET)) {
    // Inicializa o array de potências
    $potencias = [];

    // Agrupa as potências por grupo
    foreach ($_GET['grupo'] as $index => $grupo) {
        $potencia = floatval($_GET['pot_w'][$index]);
        $potencias[$grupo][] = $potencia;
    }

    // Verifica se o ramo selecionado está definido na sessão
    $ramo_selecionado = isset($_SESSION['ramo_selecionado']) ? $_SESSION['ramo_selecionado'] : '';

    // Calcula os fatores de demanda com base nas potências recebidas e no ramo selecionado
    $fatores_demanda = calcularFatoresDemanda($potencias, $ramo_selecionado);
} else {
    // Se não houver dados enviados por GET, redireciona de volta para a página anterior
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Saída dos Dados</title>
    <link rel="stylesheet" href="gg.css">
</head>

<body>
    <?php
    // Verifica se o ramo selecionado está definido na sessão
    if (isset($_SESSION['ramo_selecionado'])) {
        echo "<h2>Ramo selecionado: " . $_SESSION['ramo_selecionado'] . "</h2>";
    } else {
        echo "<h2>Nenhum ramo selecionado</h2>";
    }
    ?>

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
            <th>Fator de Demanda (FD)</th> <!-- Adicionado -->
        </tr>
        <?php
        // Verifica se existem dados na URL
        if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET)) {
            // Associative array to store sums according to the group
            $group_sums = [];

            // Iterates over the submitted data
            foreach ($_GET['grupo'] as $index => $grupo) {
                // Verifies if the current group has the corresponding index
                if (isset($_GET['grupo'][$index])) {
                    echo "<tr>";
                    echo "<td>" . $_GET['grupo'][$index] . "</td>";
                    echo "<td>" . $_GET['tipo_carga'][$index] . "</td>";
                    echo "<td>" . $_GET['descricao'][$index] . "</td>";
                    echo "<td>" . $_GET['qtd'][$index] . "</td>";
                    echo "<td>" . $_GET['pot_w'][$index] . "</td>";

                    // Checks if the FP value is "digite" to display the entered value
                    if ($_GET['fp'][$index] === 'digite') {
                        echo "<td>" . $_GET['fp_valor'][$index] . "</td>";
                    } else {
                        echo "<td>" . $_GET['fp'][$index] . "</td>";
                    }

                    // Calculates VA power
                    $pot_va = $_GET['pot_w'][$index] / $_GET['fp'][$index];
                    echo "<td>" . $pot_va * $_GET['qtd'][$index] . "</td>";

                    // Calculates total W power
                    $pot_w_total = $_GET['pot_w'][$index] * $_GET['qtd'][$index];
                    echo "<td>" . $pot_w_total . "</td>";

                    // Adds the line's VA power to the total VA power for the group
                    $group = $_GET['grupo'][$index];
                    $group_sums[$group]['pot_va_total'] = ($group_sums[$group]['pot_va_total'] ?? 0) + ($pot_va * $_GET['qtd'][$index]);

                    // Adds the line's W power to the total W power for the group
                    $group_sums[$group]['pot_w_total'] = ($group_sums[$group]['pot_w_total'] ?? 0) + $pot_w_total;

                    echo "<td>" . ($pot_va * $_GET['qtd'][$index]) . "</td>";

                    // Obtém o fator de demanda do grupo atual
                    $fator_demanda_grupo = $fatores_demanda[$grupo][$index] ?? 0; // Valor padrão é 0 se não houver fator de demanda definido
                    echo "<td>" . $fator_demanda_grupo . "</td>"; // Exibe o fator de demanda

                    echo "</tr>";
                }
            }

            // Shows the total line for each group
            foreach ($group_sums as $group => $sums) {
                echo "<tr>";
                echo "<td colspan='7'>$group</td>";
                echo "<td>" . ($sums['pot_w_total'] ?? 0) . "</td>";
                echo "<td>" . ($sums['pot_va_total'] ?? 0) . "</td>";
                echo "<td></td>"; // Added space for the Demand Factor
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='10'>Nenhum dado recebido.</td></tr>";
        }
        ?>
    </table>
</body>

</html>