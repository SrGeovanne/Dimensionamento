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
        'chuveiro_eletrico' => [
            1 => 1.00, 2 => 0.80, 3 => 0.67, 4 => 0.55, 5 => 0.50, 6 => 0.39, 7 => 0.36, 8 => 0.33, 9 => 0.31, 10 => 0.30,
            11 => 0.30, 12 => 0.29, 13 => 0.29, 14 => 0.29, 15 => 0.28, 16 => 0.28, 17 => 0.28, 18 => 0.28, 19 => 0.28, 20 => 0.27,
            21 => 0.27, 22 => 0.27, 23 => 0.27, 24 => 0.27, 25 => 0.26, 26 => 0.26, 27 => 0.26, 28 => 0.26, 29 => 0.26, 30 => 0.25,
            31 => 0.25, 32 => 0.31, 33 => 0.31, 34 => 0.31, 35 => 0.31, 36 => 0.31, 37 => 0.31, 38 => 0.31, 39 => 0.31, 40 => 0.30
        ],
        // Adicione outros tipos de carga e seus fatores de demanda aqui conforme necessário
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
