<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.html');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$query = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conexao->prepare($query);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if (!$usuario) {
    echo "Usuário não encontrado!";
    exit;
}

// Consulta para obter as perguntas respondidas pelo usuário
$query_respostas = "
    SELECT p.enunciado, p.resposta_correta, r.resposta
    FROM respostas r
    JOIN perguntas p ON r.pergunta_id = p.id
    WHERE r.usuario_id = ?
";
$stmt_respostas = $conexao->prepare($query_respostas);
$stmt_respostas->bind_param("i", $usuario_id);
$stmt_respostas->execute();
$respostas_result = $stmt_respostas->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Perfil - QAPnaProva</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Perfil de <?= htmlspecialchars($usuario['nome']) ?></h1>
        <p><strong>Email:</strong> <?= htmlspecialchars($usuario['email']) ?></p>
        <p><strong>Tipo de Usuário:</strong> <?= htmlspecialchars($usuario['tipo']) ?></p>
        
        <h2>Perguntas Respondidas</h2>
        <ul>
            <?php while ($resposta = $respostas_result->fetch_assoc()) { ?>
                <li>
    <strong><?= htmlspecialchars($resposta['enunciado']) ?></strong><br>
    Sua Resposta: <?= htmlspecialchars($resposta['resposta']) ?><br>
    Resposta Correta: <?= htmlspecialchars($resposta['resposta_correta']) ?><br>
    <span style="color: <?= $resposta['resposta'] === $resposta['resposta_correta'] ? 'green' : 'red' ?>;">
        <?= $resposta['resposta'] === $resposta['resposta_correta'] ? 'Acertou!' : 'Errou!' ?>
    </span>
</li>
            <?php } ?>
        </ul>

        <a href="index.php" class="btn-voltar">Voltar ao Simulado</a>
    </div>
</body>
</html>
