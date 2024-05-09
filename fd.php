<?php
// Função para calcular o fator de demanda do grupo A com base no ramo selecionado e na potência total
function calcularFatorDemandaGrupoA($ramo_selecionado, $pot_w)
{
    // Define os ramos com fator de demanda igual a 1 para o grupo A
    $ramos_com_fator_1 = ['auditorio', 'bancos', 'barbearias', 'clubes', 'igreja', 'garagem'];

    // Verifica se o ramo selecionado está na lista de ramos com fator 1
    if (in_array($ramo_selecionado, $ramos_com_fator_1)) {
        return 1; // Retorna 1 se o ramo estiver na lista
    } elseif ($ramo_selecionado === 'escritorios') {
        // Se o ramo for 'escritórios', verifica as especificações adicionais
        $total_pot_w = array_sum($pot_w); // Calcula o total de potências W

        // Se o total de potências W for menor ou igual a 20 kW (20000 W), retorna 1
        if ($total_pot_w <= 20) {
            return 1;
        } else {
            // Se o total de potências W exceder 20 kW, calcula o fator de demanda com base no excesso
            $excesso_pot_w = $total_pot_w - 20; // Calcula o excesso de potência além dos primeiros 20 kW
            $fator_demanda = 1 + ($excesso_pot_w / 7000); // Calcula o fator de demanda
            return min(0.7, $fator_demanda); // Retorna o fator de demanda, limitado a no máximo 0.7
        }
    } elseif ($ramo_selecionado === 'escola') {
        // Se o ramo for 'escola', verifica as especificações adicionais
        $total_pot_w = array_sum($pot_w); // Calcula o total de potências W

        // Se o total de potências W for menor ou igual a 12 kW (12000 W), retorna 1
        if ($total_pot_w <= 12) {
            return 1;
        } else {
            // Se o total de potências W exceder 12 kW, retorna 0.5
            return 0.5;
        }
    } elseif ($ramo_selecionado === 'hospitais') {
        // Se o ramo for 'hospitais', verifica as especificações adicionais
        $total_pot_w = array_sum($pot_w); // Calcula o total de potências W

        // Se o total de potências W for menor ou igual a 50 kW (50000 W), retorna 0.4
        if ($total_pot_w <= 50) {
            return 0.4;
        } else {
            // Se o total de potências W exceder 50 kW, retorna 0.2
            return 0.2;
        }
    } elseif ($ramo_selecionado === 'hoteis') {
        // Se o ramo for 'hotéis', verifica as especificações adicionais
        $total_pot_w = array_sum($pot_w); // Calcula o total de potências W

        // Se o total de potências W for menor ou igual a 20 kW, retorna 0.5
        if ($total_pot_w <= 20) {
            return 0.5;
        } elseif ($total_pot_w <= 100) { // Se o total de potências W for menor ou igual a 100 kW
            // Se o total de potências W estiver entre 20 kW e 100 kW, retorna 0.4
            return 0.4;
        } else {
            // Se o total de potências W exceder 100 kW, retorna 0.3
            return 0.3;
        }
    } elseif ($ramo_selecionado === 'residencias') {
        // Se o ramo for 'residências', verifica as especificações adicionais
        $total_pot_w = array_sum($pot_w); // Calcula o total de potências W

        // Se o total de potências W for menor ou igual a 10 kW, retorna 1
        if ($total_pot_w <= 10) {
            return 1;
        } elseif ($total_pot_w <= 120) { // Se o total de potências W for menor ou igual a 120 kW
            // Se o total de potências W estiver entre 10 kW e 120 kW, retorna 0.35
            return 0.35;
        } else {
            // Se o total de potências W exceder 120 kW, retorna 0.25
            return 0.25;
        }
    } else {
        return 0; // Retorna 0 para outros ramos
    }
}
?>
