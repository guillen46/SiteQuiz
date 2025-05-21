<?php
$password = "admin"; // Senha que você deseja atribuir ao admin
$hash = password_hash($password, PASSWORD_DEFAULT);
echo $hash;
?>
