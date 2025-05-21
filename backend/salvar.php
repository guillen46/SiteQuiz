<?php
require('conexao.php');

// Pegue os dados do formulário
$banca = $_POST['banca'];
$materia = $_POST['materia'];
$assunto = $_POST['assunto'];
$subassunto = $_POST['subassunto'];
$enunciado = $_POST['enunciado'];
$alternativa_a = $_POST['alternativa_a'];
$alternativa_b = $_POST['alternativa_b'];
$alternativa_c = $_POST['alternativa_c'];
$alternativa_d = $_POST['alternativa_d'];
$alternativa_e = $_POST['alternativa_e'] ?? null; // Alternativa E opcional
$resposta_correta = $_POST['resposta_correta'];
$solucao = $_POST['solucao'];

// Prepare a consulta SQL para salvar os dados
$sql = "INSERT INTO perguntas (
            banca, materia, assunto, subassunto, enunciado,
            alternativa_a, alternativa_b, alternativa_c,
            alternativa_d, alternativa_e,
            resposta_correta, solucao
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

if ($stmt = $conexao->prepare($sql)) {
    $stmt->bind_param(
        "ssssssssssss",
        $banca, $materia, $assunto, $subassunto, $enunciado,
        $alternativa_a, $alternativa_b, $alternativa_c,
        $alternativa_d, $alternativa_e,
        $resposta_correta, $solucao
    );

    if ($stmt->execute()) {
        header('Location: admin.html?sucesso=1');
        exit;
    } else {
        echo "Erro ao executar: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Erro na preparação da consulta: " . $conexao->error;
}

$conexao->close();
?>
