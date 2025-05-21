<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário está logado e é admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'admin') {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Excluir a pergunta pelo ID
    $query = "DELETE FROM perguntas WHERE id = ?";
    $stmt = $conexao->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        header('Location: controle_perguntas_admin.php'); // Redireciona após excluir
        exit;
    } else {
        echo "Erro ao excluir a pergunta.";
    }
} else {
    die('ID da pergunta não fornecido.');
}
?>
