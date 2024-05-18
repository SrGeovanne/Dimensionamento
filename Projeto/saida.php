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
        echo "<h2>Ramo selecionado: " . htmlspecialchars($_SESSION['ramo_selecionado']) . "</h2>";
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
                // Verifica se o índice atual possui os dados esperados
                if (isset($_GET['grupo'][$index]) && isset($_GET['qtd'][$index]) && isset($_GET['pot_w'][$index]) && isset($_GET['fp'][$index])) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($_GET['grupo'][$index]) . "</td>";
                    echo "<td>" . (isset($_GET['tipo_carga'][$index]) ? htmlspecialchars($_GET['tipo_carga'][$index]) : '') . "</td>";
                    echo "<td>" . (isset($_GET['descricao'][$index]) ? htmlspecialchars($_GET['descricao'][$index]) : '') . "</td>";
                    echo "<td>" . htmlspecialchars($_GET['qtd'][$index]) . "</td>";
                    echo "<td>" . htmlspecialchars($_GET['pot_w'][$index]) . "</td>";

                    // Define FP conforme o grupo
                    if ($_GET['grupo'][$index] === 'C' || $_GET['grupo'][$index] === 'D') {
                        $fp = 1;
                        echo "<td>" . $fp . "</td>";
                    } else {
                        $fp = floatval($_GET['fp'][$index]);
                        echo "<td>" . $fp . "</td>";
                    }

                    // Calcula a potência VA
                    $pot_va = $_GET['pot_w'][$index] / $fp;
                    echo "<td>" . ($pot_va * $_GET['qtd'][$index]) . "</td>";

                    // Calcula a potência W total
                    $pot_w_total = $_GET['pot_w'][$index] * $_GET['qtd'][$index];
                    echo "<td>" . $pot_w_total . "</td>";

                    // Adiciona a potência VA da linha ao total do grupo
                    $group_sums[$grupo]['pot_va_total'] = ($group_sums[$grupo]['pot_va_total'] ?? 0) + ($pot_va * $_GET['qtd'][$index]);

                    // Adiciona a potência W da linha ao total do grupo
                    $group_sums[$grupo]['pot_w_total'] = ($group_sums[$grupo]['pot_w_total'] ?? 0) + $pot_w_total;

                    echo "<td>" . ($pot_va * $_GET['qtd'][$index]) . "</td>";

                    // Obtém o fator de demanda do grupo atual
                    $fator_demanda_grupo = ($grupo === 'A') ? ($fatores_demanda[$grupo] ?? 0) : (($grupo === 'E') ? ($fatores_demanda[$grupo][$index] ?? 0) : 0);
                    echo "<td>" . $fator_demanda_grupo . "</td>";

                    // Calcula a potência total considerando o fator de demanda
                    $potencia_total_com_fd = $pot_w_total * $fator_demanda_grupo;
                    echo "<td>" . $potencia_total_com_fd . "</td>";

                    echo "</tr>";
                }
            }

            // Exibe a linha de total para cada grupo
            foreach ($group_sums as $group => $sums) {
                echo "<tr>";
                echo "<td colspan='7'>$group</td>";
                echo "<td>" . ($sums['pot_w_total'] ?? 0) . "</td>";
                echo "<td>" . ($sums['pot_va_total'] ?? 0) . "</td>";
                echo "<td></td>";
                $total_fd_group = is_numeric($fatores_demanda[$group]) ? ($sums['pot_w_total'] ?? 0) * $fatores_demanda[$group] : 0;
                echo "<td>$total_fd_group</td>";
                echo "</tr>";
            }

            // Calcula e exibe a linha de total geral
            $total_w_fd = array_reduce(array_keys($group_sums), function ($carry, $group) use ($group_sums, $fatores_demanda) {
                return $carry + ((is_numeric($fatores_demanda[$group]) ? $group_sums[$group]['pot_w_total'] * $fatores_demanda[$group] : 0) ?? 0);
            }, 0);
            echo "<tr>";
            echo "<td colspan='7'>Total Geral</td>";
            echo "<td></td>";
            echo "<td></td>";
            echo "<td></td>";
            echo "<td>$total_w_fd</td>";
            echo "</tr>";
        } else {
            echo "<tr><td colspan='10'>Nenhum dado recebido.</td></tr>";
        }
        ?>
    </table>
    
    <!-- Botão de Continuar -->
    <form action="transformador.php" method="GET">
        <input type="hidden" name="potencia_total_fd" value="<?php echo htmlspecialchars($total_w_fd); ?>">
        <input type="submit" value="Continuar">
    </form>
</body>

</html>
