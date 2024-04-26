<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Criar Projeto</title>
</head>
<body>
    <h2>Criar Projeto</h2>
    <form action="salvar_projeto.php" method="post">
        <label for="nome_projeto">Nome do Projeto:</label><br>
        <input type="text" id="nome_projeto" name="nome_projeto"><br>
        
        <label for="ramo">Ramo:</label><br>
        <select id="ramo" name="ramo">
            <option value="escola">Escola</option>
            <option value="hospitais">Hospitais</option>
        </select><br><br>
        
        <input type="submit" value="Salvar Projeto">
    </form>
</body>
</html>
