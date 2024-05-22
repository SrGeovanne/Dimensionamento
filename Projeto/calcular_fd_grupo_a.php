<?php
function calcularFatorDemandaGrupoA($ramo_selecionado, $pot_w)
{
    $ramos_com_fator_1 = ['auditorio', 'bancos', 'barbearias', 'clubes', 'igreja', 'garagem', 'restaurantes'];

    if (in_array($ramo_selecionado, $ramos_com_fator_1)) {
        return array_fill(0, count($pot_w), 1);
    }

    $fator_demanda = [];
    $potencia_acumulada = 0;

    foreach ($pot_w as $potencia) {
        $potencia_acumulada += $potencia;

        if ($ramo_selecionado === 'escritorios') {
            if ($potencia_acumulada <= 20000) {
                $fator_demanda[] = 1;
            } else {
                $fator_demanda[] = 0.7;
            }
        } elseif ($ramo_selecionado === 'escola') {
            if ($potencia_acumulada <= 12000) {
                $fator_demanda[] = 1;
            } else {
                $fator_demanda[] = 0.5;
            }
        } elseif ($ramo_selecionado === 'hospitais') {
            if ($potencia_acumulada <= 50000) {
                $fator_demanda[] = 0.4;
            } else {
                $fator_demanda[] = 0.2;
            }
        } elseif ($ramo_selecionado === 'hoteis') {
            if ($potencia_acumulada <= 20000) {
                $fator_demanda[] = 0.5;
            } elseif ($potencia_acumulada <= 100000) {
                $fator_demanda[] = 0.4;
            } else {
                $fator_demanda[] = 0.3;
            }
        } elseif ($ramo_selecionado === 'residencias') {
            if ($potencia_acumulada <= 10000) {
                $fator_demanda[] = 1;
            } elseif ($potencia_acumulada <= 120000) {
                $fator_demanda[] = 0.35;
            } else {
                $fator_demanda[] = 0.25;
            }
        } else {
            $fator_demanda[] = 0;
        }
    }

    return $fator_demanda;
}
?>
