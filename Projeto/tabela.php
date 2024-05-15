<?php
// Verifica se o nome do banco de dados foi passado como parâmetro na URL
if (isset($_GET['dbname'])) {
    $dbname = $_GET['dbname'];
    
    // Conecta ao banco de dados
    include 'conexao.php';
} else {
    echo "Nome do banco de dados não fornecido!";
    exit();
}
?>

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

    <?php
    // Verifica se houve uma submissão de formulário POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Verifica se todos os campos necessários foram enviados
        $campos_obrigatorios = ['grupo', 'carga', 'Descricoes', 'qtd', 'pot_w', 'fp']; // Alterado de 'descricoes' para 'Descricoes'
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
            echo "Dados atualizados com sucesso!<br>"; // Alterado para indicar que os dados foram atualizados com sucesso
        }
    }
    ?>

    <!-- Verifica se não há campos faltando para exibir o formulário -->
    <?php if (empty($campos_faltando)): ?>
    <form id="form-tabela" method="POST" action="salvar_dados.php?dbname=<?php echo urlencode($dbname); ?>">
        <input type="hidden" name="nome_projeto" value="<?php echo $_SESSION['nome_projeto']; ?>"> <!-- Adiciona o nome do projeto como um campo oculto -->
        <table id="tabela-projeto" border="1">
            <tr>
                <th>Grupo</th>
                <th>Carga</th>
                <th>Descrições</th> <!-- Alterado de 'descrições' para 'Descrições' -->
                <th>QTD</th>
                <th>Pot W</th>
                <th>FP</th>
                <th>Ações</th>
            </tr>
            <tr>
                <!-- Campos para inserir dados -->
                <td>
                    <input type="hidden" name="id[]" value="1"> <!-- Mantém o ID como 1 para a primeira linha -->
                    <select name="grupo[]">
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                        <option value="E">E</option>
                    </select>
                </td>
                <td>
                    <select name="carga[]">
                        <option value="iluminacao com compensacao">Iluminação com compensação</option>
                        <option value="iluminacao sem compensacao">Iluminação sem compensação</option>
                        <option value="tomada">Tomada de Uso Geral</option>
                    </select>
                </td>
                <td><input type="text" name="Descricoes[]"></td> <!-- Alterado de 'descrições' para 'Descricoes' -->
                <td><input type="text" name="qtd[]"></td>
                <td><input type="number" name="pot_w[]"></td>
                <td>
                    <select name="fp[]">
                        <option value="0.92">0.92</option>
                        <option value="0.50">0.50</option>
                        <option value="1">1</option>
                        <option value="digite">Digite</option>
                    </select>
                    <!-- Campo para valor personalizado do FP -->
                    <input type="number" name="fp_valor[]" style="display: none;">
                </td>
                <td></td> <!-- Célula vazia para manter a estrutura da tabela -->
            </tr>
        </table>
        <br>
        <button type="button" onclick="adicionarLinha()">+</button>
        <button type="submit">Salvar</button>
        <button type="button" onclick="calcular('<?php echo $ramo_selecionado; ?>')">Calcular</button>
    </form>
    <?php endif; ?>

    <script>
        var linhaCount = 1; // Inicia o contador de linhas com 1

        // Função para adicionar uma nova linha à tabela
        function adicionarLinha() {
            linhaCount++; // Incrementa o contador de linhas
            var table = document.getElementById('tabela-projeto');
            var newRow = table.insertRow(-1);
            var cells = ["grupo", "carga", "Descricoes", "qtd", "pot_w", "fp"];

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
                } else if (cells[i] === "carga") {
                    input = document.createElement("select");
                    input.name = cells[i] + "[]";
                    input.innerHTML = `
                        <option value="iluminacao com compensacao">Iluminação com compensação</option>
                        <option value="iluminacao sem compensacao">Iluminação sem compensação</option>
                        <option value="tomada">Tomada de Uso Geral</option>
                    `;
                } else if (cells[i] === "fp") {
                    input = document.createElement("select");
                    input.name = cells[i] + "[]";
                    input.innerHTML = `
                        <option value="0.92">0.92</option>
                        <option value="0.50">0.50</option>
                        <option value="1">1</option>
                        <option value="digite">Digite</option>
                    `;
                    input.onchange = function() {
                        verificarFP(this);
                    };
                } else {
                    input.type = "text";
                    input.name = cells[i] + "[]";
                }

                cell.appendChild(input);
            }

            // Adicionar botão de remoção de linha, exceto para a primeira linha
            var cellRemoveButton = newRow.insertCell(cells.length);
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
            linhaCount--; // Decrementa o contador de linhas
        }

        // Função para atualizar as opções do campo Tipo de Carga com base no Grupo selecionado
        function atualizarOpcoes(selectElement) {
            var grupo = selectElement.value;
            var row = selectElement.parentNode.parentNode;
            var cargaSelect = row.querySelector('select[name="carga[]"]');
            var fpSelect = row.querySelector('select[name="fp[]"]');

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
                    <option value="digite">Digite</option>
                `;
            } else if (grupo === "B") {
                cargaSelect.innerHTML = `
                    <option value="equipamentos">Equipamentos de Utilização Específica</option>
                    <option value="tomada_especifica">Tomada de Uso Específico</option>
                `;
                fpSelect.innerHTML = `
                    <option value="1">1</option>
                    <option value="digite">Digite</option>
                `;
            } else if (grupo === "C") {
                cargaSelect.innerHTML = `
                    <option value="condicionador">Condicionador de Ar</option>
                `;
                fpSelect.innerHTML = `
                    <option value="digite">Digite</option>
                `;
            } else if (grupo === "D") {
                cargaSelect.innerHTML = `
                    <option value="motores">Motores Elétricos</option>
                    <option value="maquinas_solda">Máquinas de Solda</option>
                `;
                fpSelect.innerHTML = `
                    <option value="digite">Digite</option>
                `;
            } else if (grupo === "E") {
                cargaSelect.innerHTML = `
                    <option value="equipamentos">Equipamentos Especiais</option>
                `;
                fpSelect.innerHTML = `
                    <option value="0.5">0.5</option>
                    <option value="digite">Digite</option>
                `;
            }
        }

        // Função para verificar o valor do fator de potência
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
