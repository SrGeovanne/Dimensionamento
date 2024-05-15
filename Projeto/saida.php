<?php
// Inicia a sessão
session_start();

// Inclui o arquivo com a função para calcular o fator de demanda do grupo A
require_once 'fd.php';

// Função para calcular os fatores de demanda para todos os grupos
function calcularFatoresDemanda($potencias, $ramo_selecionado)
{
    $fatores_demanda = [];

    foreach ($potencias as $grupo => $potencia) {
        if ($grupo === 'E') {
            // Define o fator de demanda do grupo E
            $maior_potencia = max($potencia);
            foreach ($potencia as $p) {
                $fatores_demanda[$grupo][] = ($p == $maior_potencia) ? 1 : 0.6;
            }
        } elseif ($grupo === 'A') {
            // Calcula o fator de demanda do grupo A com base no ramo selecionado
            $fatores_demanda[$grupo] = calcularFatorDemandaGrupoA($ramo_selecionado, $potencia);
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
            <th>Potência Total com FD</th> <!-- Adicionado -->
        </tr>
        <?php
        // Verifique se existem dados na URL
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
                    if ($grupo === 'A') {
                        $fator_demanda_grupo = $fatores_demanda[$grupo] ?? 0; // Valor padrão é 0 se não houver fator de demanda definido
                    } elseif ($grupo === 'E') {
                        $fator_demanda_grupo = $fatores_demanda[$grupo][$index] ?? 0; // Valor padrão é 0 se não houver fator de demanda definido
                    } else {
                        $fator_demanda_grupo = 0;
                    }
                    echo "<td>" . $fator_demanda_grupo . "</td>"; // Exibe o fator de demanda

                    // Calculates the total power considering the demand factor
                    $potencia_total_com_fd = $pot_w_total * $fator_demanda_grupo;
                    echo "<td>" . $potencia_total_com_fd . "</td>"; // Exibe a potência total com o fator de demanda

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
                // Checks if the demand factor is numeric before calculating the total power with FD
                if (is_numeric($fatores_demanda[$group])) {
                    echo "<td>" . ($sums['pot_w_total'] ?? 0) * ($fatores_demanda[$group] ?? 0) . "</td>"; // Total power with FD
                } else {
                    echo "<td>0</td>"; // Default to 0 if the demand factor is not numeric
                }
                echo "</tr>";
            }

            // Calculates and shows the total line for all groups
            $total_w_fd = 0;
            foreach ($group_sums as $group => $sums) {
                // Checks if the demand factor for the current group is numeric before calculating the total power with FD for all groups
                if (is_numeric($fatores_demanda[$group])) {
                    $total_w_fd += ($sums['pot_w_total'] ?? 0) * ($fatores_demanda[$group] ?? 0); // Calculates and accumulates the total power with FD for all groups
                }
            }
            echo "<tr>";
            echo "<td colspan='7'>Total Geral</td>";
            echo "<td></td>"; // Empty cell for Potência W Total
            echo "<td></td>"; // Empty cell for Potência VA Total
            echo "<td></td>"; // Added space for the Demand Factor
            echo "<td>$total_w_fd</td>"; // Displays the total power with FD for all groups
            echo "</tr>";
        } else {
            echo "<tr><td colspan='10'>Nenhum dado recebido.</td></tr>";
        }
        ?>
    </table>
    
    <!-- Botão de Continuar -->
    <form action="transformador.php" method="GET">
        <input type="hidden" name="potencia_total_fd" value="<?php echo $total_w_fd; ?>">
        <input type="submit" value="Continuar">
    </form>
</body>

</html>
