<?php
// Função para calcular o fator de demanda do grupo A com base no ramo selecionado e na potência total
function calcularFatorDemandaGrupoA($ramo_selecionado, $pot_w)
{
    // Define os ramos com fator de demanda igual a 1 para o grupo A
    $ramos_com_fator_1 = ['auditorio', 'bancos', 'barbearias', 'clubes', 'igreja', 'garagem', 'restaurantes'];

    // Verifica se o ramo selecionado está na lista de ramos com fator 1
    if (in_array($ramo_selecionado, $ramos_com_fator_1)) {
        return array_fill(0, count($pot_w), 1); // Retorna um array de 1s se o ramo estiver na lista
    }

    $fator_demanda = [];
    $potencia_acumulada = 0;

    foreach ($pot_w as $potencia) {
        $potencia_acumulada += $potencia;

        if ($ramo_selecionado === 'escritorios') {
            // Se o ramo for 'escritórios', verifica as especificações adicionais
            if ($potencia_acumulada <= 20000) {
                $fator_demanda[] = 1;
            } else {
                $fator_demanda[] = 0.7;
            }
        } elseif ($ramo_selecionado === 'escola') {
            // Se o ramo for 'escola', verifica as especificações adicionais
            if ($potencia_acumulada <= 12000) {
                $fator_demanda[] = 1;
            } else {
                $fator_demanda[] = 0.5;
            }
        } elseif ($ramo_selecionado === 'hospitais') {
            // Se o ramo for 'hospitais', verifica as especificações adicionais
            if ($potencia_acumulada <= 50000) {
                $fator_demanda[] = 0.4;
            } else {
                $fator_demanda[] = 0.2;
            }
        } elseif ($ramo_selecionado === 'hoteis') {
            // Se o ramo for 'hotéis', verifica as especificações adicionais
            if ($potencia_acumulada <= 20000) {
                $fator_demanda[] = 0.5;
            } elseif ($potencia_acumulada <= 100000) {
                $fator_demanda[] = 0.4;
            } else {
                $fator_demanda[] = 0.3;
            }
        } elseif ($ramo_selecionado === 'residencias') {
            // Se o ramo for 'residências', verifica as especificações adicionais
            if ($potencia_acumulada <= 10000) {
                $fator_demanda[] = 1;
            } elseif ($potencia_acumulada <= 120000) {
                $fator_demanda[] = 0.35;
            } else {
                $fator_demanda[] = 0.25;
            }
        } else {
            $fator_demanda[] = 0; // Retorna 0 para outros ramos
        }
    }

    return $fator_demanda;
}

