<?php
function calcularFatorDemandaGrupoE($potencia)
{
    $fator_demanda = [];
    $maior_potencia = max($potencia);
    foreach ($potencia as $p) {
        $fator_demanda[] = ($p == $maior_potencia) ? 1 : 0.6;
    }
    return $fator_demanda;
}
?>
