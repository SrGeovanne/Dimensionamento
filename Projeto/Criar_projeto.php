<?php
// Inicia a sessão
session_start();

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Armazena o ramo selecionado na sessão
    $_SESSION['ramo_selecionado'] = $_POST['ramo'];

    // Redireciona para a página "Tabela do Projeto"
    header("Location: tabela.php");
    exit;
}

// Defina a variável ramo_selecionado com o valor selecionado ou vazio se não estiver definido
$ramo_selecionado = isset($_SESSION['ramo_selecionado']) ? $_SESSION['ramo_selecionado'] : '';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Criar Projeto</title>
    <link rel="stylesheet" href="gg.css">
</head>

<body>
    <h2>Criar Projeto</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="nome_projeto">Nome do Projeto:</label><br>
        <input type="text" id="nome_projeto" name="nome_projeto"><br>

        <label for="ramo">Ramo:</label><br>
        <select id="ramo" name="ramo">
            <option value="escola" <?php if ($ramo_selecionado == 'escola') echo 'selected'; ?>>Escola</option>
            <option value="hospitais" <?php if ($ramo_selecionado == 'hospitais') echo 'selected'; ?>>Hospitais</option>
            <option value="auditorio" <?php if ($ramo_selecionado == 'auditorio') echo 'selected'; ?>>Auditório</option>
            <option value="bancos" <?php if ($ramo_selecionado == 'bancos') echo 'selected'; ?>>Bancos</option>
            <option value="clubes" <?php if ($ramo_selecionado == 'clubes') echo 'selected'; ?>>Clubes</option>
            <option value="escritorios" <?php if ($ramo_selecionado == 'escritorios') echo 'selected'; ?>>Escritórios</option>
            <option value="barbearias" <?php if ($ramo_selecionado == 'barbearias') echo 'selected'; ?>>Barbearias</option>
            <option value="garagem" <?php if ($ramo_selecionado == 'garagem') echo 'selected'; ?>>Garagens Comerciais</option>
            <option value="hoteis" <?php if ($ramo_selecionado == 'hoteis') echo 'selected'; ?>>Hotéis</option>
            <option value="igreja" <?php if ($ramo_selecionado == 'igreja') echo 'selected'; ?>>Igreja</option>
            <option value="residencias" <?php if ($ramo_selecionado == 'residencias') echo 'selected'; ?>>Residências</option>
            <option value="restaurantes" <?php if ($ramo_selecionado == 'restaurantes') echo 'selected'; ?>>Restaurantes</option>
        </select><br><br>

        <input type="submit" value="Salvar Projeto">
    </form>
</body>

</html>
