<?php
// Inclui o arquivo de conexão
include 'conexao.php';

// Verifica se o campo "nome_projeto" foi enviado e não está vazio
if (!isset($_POST['nome_projeto']) || empty($_POST['nome_projeto'])) {
    echo "Campo 'nome_projeto' não foi enviado ou está vazio.";
    exit;
}

// Lista de campos obrigatórios
$campos_obrigatorios = ['grupo', 'carga', 'Descricoes', 'qtd', 'pot_w', 'fp', 'id']; // Adicionado 'id' como campo obrigatório

// Verifica se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Verifica se os dados necessários foram recebidos
    $campos_faltando = [];
    foreach ($campos_obrigatorios as $campo) {
        if (!isset($_POST[$campo]) || empty($_POST[$campo])) {
            $campos_faltando[] = $campo;
        }
    }

    if (!empty($campos_faltando)) {
        echo "Campos faltando: " . implode(', ', $campos_faltando);
        exit;
    }

    // Obtém o nome do banco de dados fornecido pelo usuário
    $dbname = $_POST['nome_projeto'];

    // Conecta ao banco de dados usando o arquivo de conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica a conexão
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    echo "Conexão bem-sucedida<br>"; // Movido para depois da verificação dos campos obrigatórios

    // Prepara a instrução SQL para atualizar os dados na tabela
    $sql = "UPDATE projeto SET grupo = ?, carga = ?, Descricoes = ?, qtd = ?, pot_w = ?, fp = ? WHERE id = ?"; // Alterado de 'descrições' para 'Descricoes'

    // Prepara a declaração
    $stmt = $conn->prepare($sql);

    // Verifica se a preparação da declaração foi bem-sucedida
    if ($stmt) {
        // Associa os parâmetros
        $stmt->bind_param("ssssssi", $grupo, $carga, $descricao, $qtd, $pot_w, $fp, $id);

        // Itera sobre os dados recebidos do formulário
        foreach ($_POST['grupo'] as $key => $value) {
            $grupo = $_POST['grupo'][$key];
            $carga = $_POST['carga'][$key];
            $descricao = $_POST['Descricoes'][$key]; // Alterado de 'descricoes' para 'Descricoes'
            $qtd = $_POST['qtd'][$key];
            $pot_w = $_POST['pot_w'][$key];
            $fp = $_POST['fp'][$key];
            $id = $_POST['id'][$key]; // Corrigido para obter o ID do registro

            // Executa a declaração
            $stmt->execute();
        }

        echo "Dados atualizados com sucesso!";
    } else {
        echo "Erro ao preparar a declaração: " . $conn->error;
    }

    // Fecha a declaração
    $stmt->close();

    // Fecha a conexão
    $conn->close();
} else {
    echo "Método inválido!";
}
?>
