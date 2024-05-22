<?php
function calcularFatorDemandaGrupoB($carga, $qtd)
{
    $fatores_demanda = [
        'chuveiro_eletrico' => [
            1 => 1.00, 2 => 0.80, 3 => 0.67, 4 => 0.55, 5 => 0.50, 6 => 0.39, 7 => 0.36, 8 => 0.33, 9 => 0.31, 10 => 0.30,
            11 => 0.30, 12 => 0.29, 13 => 0.29, 14 => 0.29, 15 => 0.28, 16 => 0.28, 17 => 0.28, 18 => 0.28, 19 => 0.28, 20 => 0.27,
            21 => 0.27, 22 => 0.27, 23 => 0.27, 24 => 0.27, 25 => 0.26, 26 => 0.26, 27 => 0.26, 28 => 0.26, 29 => 0.26, 30 => 0.25,
            31 => 0.25, 32 => 0.25, 33 => 0.25, 34 => 0.25, 35 => 0.25, 36 => 0.25, 37 => 0.25, 38 => 0.25, 39 => 0.25, 40 => 0.25
        ],
        'torneira_eletrica' => [
            1 => 0.96, 2 => 0.72, 3 => 0.62, 4 => 0.57, 5 => 0.54, 6 => 0.52, 7 => 0.50, 8 => 0.49, 9 => 0.48, 10 => 0.46,
            11 => 0.46, 12 => 0.44, 13 => 0.44, 14 => 0.44, 15 => 0.42, 16 => 0.42, 17 => 0.42, 18 => 0.42, 19 => 0.42, 20 => 0.40,
            21 => 0.40, 22 => 0.40, 23 => 0.40, 24 => 0.40, 25 => 0.38, 26 => 0.38, 27 => 0.38, 28 => 0.38, 29 => 0.38, 30 => 0.36,
            31 => 0.36, 32 => 0.36, 33 => 0.36, 34 => 0.36, 35 => 0.36, 36 => 0.36, 37 => 0.36, 38 => 0.36, 39 => 0.36, 40 => 0.35
        ]
        // Adicione mais tipos de carga aqui conforme necessário
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
?>
