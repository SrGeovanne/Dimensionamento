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

    // Verifica se o nome do banco de dados foi passado como parâmetro na URL
    if (isset($_GET['dbname'])) {
        $dbname = $_GET['dbname'];

        // Conecta ao banco de dados
        include 'conexao.php';
    } else {
        echo "Nome do banco de dados não fornecido!";
        exit();
    }

    // Obtém o valor do ramo selecionado
    $ramo_selecionado = isset($_SESSION['ramo_selecionado']) ? $_SESSION['ramo_selecionado'] : 'Nenhum ramo selecionado';
    ?>

    <h2>Tabela do Projeto - <?php echo htmlspecialchars($ramo_selecionado); ?></h2>

    <?php
    // Verifica se houve uma submissão de formulário POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Verifica se todos os campos necessários foram enviados
        $campos_obrigatorios = ['grupo', 'carga', 'Descricoes', 'qtd', 'pot_w', 'fp'];
        $campos_faltando = [];

        foreach ($campos_obrigatorios as $campo) {
            if (!isset($_POST[$campo]) || empty($_POST[$campo])) {
                $campos_faltando[] = $campo;
            }
        }

        if (!empty($campos_faltando)) {
            echo "Todos os campos são necessários! Campos faltando: " . implode(', ', $campos_faltando);
            echo "<br>";
        } else {
            echo "Dados atualizados com sucesso!<br>";
        }
    }
    ?>

    <!-- Verifica se não há campos faltando para exibir o formulário -->
    <?php if (empty($campos_faltando)) : ?>
        <form id="form-tabela" method="POST" action="salvar_dados.php?dbname=<?php echo urlencode($dbname); ?>">
            <input type="hidden" name="nome_projeto" value="<?php echo htmlspecialchars($_SESSION['nome_projeto']); ?>">
            <input type="hidden" name="ramo" value="<?php echo htmlspecialchars($ramo_selecionado); ?>"> <!-- Adiciona o ramo como um campo oculto -->
            <table id="tabela-projeto" border="1">
                <tr>
                    <th>Grupo</th>
                    <th>Carga</th>
                    <th>Descrições</th>
                    <th>QTD</th>
                    <th>Pot W</th>
                    <th>FP</th>
                    <th></th>
                </tr>
                <tr>
                    <td>
                        <input type="hidden" name="id[]" value="1">
                        <select name="grupo[]" onchange="atualizarOpcoes(this)">
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                            <option value="E">E</option>
                        </select>
                    </td>
                    <td>
                        <select name="carga[]" onchange="atualizarFpPorCarga(this)">
                            <option value="iluminacao com compensacao">Iluminação com compensação</option>
                            <option value="iluminacao sem compensacao">Iluminação sem compensação</option>
                            <option value="tomada">Tomada de Uso Geral</option>
                        </select>
                    </td>
                    <td><input type="text" name="Descricoes[]"></td>
                    <td><input type="text" name="qtd[]"></td>
                    <td><input type="number" name="pot_w[]"></td>
                    <td><input type="number" step="0.01" name="fp[]" class="fp-input"></td>
                    <td></td>
                </tr>
            </table>
            <br>
            <button type="button" onclick="adicionarLinha()">+</button>
            <button type="submit">Salvar</button>
            <button type="button" onclick="calcular('<?php echo htmlspecialchars($ramo_selecionado); ?>')">Calcular</button>
        </form>
    <?php endif; ?>

    <script>
        var linhaCount = 1;

        // Função para adicionar uma nova linha à tabela
        function adicionarLinha() {
            linhaCount++;
            var table = document.getElementById('tabela-projeto');
            var newRow = table.insertRow(-1);
            var cells = ["grupo", "carga", "Descricoes", "qtd", "pot_w", "fp"];

            for (var i = 0; i < cells.length; i++) {
                var cell = newRow.insertCell(i);
                var input;

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
                } else if (cells[i] === "carga") {
                    input = document.createElement("select");
                    input.name = cells[i] + "[]";
                    input.innerHTML = `
                        <option value="iluminacao com compensacao">Iluminação com compensação</option>
                        <option value="iluminacao sem compensacao">Iluminação sem compensação</option>
                        <option value="tomada">Tomada de Uso Geral</option>
                    `;
                    input.onchange = function() {
                        atualizarFpPorCarga(this);
                    };
                } else if (cells[i] === "fp") {
                    input = document.createElement("input");
                    input.type = "number";
                    input.name = cells[i] + "[]";
                    input.step = "0.01";
                    input.className = "fp-input";
                } else {
                    input = document.createElement("input");
                    input.type = "text";
                    input.name = cells[i] + "[]";
                }

                cell.appendChild(input);
            }

            // Adicionar campo oculto ID com valor de linhaCount
            var cellId = newRow.insertCell(-1);
            var idInput = document.createElement("input");
            idInput.type = "hidden";
            idInput.name = "id[]";
            idInput.value = linhaCount;
            cellId.appendChild(idInput);

            // Adicionar botão de remoção de linha, exceto para a primeira linha
            var cellRemoveButton = newRow.insertCell(-1);
            var removeButton = document.createElement("button");
            removeButton.textContent = "-";
            removeButton.type = "button";
            removeButton.onclick = function() {
                removerLinha(this);
            };
            cellRemoveButton.appendChild(removeButton);
        }

        // Função para remover uma linha da tabela
        function removerLinha(button) {
            var row = button.parentNode.parentNode;
            row.parentNode.removeChild(row);
            linhaCount--;
        }

        // Função para atualizar as opções do campo Tipo de Carga e FP com base no Grupo selecionado
        function atualizarOpcoes(selectElement) {
            var grupo = selectElement.value;
            var row = selectElement.parentNode.parentNode;
            var cargaSelect = row.querySelector('select[name="carga[]"]');
            var fpInput = row.querySelector('input[name="fp[]"]');

            if (grupo === "A") {
                cargaSelect.innerHTML = `
                    <option value="iluminacao com compensacao">Iluminação com compensação</option>
                    <option value="iluminacao sem compensacao">Iluminação sem compensação</option>
                    <option value="tomada">Tomada de Uso Geral</option>
                `;
                fpInput.value = "";
                fpInput.placeholder = "Digite o FP";
            } else if (grupo === "B") {
                cargaSelect.innerHTML = `
                    <option value="equipamentos">Equipamentos de Utilização Específica</option>
                    <option value="tomada_especifica">Tomada de Uso Específico</option>
                `;
                fpInput.value = "1";
            } else if (grupo === "C" || grupo === "D") {
                cargaSelect.innerHTML = `
                    <option value="condicionador">Condicionador de Ar</option>
                    <option value="motores">Motores Elétricos</option>
                    <option value="maquinas_solda">Máquinas de Solda</option>
                `;
                fpInput.value = "1";
            } else if (grupo === "E") {
                cargaSelect.innerHTML = `
                    <option value="equipamentos">Equipamentos Especiais</option>
                `;
                fpInput.value = "0.5";
            }
        }

        // Função para atualizar o FP com base na carga selecionada
        function atualizarFpPorCarga(selectElement) {
            var row = selectElement.parentNode.parentNode;
            var grupoSelect = row.querySelector('select[name="grupo[]"]');
            var fpInput = row.querySelector('input[name="fp[]"]');

            if (grupoSelect.value === "A") {
                if (selectElement.value === "iluminacao sem compensacao") {
                    fpInput.value = "0.5";
                } else if (selectElement.value === "iluminacao com compensacao") {
                    fpInput.value = "0.92";
                } else if (selectElement.value === "tomada") {
                    fpInput.value = "1";
                }
            }
        }

        // Função para calcular
        function calcular(ramoSelecionado) {
            // Se não houver pelo menos uma linha, não faz nada
            if (linhaCount < 1) {
                alert("Adicione pelo menos uma linha à tabela.");
                return;
            }

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

            // Cria uma URL com os dados da tabela e o nome do ramo selecionado
            var url = "saida.php?ramo=" + encodeURIComponent(ramoSelecionado) + "&" + new URLSearchParams(formData).toString();

            // Redireciona para a página de saída com os dados da tabela e o nome do ramo selecionado
            window.location.href = url;
        }
    </script>
</body>

</html>
