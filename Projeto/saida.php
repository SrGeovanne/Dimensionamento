<?php
// Inicia a sessão
session_start();

// Inclui o arquivo com a função para calcular o fator de demanda dos grupos
require_once 'fd.php';

// Função para calcular os fatores de demanda para todos os grupos
function calcularFatoresDemanda($potencias, $ramo_selecionado, $cargas, $quantidades)
{
    $fatores_demanda = [];

    foreach ($potencias as $grupo => $potencia) {
        if ($grupo === 'A') {
            $fatores_demanda[$grupo] = calcularFatorDemandaGrupoA($ramo_selecionado, $potencia);
        } elseif ($grupo === 'B') {
            $fatores_demanda[$grupo] = [];
            foreach ($potencia as $index => $pot) {
                $fatores_demanda[$grupo][] = calcularFatorDemandaGrupoB($cargas[$index], $quantidades[$index]);
            }
        } elseif ($grupo === 'C') {
            $fatores_demanda[$grupo] = [];
            foreach ($potencia as $index => $pot) {
                $fatores_demanda[$grupo][] = calcularFatorDemandaGrupoC($quantidades[$index]);
            }
        } elseif ($grupo === 'D') {
            $fatores_demanda[$grupo] = [];
            foreach ($potencia as $index => $pot) {
                $fatores_demanda[$grupo][] = calcularFatorDemandaGrupoD($quantidades[$index]);
            }
        } elseif ($grupo === 'E') {
            $fatores_demanda[$grupo] = [];
            foreach ($potencia as $index => $pot) {
                $fatores_demanda[$grupo][] = calcularFatorDemandaGrupoE($quantidades[$index]);
            }
        } else {
            $fatores_demanda[$grupo] = array_fill(0, count($potencia), 0); // Define o fator de demanda inicial como 0 para grupos não especificados
        }
    }

    return $fatores_demanda;
}

