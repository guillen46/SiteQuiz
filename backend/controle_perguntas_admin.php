<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário está logado e é admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'admin') {
    header('Location: login.php');
    exit;
}

// Lógica para a pesquisa
$searchQuery = "";
if (isset($_POST['pesquisa'])) {
    $searchQuery = $_POST['pesquisa'];
}

// Consultar todas as perguntas ou com filtro de pesquisa
$query = "SELECT * FROM perguntas WHERE enunciado LIKE ? OR materia LIKE ? ORDER BY id DESC";
$stmt = $conexao->prepare($query);
$searchTerm = "%$searchQuery%";
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de Perguntas - Admin</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #ddd;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 40px auto;
            padding: 20px;
            background-color: #1e1e1e;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
        }

        h1 {
            text-align: center;
            color: #ffcc00;
        }

        form {
            margin-bottom: 20px;
            text-align: center;
        }

        input[type="text"] {
            padding: 10px;
            width: 70%;
            font-size: 16px;
            border: 1px solid #444;
            border-radius: 6px;
            margin-right: 10px;
            background-color: #333;
            color: #fff;
        }

        button {
            padding: 10px 15px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #218838;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #444;
        }

        th {
            background-color: #333;
        }

        td {
            background-color: #222;
        }

        a {
            color: #ffcc00;
            text-decoration: none;
            padding: 5px 10px;
            background-color: #444;
            border-radius: 6px;
            margin-right: 5px;
        }

        a:hover {
            background-color: #555;
        }

        .action-buttons a {
            margin-right: 10px;
        }

        .btn-danger {
            background-color: #d9534f;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c9302c;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            table {
                font-size: 14px;
            }

            input[type="text"] {
                width: 100%;
            }

            button {
                width: 100%;
                margin-top: 10px;
            }
        }

        /* Estilo da Modal */
        .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0, 0, 0);
        background-color: rgba(0, 0, 0, 0.6); /* Fundo semitransparente mais escuro */
        padding-top: 60px;
    }

    .modal-content {
        background-color: #2c2f38; /* Cor mais escura para a modal */
        margin: 10% auto;
        padding: 20px;
        border: 1px solid #444;
        width: 40%; /* Tamanho da modal menor */
        border-radius: 10px; /* Bordas arredondadas */
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.8); /* Sombra para dar destaque */
    }

    .modal-content h4 {
        color: #ffcc00; /* Cor do título mais suave */
        text-align: center;
    }

    .modal-content p {
        color: #ddd; /* Texto da modal em tom claro */
        text-align: center;
        font-size: 18px;
    }

    button {
        padding: 12px 20px;
        background-color: #28a745; /* Cor do botão de sucesso */
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        width: 100%;
        margin-top: 15px;
    }

    button:hover {
        background-color: #218838;
    }

    </style>
</head>
<nav style="background-color: #333; padding: 10px;">
  <a href="index.php" style="color: white; margin-right: 15px; text-decoration: none; font-weight: bold;">Pagina das Perguntas</a>
  <a href="controle_perguntas_admin.php" style="color: white; text-decoration: none; font-weight: bold;">Controle de Perguntas</a>
</nav>
<body>
    <!-- Modal de Sucesso -->
    <div id="modalSucesso" class="modal">
        <div class="modal-content">
            <h4>Sucesso!</h4>
            <p>A pergunta foi atualizada com sucesso.</p>
            <button onclick="fecharModal()">Fechar</button>
        </div>
    </div>

    <div class="container">
        <h1>Controle de Perguntas - Administrador</h1>
        
        <!-- Formulário de pesquisa -->
        <form method="POST" action="controle_perguntas_admin.php">
            <input type="text" name="pesquisa" placeholder="Pesquisar perguntas..." value="<?= htmlspecialchars($searchQuery) ?>" />
            <button type="submit">Pesquisar</button>
        </form>

        <!-- Tabela de perguntas -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Enunciado</th>
                    <th>Matéria</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($pergunta = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $pergunta['id'] ?></td>
                        <td>
                            <!-- Remover as tags HTML das perguntas e decodificar as entidades HTML -->
                            <?= html_entity_decode(strip_tags($pergunta['enunciado'])) ?>
                        </td>
                        <td><?= htmlspecialchars($pergunta['materia']) ?></td>
                        <td class="action-buttons">
                            <a href="editar_pergunta.php?id=<?= $pergunta['id'] ?>">Editar</a>
                            <a href="excluir_pergunta.php?id=<?= $pergunta['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir esta pergunta?')">Excluir</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Função para abrir a modal
        function abrirModal() {
            document.getElementById('modalSucesso').style.display = 'block';
        }

        // Função para fechar a modal
        function fecharModal() {
            document.getElementById('modalSucesso').style.display = 'none';
        }

        // Verifique se o parâmetro 'atualizada' está presente na URL
        window.onload = function() {
            var urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('atualizada') === 'true') {
                abrirModal();
            }
        };
    </script>
</body>
</html>