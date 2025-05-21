<?php
session_start();
require('conexao.php');

$email = trim($_POST['email']);
$senha = $_POST['senha'];

$sql = "SELECT id, nome, email, senha, tipo FROM usuarios WHERE email = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $usuario = $resultado->fetch_assoc();

    // Debug: Exibir a senha recuperada do banco para verificar se está OK


    if (password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_email'] = $usuario['email'];
        $_SESSION['usuario_tipo'] = $usuario['tipo'];

}
}
$stmt->close();
$conexao->close();
?>
