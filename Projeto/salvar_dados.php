<?php
// Inclui o arquivo de conexão
include 'conexao.php';

// Verifica se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se os dados necessários foram recebidos
    if (
        isset($_POST['grupo']) && isset($_POST['tipo_carga']) && isset($_POST['descricao']) &&
        isset($_POST['qtd']) && isset($_POST['pot_w']) && isset($_POST['fp']) &&
        isset($_POST['fp_valor']) && isset($_POST['nome_projeto'])
    ) {
        // Obtém o nome do banco de dados fornecido pelo usuário
        $dbname = $_POST['nome_projeto'];

        // Conecta ao banco de dados usando o arquivo de conexão
        $conn = conectar($dbname);

        // Verifica a conexão
        if ($conn) {
            // Prepara a instrução SQL para inserir os dados na tabela
            $sql = "INSERT INTO projetos (grupo, tipo_carga, descricao, qtd, pot_w, fp) VALUES (?, ?, ?, ?, ?, ?)";

            // Prepara a declaração
            $stmt = $conn->prepare($sql);

            // Verifica se a preparação da declaração foi bem-sucedida
            if ($stmt) {
                // Associa os parâmetros
                $stmt->bind_param("ssssss", $grupo, $tipo_carga, $descricao, $qtd, $pot_w, $fp);

                // Itera sobre os dados recebidos do formulário
                foreach ($_POST['grupo'] as $key => $grupo) {
                    $tipo_carga = $_POST['tipo_carga'][$key];
                    $descricao = $_POST['descricao'][$key];
                    $qtd = $_POST['qtd'][$key];
                    $pot_w = $_POST['pot_w'][$key];
                    $fp = $_POST['fp'][$key];

                    // Executa a declaração
                    $stmt->execute();
                }

                echo "Dados inseridos com sucesso!";
            } else {
                echo "Erro ao preparar a declaração: " . $conn->error;
            }

            // Fecha a declaração
            $stmt->close();

            // Fecha a conexão
            $conn->close();
        } else {
            echo "Erro ao conectar ao banco de dados!";
        }
    } else {
        echo "Todos os campos são necessários!";
    }
} else {
    echo "Método inválido!";
}
?>
