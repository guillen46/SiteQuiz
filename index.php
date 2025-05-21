<?php
session_start();
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Consulta para verificar o usuário no banco
    $query = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conexao->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        // Sessão de login
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_tipo'] = $usuario['tipo'];

        // Redireciona para a página correta dependendo do tipo de usuário
        if ($usuario['tipo'] == 'admin') {
            header('Location: controle_perguntas_admin.php');
        } else {
            header('Location: index.php');
        }
        exit;
    } else {
        $erro = "Email ou senha inválidos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Simulado - QAPnaProva</title>
  <link rel="stylesheet" href="style.css">
  <script>
let respostaCorreta = "";
let materiaSelecionada = "";
let respostaConfirmada = false; // Flag para verificar se a resposta foi confirmada
let perguntasRespondidas = []; // Array para armazenar IDs das perguntas respondidas

function verificarResposta() {
  const opcoes = document.getElementsByName("resposta");
  let selecionada = null;

  for (let opcao of opcoes) {
    if (opcao.checked) {
      selecionada = opcao.value;
      break;
    }
  }

  const feedback = document.getElementById("feedback");
  feedback.style.display = "block";

  if (!selecionada) {
    feedback.innerHTML = "<p>Por favor, selecione uma alternativa!</p>";
    return;
  }

  if (selecionada.toUpperCase() === respostaCorreta.toUpperCase()) {
    feedback.innerHTML = "<p style='color: green;'><strong>Parabéns! Você acertou!</strong></p>";
  } else {
    feedback.innerHTML = `<p style='color: red;'><strong>Você errou!</strong> A resposta correta era: ${respostaCorreta}</p>`;
  }

  // 🚫 Desativa as alternativas após a resposta
  opcoes.forEach(opcao => {
    opcao.disabled = true;
  });

  respostaConfirmada = true; // Marca que a resposta foi confirmada
  // Envia a resposta para salvar no banco de dados
fetch('salvar_resposta.php', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/x-www-form-urlencoded',
  },
  body: `pergunta_id=${encodeURIComponent(pergunta.id)}&resposta=${encodeURIComponent(selecionada)}`
});


  // Remover a classe 'desativado' e permitir o clique no botão de mostrar solução
  document.getElementById("mostrar-solucao-btn").classList.remove("desativado");
  document.getElementById("mostrar-solucao-btn").disabled = false; // Habilita o botão
}

function mostrarSolucao() {
  if (!respostaConfirmada) {
    alert("Você precisa confirmar sua resposta antes de ver a solução.");
    return;
  }

  const solucaoDiv = document.getElementById('solucao-container');
  solucaoDiv.style.display = "block"; // Exibe a solução
}

function carregarPergunta() {
  const perguntaDiv = document.getElementById("pergunta");
  perguntaDiv.classList.remove("fade"); // reinicia animação

  let url = 'pergunta_aleatoria.php';
  if (materiaSelecionada !== "") {
    url += '?materia=' + encodeURIComponent(materiaSelecionada);
  }

  fetch(url)
    .then(response => response.json())
    .then(pergunta => {
      if (!pergunta.error) {
        // Verifica se a pergunta já foi respondida
        if (perguntasRespondidas.includes(pergunta.id)) {
          carregarPergunta(); // Chama recursivamente até encontrar uma nova pergunta
          return;
        }

        // Marca a pergunta como respondida
        perguntasRespondidas.push(pergunta.id);
        respostaCorreta = pergunta.resposta_correta;

        document.getElementById('enunciado').innerHTML = pergunta.enunciado;

        const alternativasDiv = document.getElementById('alternativas');
        let html = "";

        const ids = { A: 'alt-a', B: 'alt-b', C: 'alt-c', D: 'alt-d', E: 'alt-e' };
        if (pergunta.alternativa_a) html += `<input type="radio" name="resposta" value="A" id="${ids.A}"><label for="${ids.A}">A) ${pergunta.alternativa_a}</label>`;
        if (pergunta.alternativa_b) html += `<input type="radio" name="resposta" value="B" id="${ids.B}"><label for="${ids.B}">B) ${pergunta.alternativa_b}</label>`;
        if (pergunta.alternativa_c) html += `<input type="radio" name="resposta" value="C" id="${ids.C}"><label for="${ids.C}">C) ${pergunta.alternativa_c}</label>`;
        if (pergunta.alternativa_d) html += `<input type="radio" name="resposta" value="D" id="${ids.D}"><label for="${ids.D}">D) ${pergunta.alternativa_d}</label>`;
        if (pergunta.alternativa_e) html += `<input type="radio" name="resposta" value="E" id="${ids.E}"><label for="${ids.E}">E) ${pergunta.alternativa_e}</label>`;

        alternativasDiv.innerHTML = html;

        document.getElementById('solucao').innerHTML = pergunta.solucao;
        document.getElementById('feedback').style.display = "none";
        document.getElementById('solucao-container').style.display = "none";

        // Desabilita o botão "Mostrar Solução" inicialmente, com a classe 'desativado'
        document.getElementById("mostrar-solucao-btn").classList.add("desativado");
        document.getElementById("mostrar-solucao-btn").disabled = true; // Desativa o botão

        perguntaDiv.classList.add("fade"); // animação de entrada
      } else {
        alert('Erro ao carregar pergunta');
      }
    })
    .catch(error => console.error('Erro ao carregar a pergunta:', error));
}

