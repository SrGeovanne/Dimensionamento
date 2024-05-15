<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Recomendação de Transformador</title>
    <link rel="stylesheet" href="gg.css">
</head>

<body>
    <?php
    // Verifica se a Potência Total com FD do total geral está definida e dentro das faixas especificadas
    if (isset($_GET['potencia_total_fd']) && is_numeric($_GET['potencia_total_fd'])) {
        $potencia_total_fd = intval($_GET['potencia_total_fd']);

        // Determina o transformador recomendado com base na potência total com FD
        if ($potencia_total_fd >= 60 && $potencia_total_fd <= 82) {
            $transformador_recomendado = "75 kVA";
        } elseif ($potencia_total_fd >= 83 && $potencia_total_fd <= 124) {
            $transformador_recomendado = "112,5 kVA";
        } elseif ($potencia_total_fd >= 125 && $potencia_total_fd <= 165) {
            $transformador_recomendado = "150 kVA";
        } elseif ($potencia_total_fd >= 166 && $potencia_total_fd <= 248) {
            $transformador_recomendado = "225 kVA";
        } elseif ($potencia_total_fd >= 249 && $potencia_total_fd <= 330) {
            $transformador_recomendado = "300 kVA";
        } elseif ($potencia_total_fd >= 331 && $potencia_total_fd <= 550) {
            $transformador_recomendado = "500 kVA";
        } elseif ($potencia_total_fd >= 551 && $potencia_total_fd <= 825) {
            $transformador_recomendado = "750 kVA";
        } elseif ($potencia_total_fd >= 826 && $potencia_total_fd <= 1100) {
            $transformador_recomendado = "1000 kVA";
        } elseif ($potencia_total_fd >= 1101 && $potencia_total_fd <= 1375) {
            $transformador_recomendado = "1250 kVA";
        } elseif ($potencia_total_fd >= 1376 && $potencia_total_fd <= 1650) {
            $transformador_recomendado = "1500 kVA";
        } elseif ($potencia_total_fd >= 1651 && $potencia_total_fd <= 2200) {
            $transformador_recomendado = "2000 kVA";
        } elseif ($potencia_total_fd >= 2201 && $potencia_total_fd <= 2717) {
            $transformador_recomendado = "2500 kVA";
        } else {
            $transformador_recomendado = "não há transformador recomendável para essa potência";
        }

        echo "<h2>Transformador Recomendado: $transformador_recomendado</h2>";
    } else {
        echo "<h2>Nenhum Transformador Recomendavel diponivel.</h2>";
    }
    ?>
</body>

</html>
