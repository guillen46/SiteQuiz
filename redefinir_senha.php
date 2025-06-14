<?php
$token = $_GET['token'] ?? '';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Redefinir Senha</title>
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

    .redefinir-container {
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

    input[type="password"] {
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

  <div class="redefinir-container">
    <h2>Nova Senha</h2>
    <form action="salvar_nova_senha.php" method="POST">
      <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
      <input type="password" name="nova_senha" placeholder="Digite a nova senha" required>
      <input type="password" name="confirmar_senha" placeholder="Confirme a nova senha" required>
      <button type="submit">Salvar nova senha</button>
    </form>
  </div>

</body>
</html>