// Verifica se os dados foram enviados por GET
if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET)) {
    // Inicializa os arrays de potências, cargas e quantidades
    $potencias = [];
    $cargas = [];
    $quantidades = [];

    // Agrupa as potências, cargas e quantidades por grupo
    foreach ($_GET['grupo'] as $index => $grupo) {
        $potencia = floatval($_GET['pot_w'][$index]);
        $potencias[$grupo][] = $potencia;
        $cargas[$index] = $_GET['carga'][$index];
        $quantidades[$index] = intval($_GET['qtd'][$index]);
    }

    // Verifica se o ramo selecionado está definido na sessão
    $ramo_selecionado = isset($_SESSION['ramo_selecionado']) ? $_SESSION['ramo_selecionado'] : '';

    // Calcula os fatores de demanda com base nas potências recebidas e no ramo selecionado
    $fatores_demanda = calcularFatoresDemanda($potencias, $ramo_selecionado, $cargas, $quantidades);
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

    // Verifique se existem dados na URL
    if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET)) {
        // Associative array to store sums according to the group
        $group_sums = [];

        // Agrupa os índices por grupo
        $indices_por_grupo = [];
        foreach ($_GET['grupo'] as $index => $grupo) {
            $indices_por_grupo[$grupo][] = $index;
        }

        // Itera sobre cada grupo para exibir uma tabela separada
        foreach ($indices_por_grupo as $grupo => $indices) {
            echo "<h3>Grupo: " . htmlspecialchars($grupo) . "</h3>";
            echo "<table border='1'>";
            echo "<tr>
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
                <th>Potência Total com FD</th>
            </tr>";

            foreach ($indices as $index) {
                // Verifica se o índice atual possui os dados esperados
                if (isset($_GET['grupo'][$index]) && isset($_GET['qtd'][$index]) && isset($_GET['pot_w'][$index]) && isset($_GET['fp'][$index])) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($_GET['grupo'][$index]) . "</td>";
                    echo "<td>" . (isset($_GET['carga'][$index]) ? htmlspecialchars($_GET['carga'][$index]) : '') . "</td>";
                    echo "<td>" . (isset($_GET['Descricoes'][$index]) ? htmlspecialchars($_GET['Descricoes'][$index]) : '') . "</td>";
                    echo "<td>" . htmlspecialchars($_GET['qtd'][$index]) . "</td>";
                    echo "<td>" . htmlspecialchars($_GET['pot_w'][$index]) . "</td>";

                    // Define FP conforme o grupo
                    if ($_GET['grupo'][$index] === 'C' || $_GET['grupo'][$index] === 'D') {
                        $fp = 1;
                        echo "<td>" . $fp . "</td>";
                    } else {
                        $fp = floatval($_GET['fp'][$index]);
                        echo "<td>" . number_format($fp, 2) . "</td>";
                    }

                    // Calcula a potência VA
                    $pot_va = $_GET['pot_w'][$index] / $fp;
                    echo "<td>" . number_format($pot_va * $_GET['qtd'][$index], ($pot_va * $_GET['qtd'][$index] == intval($pot_va * $_GET['qtd'][$index])) ? 0 : 2) . "</td>";

                    // Calcula a potência W total
                    $pot_w_total = $_GET['pot_w'][$index] * $_GET['qtd'][$index];
                    echo "<td>" . number_format($pot_w_total, ($pot_w_total == intval($pot_w_total)) ? 0 : 2) . "</td>";

                    // Adiciona a potência VA da linha ao total do grupo
                    $group_sums[$grupo]['pot_va_total'] = ($group_sums[$grupo]['pot_va_total'] ?? 0) + ($pot_va * $_GET['qtd'][$index]);

                    // Adiciona a potência W da linha ao total do grupo
                    $group_sums[$grupo]['pot_w_total'] = ($group_sums[$grupo]['pot_w_total'] ?? 0) + $pot_w_total;

                    echo "<td>" . number_format($pot_va * $_GET['qtd'][$index], ($pot_va * $_GET['qtd'][$index] == intval($pot_va * $_GET['qtd'][$index])) ? 0 : 2) . "</td>";

                    // Obtém o fator de demanda do grupo atual
                    $fator_demanda_grupo = ($fatores_demanda[$grupo][$index - $indices[0]] ?? 0);
                    echo "<td>" . number_format($fator_demanda_grupo, 2) . "</td>";

                    // Calcula a potência total considerando o fator de demanda
                    $potencia_total_com_fd = $pot_w_total * $fator_demanda_grupo;
                    echo "<td>" . number_format($potencia_total_com_fd, ($potencia_total_com_fd == intval($potencia_total_com_fd)) ? 0 : 2) . "</td>";

                    echo "</tr>";
                }
            }

            // Exibe a linha de total para o grupo atual
            echo "<tr>";
            echo "<td colspan='7'>Total do Grupo $grupo</td>";
            echo "<td>" . number_format(($group_sums[$grupo]['pot_w_total'] ?? 0), (($group_sums[$grupo]['pot_w_total'] ?? 0) == intval(($group_sums[$grupo]['pot_w_total'] ?? 0))) ? 0 : 2) . "</td>";
            echo "<td>" . number_format(($group_sums[$grupo]['pot_va_total'] ?? 0), (($group_sums[$grupo]['pot_va_total'] ?? 0) == intval(($group_sums[$grupo]['pot_va_total'] ?? 0))) ? 0 : 2) . "</td>";
            echo "<td></td>";
            $total_fd_group = array_reduce(array_keys($indices), function($carry, $i) use ($grupo, $fatores_demanda, $group_sums) {
                return $carry + (($group_sums[$grupo]['pot_w_total'] ?? 0) * ($fatores_demanda[$grupo][$i] ?? 0));
            }, 0);
            echo "<td>" . number_format($total_fd_group, ($total_fd_group == intval($total_fd_group)) ? 0 : 2) . "</td>";
            echo "</tr>";

            echo "</table><br>";
        }

        // Calcula e exibe a linha de total geral
        $total_w_fd = array_reduce(array_keys($group_sums), function ($carry, $group) use ($group_sums, $fatores_demanda) {
            return $carry + array_reduce(array_keys($fatores_demanda[$group]), function($carry_inner, $i) use ($group, $fatores_demanda, $group_sums) {
                return $carry_inner + (($group_sums[$group]['pot_w_total'] ?? 0) * ($fatores_demanda[$group][$i] ?? 0));
            }, 0);
        }, 0);
        echo "<h3>Total Geral</h3>";
        echo "<table border='1'>";
        echo "<tr>";
        echo "<td colspan='7'>Total Geral</td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td>" . number_format($total_w_fd, ($total_w_fd == intval($total_w_fd)) ? 0 : 2) . "</td>";
        echo "</tr>";
        echo "</table>";
    } else {
        echo "<tr><td colspan='10'>Nenhum dado recebido.</td></tr>";
    }
    ?>

    <!-- Botão de Continuar -->
    <form action="transformador.php" method="GET">
        <input type="hidden" name="potencia_total_fd" value="<?php echo htmlspecialchars(number_format($total_w_fd, ($total_w_fd == intval($total_w_fd)) ? 0 : 2)); ?>">
        <input type="submit" value="Continuar">
    </form>
</body>

</html>
