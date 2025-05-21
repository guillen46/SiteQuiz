<?php
$host = 'localhost';
$usuario = 'root';
$senha = '123456';
$banco = 'qapnaprova';

$conexao = new mysqli($host, $usuario, $senha, $banco);  // Alterando de $conn para $conexao

if ($conexao->connect_error) {
    die("Erro de conexão: " . $conexao->connect_error);
}
?>
