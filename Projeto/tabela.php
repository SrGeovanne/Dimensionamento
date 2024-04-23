<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Tabela do Projeto</title>
</head>
<body>
    <h2>Tabela do Projeto</h2>
    <table id="tabela-projeto" border="1">
        <tr>
            <th>Grupo</th>
            <th>Tipo de Carga</th>
            <th>Descrições</th>
            <th>QTD</th>
            <th>Pot W</th>
            <th>FP</th>
        </tr>
        <tr>
            <!-- Campos para inserir dados -->
            <td><input type="text" name="grupo[]"></td>
            <td><input type="text" name="tipo_carga[]"></td>
            <td><input type="text" name="descricao[]"></td>
            <td><input type="text" name="qtd[]"></td>
            <td><input type="text" name="pot_w[]"></td>
            <td><input type="text" name="fp[]"></td>
        </tr>
    </table>
    <br>
    <button onclick="adicionarLinha()">+</button>
    <button onclick="salvarDados()">Salvar e Calcular</button>
    
    <script>
        function adicionarLinha() {
            // Adiciona uma nova linha na tabela
            var table = document.getElementById('tabela-projeto');
            var newRow = table.insertRow(-1);
            var cells = ["grupo", "tipo_carga", "descricao", "qtd", "pot_w", "fp"];

            for (var i = 0; i < cells.length; i++) {
                var cell = newRow.insertCell(i);
                var input = document.createElement("input");
                input.type = "text";
                input.name = cells[i] + "[]";
                cell.appendChild(input);
            }
        }

        function salvarDados() {
            var formData = new FormData();
            var inputs = document.querySelectorAll('#tabela-projeto input');

            inputs.forEach(function(input) {
                formData.append(input.name, input.value);
            });

            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        alert("Dados salvos e calculados!");
                    } else {
                        alert("Erro ao salvar os dados!");
                    }
                }
            };

            xhr.open("POST", "salvar_dados.php", true);
            xhr.send(formData);
        }
    </script>
</body>
</html>
