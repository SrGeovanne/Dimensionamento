<?php
function calcularFatorDemandaGrupoC($num_aparelhos)
{
    if ($num_aparelhos == 2) {
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
?>
