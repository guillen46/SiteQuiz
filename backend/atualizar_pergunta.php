<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário está logado e é admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'admin') {
    header('Location: login.php');
    exit;
}

// Verifica se os dados do formulário foram enviados
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int)$_POST['id'];
    $materia = $_POST['materia'];
    $assunto = $_POST['assunto'];
    $enunciado = $_POST['enunciado'];
    $alternativa_a = $_POST['alternativa_a'];
    $alternativa_b = $_POST['alternativa_b'];
    $alternativa_c = $_POST['alternativa_c'];
    $alternativa_d = $_POST['alternativa_d'];
    $alternativa_e = $_POST['alternativa_e'];
    $resposta_correta = $_POST['resposta_correta'];
    $solucao = $_POST['solucao'];

    // Ajuste: a string de tipos deve corresponder ao número de variáveis
    // O bind_param agora aceita 11 parâmetros (10 strings + 1 inteiro)
    $query = "UPDATE perguntas SET 
                materia = ?, 
                assunto = ?, 
                enunciado = ?, 
                alternativa_a = ?, 
                alternativa_b = ?, 
                alternativa_c = ?, 
                alternativa_d = ?, 
                alternativa_e = ?, 
                resposta_correta = ?, 
                solucao = ? 
              WHERE id = ?";
    $stmt = $conexao->prepare($query);

    // Verifica se a preparação do statement foi bem-sucedida
    if ($stmt === false) {
        die('Erro na preparação do statement: ' . $conexao->error);
    }

    // Binding dos parâmetros (10 strings + 1 inteiro)
    $stmt->bind_param("ssssssssssi", 
        $materia, 
        $assunto, 
        $enunciado, 
        $alternativa_a, 
        $alternativa_b, 
        $alternativa_c, 
        $alternativa_d, 
        $alternativa_e, 
        $resposta_correta, 
        $solucao, 
        $id
    );

    // Executa a query
    if ($stmt->execute()) {
        // Redireciona para a página de controle de perguntas, passando o parâmetro 'atualizada=true'
        header('Location: controle_perguntas_admin.php?atualizada=true');
        exit;
    } else {
        echo "Erro ao atualizar a pergunta: " . $stmt->error;
    }
}
?>
