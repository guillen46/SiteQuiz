<?php
// Conexão com o banco de dados
require 'conexao.php';

// Teste de conexão
if ($conexao) {
    echo "Conexão bem-sucedida!";
} else {
    echo "Erro na conexão: " . mysqli_connect_error();
}

$conexao->close();
?>
