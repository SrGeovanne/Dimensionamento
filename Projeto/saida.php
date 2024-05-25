<?php
// Inicia a sessão
session_start();

// Inclui o arquivo com a função para calcular os fatores de demanda
require_once 'fd.php';

// Função para calcular os fatores de demanda para todos os grupos
function calcularFatoresDemanda($potencias, $ramo_selecionado)
{
    $fatores_demanda = [];

    foreach ($potencias as $grupo => $potencia) {
        if ($grupo === 'E') {
            $fatores_demanda[$grupo] = calcularFatorDemandaGrupoE($potencia);
        } elseif ($grupo === 'D') {
            $fatores_demanda[$grupo] = calcularFatorDemandaGrupoD($potencia);
        } elseif ($grupo === 'A') {
            $fatores_demanda[$grupo] = calcularFatorDemandaGrupoA($ramo_selecionado, $potencia);
        } elseif ($grupo === 'B') {
            $fatores_demanda[$grupo] = [];
            foreach ($potencia as $index => $pot) {
                $carga = $_GET['carga'][$index];
                $qtd = $_GET['qtd'][$index];
                $fatores_demanda[$grupo][] = calcularFatorDemandaGrupoB($carga, $qtd);
            }
        } elseif ($grupo === 'C') {
            $num_aparelhos = count($potencia);
            $fator_demanda_c = calcularFatorDemandaGrupoC($num_aparelhos);
            $fatores_demanda[$grupo] = array_fill(0, $num_aparelhos, $fator_demanda_c);
        } else {
            $fatores_demanda[$grupo] = array_fill(0, count($potencia), 0); // Define o fator de demanda inicial como 0 para os grupos não especificados
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
    <meta charset="ISO-8859-1">
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
        $total_w_fd = 0;

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

            $total_pot_fd_grupo = 0; // Inicializa o total do grupo

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
                    $fator_demanda_grupo = $fatores_demanda[$grupo][$index % count($fatores_demanda[$grupo])] ?? 0;
                    echo "<td>" . number_format($fator_demanda_grupo, 2) . "</td>";

                    // Calcula a potência total considerando o fator de demanda
                    $potencia_total_com_fd = $pot_w_total * $fator_demanda_grupo;
                    echo "<td>" . number_format($potencia_total_com_fd, ($potencia_total_com_fd == intval($potencia_total_com_fd)) ? 0 : 2) . "</td>";

                    // Soma a potência total com FD ao total geral
                    $total_w_fd += $potencia_total_com_fd;

                    // Atualiza o total do grupo
                    $total_pot_fd_grupo += $potencia_total_com_fd;

                    echo "</tr>";
                }
            }

            // Exibe a linha de total para o grupo atual
            echo "<tr>";
            echo "<td colspan='7'>Total do Grupo $grupo</td>";
            echo "<td>" . number_format(($group_sums[$grupo]['pot_w_total'] ?? 0), (($group_sums[$grupo]['pot_w_total'] ?? 0) == intval(($group_sums[$grupo]['pot_w_total'] ?? 0))) ? 0 : 2) . "</td>";
            echo "<td>" . number_format(($group_sums[$grupo]['pot_va_total'] ?? 0), (($group_sums[$grupo]['pot_va_total'] ?? 0) == intval(($group_sums[$grupo]['pot_va_total'] ?? 0))) ? 0 : 2) . "</td>";
            echo "<td></td>";
            echo "<td>" . number_format($total_pot_fd_grupo, ($total_pot_fd_grupo == intval($total_pot_fd_grupo)) ? 0 : 2) . "</td>"; // Exibe o total do grupo
            echo "</tr>";

            echo "</table><br>";
        }

        // Calcula a recomendação do transformador
        if ($total_w_fd >= 60000 && $total_w_fd <= 82000) {
            $transformador_recomendado = "75 kVA";
        } elseif ($total_w_fd >= 83000 && $total_w_fd <= 124000) {
            $transformador_recomendado = "112,5 kVA";
        } elseif ($total_w_fd >= 125000 && $total_w_fd <= 165000) {
            $transformador_recomendado = "150 kVA";
        } elseif ($total_w_fd >= 166000 && $total_w_fd <= 248000) {
            $transformador_recomendado = "225 kVA";
        } elseif ($total_w_fd >= 249000 && $total_w_fd <= 330000) {
            $transformador_recomendado = "300 kVA";
        } elseif ($total_w_fd >= 331000 && $total_w_fd <= 550000) {
            $transformador_recomendado = "500 kVA";
        } elseif ($total_w_fd >= 551000 && $total_w_fd <= 825000) {
            $transformador_recomendado = "750 kVA";
        } elseif ($total_w_fd >= 826000 && $total_w_fd <= 1100000) {
            $transformador_recomendado = "1000 kVA";
        } elseif ($total_w_fd >= 1101000 && $total_w_fd <= 1375000) {
            $transformador_recomendado = "1250 kVA";
        } elseif ($total_w_fd >= 1376000 && $total_w_fd <= 1650000) {
            $transformador_recomendado = "1500 kVA";
        } elseif ($total_w_fd >= 1651000 && $total_w_fd <= 2200000) {
            $transformador_recomendado = "2000 kVA";
        } elseif ($total_w_fd >= 2201000 && $total_w_fd <= 2717000) {
            $transformador_recomendado = "2500 kVA";
        } else {
            $transformador_recomendado = "não há transformador recomendável para essa potência";
        }

        // Exibe a linha de total geral e a recomendação do transformador
        echo "<h3>Total Geral</h3>";
        echo "<table border='1'>";
        echo "<tr>";
        echo "<td colspan='10'>Total Geral</td>";
        echo "<td>" . number_format($total_w_fd, ($total_w_fd == intval($total_w_fd)) ? 0 : 2) . "</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td colspan='10'>Transformador Recomendado</td>";
        echo "<td>$transformador_recomendado</td>";
        echo "</tr>";
        echo "</table>";
    } else {
        echo "<tr><td colspan='11'>Nenhum dado recebido.</td></tr>";
    }
    ?>
</body>

</html>