window.onload = () => {
  carregarPergunta();

  const materiaLinks = document.querySelectorAll('.materia-link');

  materiaLinks.forEach(link => {
    link.addEventListener('click', e => {
      e.preventDefault();

      const botaoClicado = e.target;

      // Ignora se já estiver desativado
      if (botaoClicado.classList.contains('desativado')) return;

      materiaSelecionada = botaoClicado.dataset.materia;

      // Remove classe 'desativado' de todos
      materiaLinks.forEach(btn => btn.classList.remove('desativado'));

      // Adiciona a classe no botão clicado
      botaoClicado.classList.add('desativado');

      carregarPergunta();
    });
  });
};
</script>
</head>
<body class="simulado-page">
  <!-- Sidebar -->
  <div class="sidebar">
  <div class="user-info">
    <div class="nome"><?= htmlspecialchars($_SESSION['usuario_nome']) ?></div>
    <div class="email"><?= htmlspecialchars($_SESSION['usuario_email']) ?></div>
  </div>
  <ul>
      <?php
      require_once 'conexao.php';
      $materias = $conexao->query("SELECT DISTINCT materia FROM perguntas ORDER BY materia");
      while ($row = $materias->fetch_assoc()) {
          $materia = htmlspecialchars($row['materia']);
          echo "<li><a href='#' class='materia-link' data-materia=\"$materia\">$materia</a></li>";
      }
      ?>
    </ul>
    <!-- Novo botão de perfil -->
<a href="perfil.php" class="perfil-btn" style="position: absolute; bottom: 180px; left: 20px; right: 20px; background-color: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 8px; font-size: 14px; text-align: center; text-decoration: none;">Perfil</a>

  <?php if (isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'admin'): ?>
    <a href="admin.html" class="admin-btn" style="position: absolute; bottom: 130px; left: 20px; right: 20px; background-color: #28a745; color: white; padding: 10px 15px; border: none; border-radius: 8px; font-size: 14px; text-align: center; text-decoration: none;">Cadastrar Pergunta</a>
  <?php endif; ?>

  <a href="logout.php" class="logout-btn">Sair</a>
</div>


  <!-- Conteúdo Principal -->
  <div class="container">
  <h1 style="color: #28a745;">Perguntas - QAPNAPROVA</h1>

    <div id="pergunta" class="pergunta fade">
      <h3 id="enunciado"></h3>

      <div id="alternativas" class="alternativas"></div>

      <button onclick="verificarResposta()">Confirmar Resposta</button>
      <button id="mostrar-solucao-btn" onclick="mostrarSolucao()" class="desativado" disabled>Mostrar Solução</button>
<button onclick="carregarPergunta()">Próxima Pergunta</button>


      <div id="feedback" class="resposta" style="display:none;"></div>

      <div id="solucao-container" class="resposta" style="display:none;">
        <h4>Solução:</h4>
        <div id="solucao" style="margin-top: 10px;"></div>
      </div>
    </div>
  </div>
</body>
</html>