// Função para calcular o fator de demanda do grupo B com base na quantidade de aparelhos
function calcularFatorDemandaGrupoB($carga, $qtd)
{
    $fatores_demanda = [
        'chuveiro eletrico' => [
            1 => 1.00, 2 => 0.80, 3 => 0.67, 4 => 0.55, 5 => 0.50, 6 => 0.39, 7 => 0.36, 8 => 0.33, 9 => 0.31, 10 => 0.30,
            11 => 0.30, 12 => 0.29, 13 => 0.29, 14 => 0.29, 15 => 0.28, 16 => 0.28, 17 => 0.28, 18 => 0.28, 19 => 0.28, 20 => 0.27,
            21 => 0.27, 22 => 0.27, 23 => 0.27, 24 => 0.27, 25 => 0.26, 26 => 0.26, 27 => 0.26, 28 => 0.26, 29 => 0.26, 30 => 0.25,
            31 => 0.25, 32 => 0.31, 33 => 0.31, 34 => 0.31, 35 => 0.31, 36 => 0.31, 37 => 0.31, 38 => 0.31, 39 => 0.31, 40 => 0.30,
            41 => 0.25, 46 => 0.25, 56 => 0.24, 65 => 0.24, 76 => 0.24, 81 => 0.23, 91 => 0.23, 101 => 0.22, 121 => 0.21,
            151 => 0.20, 201 => 0.21, 251 => 0.20, 351 => 0.20, 451 => 0.20
        ],
        'torneira eletrica' => [
            1 => 0.96, 2 => 1.00, 3 => 0.72, 4 => 0.62, 5 => 0.57, 6 => 0.54, 7 => 0.50, 8 => 0.49, 9 => 0.48, 10 => 0.46,
            11 => 0.46, 12 => 0.44, 13 => 0.44, 14 => 0.44, 15 => 0.43, 16 => 0.42, 17 => 0.42, 18 => 0.42, 19 => 0.42, 20 => 0.40,
            21 => 0.40, 22 => 0.40, 23 => 0.40, 24 => 0.40, 25 => 0.38, 26 => 0.38, 27 => 0.38, 28 => 0.38, 29 => 0.38, 30 => 0.37,
            31 => 0.37, 32 => 0.35, 33 => 0.35, 34 => 0.35, 35 => 0.35, 36 => 0.35, 37 => 0.35, 38 => 0.35, 39 => 0.35, 40 => 0.35,
            41 => 0.35, 46 => 0.34, 56 => 0.33, 65 => 0.32, 76 => 0.31, 81 => 0.31, 91 => 0.30, 101 => 0.30, 121 => 0.29,
            151 => 0.28, 201 => 0.27, 251 => 0.26, 351 => 0.25, 451 => 0.24
        ],
        'Aquecedor passagem' => [
            1 => 1.00, 2 => 1.00, 3 => 0.72, 4 => 0.62, 5 => 0.57, 6 => 0.54, 7 => 0.50, 8 => 0.49, 9 => 0.48, 10 => 0.46,
            11 => 0.46, 12 => 0.44, 13 => 0.44, 14 => 0.44, 15 => 0.43, 16 => 0.42, 17 => 0.42, 18 => 0.42, 19 => 0.42, 20 => 0.40,
            21 => 0.40, 22 => 0.40, 23 => 0.40, 24 => 0.40, 25 => 0.38, 26 => 0.38, 27 => 0.38, 28 => 0.38, 29 => 0.38, 30 => 0.37,
            31 => 0.37, 32 => 0.35, 33 => 0.35, 34 => 0.35, 35 => 0.35, 36 => 0.35, 37 => 0.35, 38 => 0.35, 39 => 0.35, 40 => 0.35,
            41 => 0.35, 46 => 0.34, 56 => 0.33, 65 => 0.32, 76 => 0.31, 81 => 0.31, 91 => 0.30, 101 => 0.30, 121 => 0.29,
            151 => 0.28, 201 => 0.27, 251 => 0.26, 351 => 0.25, 451 => 0.24
        ],
        'ferro eletrico' => [
            1 => 1.00, 2 => 1.00, 3 => 0.72, 4 => 0.62, 5 => 0.57, 6 => 0.54, 7 => 0.50, 8 => 0.49, 9 => 0.48, 10 => 0.46,
            11 => 0.46, 12 => 0.44, 13 => 0.44, 14 => 0.44, 15 => 0.43, 16 => 0.42, 17 => 0.42, 18 => 0.42, 19 => 0.42, 20 => 0.40,
            21 => 0.40, 22 => 0.40, 23 => 0.40, 24 => 0.40, 25 => 0.38, 26 => 0.38, 27 => 0.38, 28 => 0.38, 29 => 0.38, 30 => 0.37,
            31 => 0.37, 32 => 0.35, 33 => 0.35, 34 => 0.35, 35 => 0.35, 36 => 0.35, 37 => 0.35, 38 => 0.35, 39 => 0.35, 40 => 0.35,
            41 => 0.35, 46 => 0.34, 56 => 0.33, 65 => 0.32, 76 => 0.31, 81 => 0.31, 91 => 0.30, 101 => 0.30, 121 => 0.29,
            151 => 0.28, 201 => 0.27, 251 => 0.26, 351 => 0.25, 451 => 0.24
        ],
        'fogao eletrico' => [
            1 => 1.00, 2 => 0.60, 3 => 0.48, 4 => 0.40, 5 => 0.37, 6 => 0.35, 7 => 0.32, 8 => 0.32, 9 => 0.31, 10 => 0.30,
            11 => 0.30, 12 => 0.28, 13 => 0.28, 14 => 0.28, 15 => 0.28, 16 => 0.27, 17 => 0.27, 18 => 0.27, 19 => 0.27, 20 => 0.27,
            21 => 0.26, 22 => 0.26, 23 => 0.26, 24 => 0.26, 25 => 0.25, 26 => 0.25, 27 => 0.25, 28 => 0.25, 29 => 0.25, 30 => 0.24,
            31 => 0.24, 32 => 0.24, 33 => 0.24, 34 => 0.24, 35 => 0.24, 36 => 0.24, 37 => 0.24, 38 => 0.24, 39 => 0.24, 40 => 0.24,
            41 => 0.24, 46 => 0.24, 56 => 0.24, 65 => 0.24, 76 => 0.24, 81 => 0.24, 91 => 0.24, 101 => 0.24, 121 => 0.24,
            151 => 0.24, 201 => 0.24, 251 => 0.24, 351 => 0.24, 451 => 0.24
        ],
        'maquina secar roupa' => [
            1 => 1.00, 2 => 0.60, 3 => 0.48, 4 => 0.40, 5 => 0.37, 6 => 0.35, 7 => 0.32, 8 => 0.32, 9 => 0.31, 10 => 0.30,
            11 => 0.30, 12 => 0.28, 13 => 0.28, 14 => 0.28, 15 => 0.28, 16 => 0.27, 17 => 0.27, 18 => 0.27, 19 => 0.27, 20 => 0.27,
            21 => 0.26, 22 => 0.26, 23 => 0.26, 24 => 0.26, 25 => 0.25, 26 => 0.25, 27 => 0.25, 28 => 0.25, 29 => 0.25, 30 => 0.24,
            31 => 0.24, 32 => 0.24, 33 => 0.24, 34 => 0.24, 35 => 0.24, 36 => 0.24, 37 => 0.24, 38 => 0.24, 39 => 0.24, 40 => 0.24,
            41 => 0.24, 46 => 0.24, 56 => 0.24, 65 => 0.24, 76 => 0.24, 81 => 0.23, 91 => 0.23, 101 => 0.22, 121 => 0.21,
            151 => 0.20, 201 => 0.21, 251 => 0.20, 351 => 0.20, 451 => 0.20
        ],
        'maquina lavar_louca' => [
            1 => 1.00, 2 => 0.60, 3 => 0.48, 4 => 0.40, 5 => 0.37, 6 => 0.35, 7 => 0.32, 8 => 0.32, 9 => 0.31, 10 => 0.30,
            11 => 0.30, 12 => 0.28, 13 => 0.28, 14 => 0.28, 15 => 0.28, 16 => 0.27, 17 => 0.27, 18 => 0.27, 19 => 0.27, 20 => 0.27,
            21 => 0.26, 22 => 0.26, 23 => 0.26, 24 => 0.26, 25 => 0.25, 26 => 0.25, 27 => 0.25, 28 => 0.25, 29 => 0.25, 30 => 0.24,
            31 => 0.24, 32 => 0.24, 33 => 0.24, 34 => 0.24, 35 => 0.24, 36 => 0.24, 37 => 0.24, 38 => 0.24, 39 => 0.24, 40 => 0.24,
            41 => 0.24, 46 => 0.24, 56 => 0.24, 65 => 0.24, 76 => 0.24, 81 => 0.23, 91 => 0.23, 101 => 0.22, 121 => 0.21,
            151 => 0.20, 201 => 0.21, 251 => 0.20, 351 => 0.20, 451 => 0.20
        ],
        'forno eletrico' => [
            1 => 1.00, 2 => 0.60, 3 => 0.48, 4 => 0.40, 5 => 0.37, 6 => 0.35, 7 => 0.32, 8 => 0.32, 9 => 0.31, 10 => 0.30,
            11 => 0.30, 12 => 0.28, 13 => 0.28, 14 => 0.28, 15 => 0.28, 16 => 0.27, 17 => 0.27, 18 => 0.27, 19 => 0.27, 20 => 0.27,
            21 => 0.26, 22 => 0.26, 23 => 0.26, 24 => 0.26, 25 => 0.25, 26 => 0.25, 27 => 0.25, 28 => 0.25, 29 => 0.25, 30 => 0.24,
            31 => 0.24, 32 => 0.24, 33 => 0.24, 34 => 0.24, 35 => 0.24, 36 => 0.24, 37 => 0.24, 38 => 0.24, 39 => 0.24, 40 => 0.24,
            41 => 0.24, 46 => 0.24, 56 => 0.24, 65 => 0.24, 76 => 0.24, 81 => 0.23, 91 => 0.23, 101 => 0.22, 121 => 0.21,
            151 => 0.20, 201 => 0.21, 251 => 0.20, 351 => 0.20, 451 => 0.20
        ],
        'microondas' => [
            1 => 1.00, 2 => 0.60, 3 => 0.48, 4 => 0.40, 5 => 0.37, 6 => 0.35, 7 => 0.32, 8 => 0.32, 9 => 0.31, 10 => 0.30,
            11 => 0.30, 12 => 0.28, 13 => 0.28, 14 => 0.28, 15 => 0.28, 16 => 0.27, 17 => 0.27, 18 => 0.27, 19 => 0.27, 20 => 0.27,
            21 => 0.26, 22 => 0.26, 23 => 0.26, 24 => 0.26, 25 => 0.25, 26 => 0.25, 27 => 0.25, 28 => 0.25, 29 => 0.25, 30 => 0.24,
            31 => 0.24, 32 => 0.24, 33 => 0.24, 34 => 0.24, 35 => 0.24, 36 => 0.24, 37 => 0.24, 38 => 0.24, 39 => 0.24, 40 => 0.24,
            41 => 0.24, 46 => 0.24, 56 => 0.24, 65 => 0.24, 76 => 0.24, 81 => 0.23, 91 => 0.23, 101 => 0.22, 121 => 0.21,
            151 => 0.20, 201 => 0.21, 251 => 0.20, 351 => 0.20, 451 => 0.20
        ],
    ];

    if (isset($fatores_demanda[$carga])) {
        if (isset($fatores_demanda[$carga][$qtd])) {
            return $fatores_demanda[$carga][$qtd];
        } else {
            // Se a quantidade for maior que a maior chave no array, usar o maior fator disponível
            return end($fatores_demanda[$carga]);
        }
    }

    return 0; // Retorna 0 se o tipo de carga não for encontrado
}

