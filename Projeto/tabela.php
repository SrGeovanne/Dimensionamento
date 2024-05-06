<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Tabela do Projeto</title>
    <link rel="stylesheet" href="gg.css">
</head>

<body>
    <?php
    // Inicia a sessão
    session_start();

    // Obtém o valor do ramo selecionado
    $ramo_selecionado = isset($_SESSION['ramo_selecionado']) ? $_SESSION['ramo_selecionado'] : 'Nenhum ramo selecionado';
    ?>

    <h2>Tabela do Projeto - <?php echo $ramo_selecionado; ?></h2>

    <table id="tabela-projeto" border="1">
        <tr>
            <th>Grupo</th>
            <th>Tipo de Carga</th>
            <th>Descrições</th>
            <th>QTD</th>
            <th>Pot W</th>
            <th>FP</th>
            <th>Ações</th>
        </tr>
        <tr>
            <!-- Campos para inserir dados -->
            <td>
                <select name="grupo[]" onchange="atualizarOpcoes(this)">
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                    <option value="E">E</option>
                </select>
            </td>
            <td>
                <select name="tipo_carga[]">
                    <option value="iluminacao com compensacao">Iluminação com compensação</option>
                    <option value="iluminacao sem compensacao">Iluminação sem compensação</option>
                    <option value="tomada">Tomada de Uso Geral</option>
                </select>
            </td>
            <td><input type="text" name="descricao[]"></td>
            <td><input type="text" name="qtd[]"></td>
            <td><input type="number" name="pot_w[]"></td>
            <td>
                <select name="fp[]">
                    <option value="0.92">0.92</option>
                    <option value="0.50">0.50</option>
                    <option value="1">1</option>
                    <option value="digite" style="display:none;">Digite</option>
                </select>
                <input type="text" name="fp_valor[]" style="display:none;">
            </td>
            <td><button onclick="removerLinha(this)">-</button></td>
        </tr>
    </table>
    <br>
    <button onclick="adicionarLinha()">+</button>
    <button onclick="salvarDados()">Salvar</button>
    <button onclick="calcular()">Calcular</button>

    <script>
        function atualizarOpcoes(selectElement) {
            var grupo = selectElement.value;
            var row = selectElement.parentNode.parentNode;
            var cargaSelect = row.querySelector('select[name="tipo_carga[]"]');
            var fpSelect = row.querySelector('select[name="fp[]"]');
            var fpInput = row.querySelector('input[name="fp_valor[]"]');

            if (grupo === "A") {
                cargaSelect.innerHTML = `
                    <option value="iluminacao com compensacao">Iluminação com compensação</option>
                    <option value="iluminacao sem compensacao">Iluminação sem compensação</option>
                    <option value="tomada">Tomada de Uso Geral</option>
                `;
                fpSelect.innerHTML = `
                    <option value="0.92">0.92</option>
                    <option value="0.50">0.50</option>
                    <option value="1">1</option>
                    <option value="digite" style="display:none;">Digite</option>
                `;
                fpInput.style.display = "none";
                fpInput.value = "";
            } else if (grupo === "B") {
                cargaSelect.innerHTML = `
                    <option value="equipamentos">Equipamentos de Utilização Específica</option>
                    <option value="tomada_especifica">Tomada de Uso Específico</option>
                `;
                fpSelect.innerHTML = `
                    <option value="1">1</option>
                    <option value="digite" style="display:none;">Digite</option>
                `;
                fpInput.style.display = "none";
                fpInput.value = "";
            } else if (grupo === "C") {
                cargaSelect.innerHTML = `
                    <option value="condicionador">Condicionador de Ar</option>
                `;
                fpSelect.innerHTML = `
                    <option value="digite">Digite</option>
                `;
                fpInput.style.display = "inline-block";
                fpInput.value = "";
            } else if (grupo === "D") {
                cargaSelect.innerHTML = `
                    <option value="motores">Motores Elétricos</option>
                    <option value="maquinas_solda">Máquinas de Solda</option>
                `;
                fpSelect.innerHTML = `
                    <option value="digite">Digite</option>
                `;
                fpInput.style.display = "inline-block";
                fpInput.value = "";
            } else if (grupo === "E") {
                cargaSelect.innerHTML = `
                    <option value="equipamentos">Equipamentos Especiais</option>
                `;
                fpSelect.innerHTML = `
                    <option value="0.5">0.5</option>
                    <option value="digite" style="display:none;">Digite</option>
                `;
                fpInput.style.display = "none";
                fpInput.value = "";
            }
        }

        function adicionarLinha() {
            var table = document.getElementById('tabela-projeto');
            var newRow = table.insertRow(-1);
            var cells = ["grupo", "tipo_carga", "descricao", "qtd", "pot_w", "fp"];

            for (var i = 0; i < cells.length; i++) {
                var cell = newRow.insertCell(i);
                var input = document.createElement("input");

                if (cells[i] === "grupo") {
                    input = document.createElement("select");
                    input.name = cells[i] + "[]";
                    input.innerHTML = `
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                        <option value="E">E</option>
                    `;
                    input.onchange = function() {
                        atualizarOpcoes(this);
                    };
                } else if (cells[i] === "tipo_carga") {
                    input = document.createElement("select");
                    input.name = cells[i] + "[]";
                    input.innerHTML = `
                        <option value="iluminacao com compensacao">Iluminação com compensação</option>
                        <option value="iluminacao sem compensacao">Iluminação sem compensação</option>
                        <option value="tomada">Tomada de Uso Geral</option>
                        <option value="equipamentos">Equipamentos de Utilização Específica</option>
                        <option value="tomada_especifica">Tomada de Uso Específico</option>
                        <option value="condicionador">Condicionador de Ar</option>
                        <option value="motores">Motores Elétricos</option>
                        <option value="maquinas_solda">Máquinas de Solda</option>
                        <option value="equipamentos">Equipamentos Especiais</option>
                    `;
                } else if (cells[i] === "fp") {
                    input = document.createElement("select");
                    input.name = cells[i] + "[]";
                    input.innerHTML = `
                        <option value="0.92">0.92</option>
                        <option value="0.50">0.50</option>
                        <option value="1">1</option>
                        <option value="digite" style="display:none;">Digite</option>
                    `;
                    input.onchange = function() {
                        verificarFP(this);
                    };
                } else if (cells[i] === "fp_valor") {
                    input = document.createElement("input");
                    input.type = "text";
                    input.name = cells[i] + "[]";
                    input.style.display = "none";
                } else {
                    input.type = "text";
                    input.name = cells[i] + "[]";
                }

                cell.appendChild(input);
            }

            // Adicionar botão de remoção de linha
            var cellRemoveButton = newRow.insertCell(cells.length);
            var removeButton = document.createElement("button");
            removeButton.textContent = "-";
            removeButton.onclick = function() {
                removerLinha(this);
            };
            cellRemoveButton.appendChild(removeButton);
        }

        function verificarFP(selectElement) {
            var row = selectElement.parentNode.parentNode;
            var fpInput = row.querySelector('input[name="fp_valor[]"]');

            if (selectElement.value === "digite") {
                fpInput.style.display = "inline-block";
                fpInput.value = "";
            } else {
                fpInput.style.display = "none";
                fpInput.value = selectElement.value;
            }
        }

        function removerLinha(button) {
            var row = button.parentNode.parentNode;
            row.parentNode.removeChild(row);
        }

        function salvarDados() {
            var formData = new FormData();
            var tableRows = document.querySelectorAll('#tabela-projeto tr');

            // Itera sobre as linhas da tabela, exceto a primeira que contém os cabeçalhos
            for (var i = 1; i < tableRows.length; i++) {
                var row = tableRows[i];
                var rowData = {};

                // Obtém os elementos de input e select da linha atual
                var inputs = row.querySelectorAll('input, select');

                // Itera sobre os elementos para obter seus nomes e valores
                inputs.forEach(function(input) {
                    rowData[input.name] = input.value;
                });

                // Adiciona os dados da linha ao formData
                for (var key in rowData) {
                    formData.append(key, rowData[key]);
                }
            }

            // Envia os dados para o arquivo PHP responsável por salvar no banco de dados
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        alert("Dados salvos!");
                    } else {
                        alert("Erro ao salvar os dados!");
                    }
                }
            };

            xhr.open("POST", "salvar_dados.php", true);
            xhr.send(formData);
        }

        function calcular() {
            var formData = new FormData();
            var tableRows = document.querySelectorAll('#tabela-projeto tr');

            // Itera sobre as linhas da tabela, exceto a primeira que contém os cabeçalhos
            for (var i = 1; i < tableRows.length; i++) {
                var row = tableRows[i];
                var rowData = {};

                // Obtém os elementos de input e select da linha atual
                var inputs = row.querySelectorAll('input, select');

                // Itera sobre os elementos para obter seus nomes e valores
                inputs.forEach(function(input) {
                    rowData[input.name] = input.value;
                });

                // Adiciona os dados da linha ao formData
                for (var key in rowData) {
                    formData.append(key, rowData[key]);
                }
            }

            // Obtém o nome do ramo selecionado
            var ramoSelecionado = "<?php echo $ramo_selecionado; ?>";

            // Cria uma URL com os dados da tabela e o nome do ramo selecionado
            var url = "saida.php?ramo=" + encodeURIComponent(ramoSelecionado) + "&" + new URLSearchParams(formData).toString();

            // Redireciona para a página de saída com os dados da tabela e o nome do ramo selecionado
            window.location.href = url;
        }
    </script>
</body>

</html>
