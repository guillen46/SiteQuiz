<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo "Usuário não autenticado.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_SESSION['usuario_id'];
    $pergunta_id = $_POST['pergunta_id'] ?? null;
    $resposta = $_POST['resposta'] ?? null;

    if ($pergunta_id && $resposta) {
        $query = "INSERT INTO respostas (usuario_id, pergunta_id, resposta) VALUES (?, ?, ?)";
        $stmt = $conexao->prepare($query);
        $stmt->bind_param("iis", $usuario_id, $pergunta_id, $resposta);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Resposta salva com sucesso.";
        } else {
            http_response_code(500);
            echo "Erro ao salvar resposta.";
        }
    } else {
        http_response_code(400);
        echo "Dados incompletos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Responder Questão</title>
</head>
<body>
    <h1>Responda a Pergunta</h1>
    <form action="salvar_resposta.php" method="POST">
        <!-- Campo oculto para enviar o ID da pergunta -->
        <input type="hidden" name="pergunta_id" value="1">
        
        <p>Escolha sua resposta:</p>
        <input type="radio" name="resposta" value="A" id="A">
        <label for="A">Alternativa A</label><br>
        
        <input type="radio" name="resposta" value="B" id="B">
        <label for="B">Alternativa B</label><br>
        
        <input type="radio" name="resposta" value="C" id="C">
        <label for="C">Alternativa C</label><br>
        
        <input type="radio" name="resposta" value="D" id="D">
        <label for="D">Alternativa D</label><br>

        <button type="submit">Enviar Resposta</button>
        <script>
    const form = document.querySelector('form');
    form.addEventListener('submit', function(event) {
        const pergunta_id = document.querySelector('input[name="pergunta_id"]').value;
        const resposta = document.querySelector('input[name="resposta"]:checked');
        
        if (!resposta) {
            alert('Por favor, selecione uma resposta!');
            event.preventDefault(); // Impede o envio se não houver resposta selecionada
        } else {
            console.log('Pergunta ID:', pergunta_id);
            console.log('Resposta:', resposta.value);
        }
    });
</script>

    </form>
</body>
</html>

