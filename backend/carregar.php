<?php
require 'conexao.php';

$sql = "SELECT * FROM perguntas ORDER BY id DESC";
$result = $conn->query($sql);

$dados = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $dados[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($dados);

$conn->close();
?>
