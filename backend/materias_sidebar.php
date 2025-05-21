<?php
require('conexao.php');

$sql = "SELECT DISTINCT materia FROM perguntas ORDER BY materia";
$resultado = $conexao->query($sql);

if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $materia = htmlspecialchars($row['materia']);
        echo "<li><a href='#' class='materia-link' data-materia='$materia'>$materia</a></li>";
    }
} else {
    echo "<li>Nenhuma matéria encontrada.</li>";
}
$conexao->close();
?>
