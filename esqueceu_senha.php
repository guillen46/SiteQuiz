<?php
// Formulário para usuário informar o email
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Recuperar Senha</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #121212;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      animation: fadeIn 0.8s ease-in-out;
    }

    .recuperar-container {
      background: #1e1e1e;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.5);
      width: 100%;
      max-width: 400px;
      text-align: center;
      color: #fff;
    }

    h2 {
      margin-bottom: 20px;
      color: #fff;
    }

    input[type="email"] {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #444;
      background: #2c2c2c;
      color: #fff;
      border-radius: 8px;
      transition: border-color 0.3s ease;
    }

    input:focus {
      border-color: #218838;
      outline: none;
    }

    button {
      background-color: #218838;
      color: white;
      padding: 12px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      width: 100%;
      font-size: 16px;
      margin-top: 15px;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #1c6c30;
    }

    .voltar-link {
      margin-top: 20px;
      font-size: 14px;
      color: #ccc;
    }

    .voltar-link a {
      color: #218838;
      text-decoration: none;
      font-weight: bold;
      transition: color 0.3s ease;
    }

    .voltar-link a:hover {
      color: #1c6c30;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-15px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>
</head>
<body>

  <div class="recuperar-container">
    <h2>Recuperar Senha</h2>
    <form action="processa_recuperacao.php" method="POST">
      <input type="email" name="email" placeholder="Digite seu e-mail" required>
      <button type="submit">Enviar link de recuperação</button>
    </form>
    <p class="voltar-link"><a href="formlogin.php">Voltar ao login</a></p>
  </div>

</body>
</html>
