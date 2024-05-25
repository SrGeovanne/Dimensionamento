<?php
// Inclui o arquivo de conexão
include 'conexao.php';
session_start();

// Verifica se o campo "nome_projeto" foi enviado e não está vazio
if (!isset($_POST['nome_projeto']) || empty($_POST['nome_projeto'])) {
    echo "Campo 'nome_projeto' não foi enviado ou está vazio.";
    exit;
}

// Verifica se o campo "ramo" foi enviado e não está vazio
if (!isset($_POST['ramo']) || empty($_POST['ramo'])) {
    echo "Campo 'ramo' não foi enviado ou está vazio.";
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

    // Define o valor do ramo
    $ramo = $_POST['ramo'];

    // Itera sobre os dados recebidos do formulário
    foreach ($_POST['grupo'] as $key => $value) {
        $id = $_POST['id'][$key]; // Obtém o ID do registro
        $grupo = $_POST['grupo'][$key];
        $carga = $_POST['carga'][$key];
        $descricao = $_POST['Descricoes'][$key]; // Alterado de 'descricoes' para 'Descricoes'
        $qtd = $_POST['qtd'][$key];
        $pot_w = $_POST['pot_w'][$key];
        $fp = $_POST['fp'][$key];

        // Verifica se o registro já existe
        $sql_check = "SELECT COUNT(*) FROM projeto WHERE id = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("i", $id);
        $stmt_check->execute();
        $stmt_check->bind_result($count);
        $stmt_check->fetch();
        $stmt_check->close();

        if ($count > 0) {
            // Prepara a instrução SQL para atualizar os dados na tabela
            $sql = "UPDATE projeto SET ramo = ?, grupo = ?, carga = ?, Descricoes = ?, qtd = ?, pot_w = ?, fp = ? WHERE id = ?";
        } else {
            // Prepara a instrução SQL para inserir novos dados na tabela
            $sql = "INSERT INTO projeto (ramo, id, grupo, carga, Descricoes, qtd, pot_w, fp) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        }

        // Prepara a declaração
        $stmt = $conn->prepare($sql);

        // Verifica se a preparação da declaração foi bem-sucedida
        if ($stmt) {
            // Associa os parâmetros
            if ($count > 0) {
                $stmt->bind_param("sssssssi", $ramo, $grupo, $carga, $descricao, $qtd, $pot_w, $fp, $id);
            } else {
                $stmt->bind_param("sisssssi", $ramo, $id, $grupo, $carga, $descricao, $qtd, $pot_w, $fp);
            }

            // Executa a declaração
            $stmt->execute();

            // Fecha a declaração
            $stmt->close();
        } else {
            echo "Erro ao preparar a declaração: " . $conn->error;
        }
    }

    $_SESSION['mensagem_sucesso'] = "Dados atualizados com sucesso!";

    // Fecha a conexão
    $conn->close();

    // Redireciona de volta para tabela.php
    header("Location: tabela.php?dbname=" . urlencode($dbname));
    exit;
} else {
    echo "Método inválido!";
}
?>