// Função para calcular o fator de demanda do grupo C com base no número de aparelhos
function calcularFatorDemandaGrupoC($num_aparelhos)
{
    if ($num_aparelhos <= 2) {
        return 1.00;
    } elseif ($num_aparelhos == 3) {
        return 0.88;
    } elseif ($num_aparelhos == 4) {
        return 0.82;
    } elseif ($num_aparelhos == 5) {
        return 0.78;
    } elseif ($num_aparelhos == 6) {
        return 0.76;
    } elseif ($num_aparelhos == 7) {
        return 0.74;
    } elseif ($num_aparelhos == 8) {
        return 0.72;
    } elseif ($num_aparelhos == 9) {
        return 0.71;
    } elseif ($num_aparelhos >= 10) {
        return 0.70;
    } else {
        return 0; // Retorna 0 para outros valores
    }
}

// Função para calcular o fator de demanda do grupo D com base na potência
function calcularFatorDemandaGrupoD($potencias)
{
    $maior_potencia = max($potencias);
    $fatores_demanda = [];
    foreach ($potencias as $potencia) {
        $fatores_demanda[] = ($potencia == $maior_potencia) ? 1 : 0.7;
    }
    return $fatores_demanda;
}

// Função para calcular o fator de demanda do grupo E com base na potência
function calcularFatorDemandaGrupoE($potencias)
{
    $maior_potencia = max($potencias);
    $fatores_demanda = [];
    foreach ($potencias as $potencia) {
        $fatores_demanda[] = ($potencia == $maior_potencia) ? 1 : 0.6;
    }
    return $fatores_demanda;
}
?>
