<?php
require('conexao.php');

$materia = isset($_GET['materia']) ? $_GET['materia'] : null;

if ($materia) {
    $sql = "SELECT * FROM perguntas WHERE materia = ? ORDER BY RAND() LIMIT 1";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $materia);
} else {
    $sql = "SELECT * FROM perguntas ORDER BY RAND() LIMIT 1";
    $stmt = $conexao->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $pergunta = $result->fetch_assoc();
    header('Content-Type: application/json');
    echo json_encode($pergunta);
} else {
    echo json_encode(['error' => 'Nenhuma pergunta encontrada']);
}

$conexao->close();
?>
