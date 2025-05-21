<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário está logado e é admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'admin') {
    header('Location: login.php');
    exit;
}

// Verifica se o parâmetro 'id' foi passado
if (!isset($_GET['id'])) {
    echo "ID da pergunta não fornecido!";
    exit;
}

$id_pergunta = (int)$_GET['id'];

// Busca os dados da pergunta no banco de dados
$query = "SELECT * FROM perguntas WHERE id = ?";
$stmt = $conexao->prepare($query);
$stmt->bind_param("i", $id_pergunta);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {
    echo "Pergunta não encontrada!";
    exit;
}

$pergunta = $resultado->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Pergunta</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Editar Pergunta</h1>

        <form method="POST" action="atualizar_pergunta.php">
            <input type="hidden" name="id" value="<?= $pergunta['id'] ?>">

            <label for="materia">Matéria</label>
            <input type="text" name="materia" value="<?= htmlspecialchars($pergunta['materia']) ?>" required>

            <label for="assunto">Assunto</label>
            <input type="text" name="assunto" value="<?= htmlspecialchars($pergunta['assunto']) ?>" required>

            <label for="enunciado">Enunciado</label>
            <textarea name="enunciado" required><?= htmlspecialchars($pergunta['enunciado']) ?></textarea>

            <label for="alternativa_a">Alternativa A</label>
            <input type="text" name="alternativa_a" value="<?= htmlspecialchars($pergunta['alternativa_a']) ?>" required>

            <label for="alternativa_b">Alternativa B</label>
            <input type="text" name="alternativa_b" value="<?= htmlspecialchars($pergunta['alternativa_b']) ?>" required>

            <label for="alternativa_c">Alternativa C</label>
            <input type="text" name="alternativa_c" value="<?= htmlspecialchars($pergunta['alternativa_c']) ?>" required>

            <label for="alternativa_d">Alternativa D</label>
            <input type="text" name="alternativa_d" value="<?= htmlspecialchars($pergunta['alternativa_d']) ?>" required>

            <label for="alternativa_e">Alternativa E</label>
            <input type="text" name="alternativa_e" value="<?= htmlspecialchars($pergunta['alternativa_e']) ?>" required>

            <label for="resposta_correta">Resposta Correta</label>
            <input type="text" name="resposta_correta" value="<?= htmlspecialchars($pergunta['resposta_correta']) ?>" required>

            <label for="solucao">Solução</label>
            <textarea name="solucao" required><?= htmlspecialchars($pergunta['solucao']) ?></textarea>

            <button type="submit">Atualizar Pergunta</button>
        </form>

        <a href="controle_perguntas_admin.php">Voltar ao controle de perguntas</a>
    </div>
</body>
</html>
