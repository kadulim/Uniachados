<?php
include('../php/connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nome = $_POST['nome'];
  $email = $_POST['email'];
  $senha = $_POST['senha'];

  if (!empty($nome) && !empty($email) && !empty($senha)) {
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (nome, email, senha) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
      $stmt->bind_param("sss", $nome, $email, $senha_hash);
      if ($stmt->execute()) {
        $sucesso = "Cadastro realizado com sucesso!";
      } else {
        $erro = "Erro ao cadastrar: " . $stmt->error;
      }
      $stmt->close();
    } else {
      $erro = "Erro na preparação da query.";
    }
  } else {
    $erro = "Preencha todos os campos!";
  }

  $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="../storage/css/index.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous" />
  <title>Login</title>
  <style>
    body {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .center-container {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar bg-primary border-bottom border-body" data-bs-theme="dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="../index.html">Uniachados</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
        <li class="nav-item"><a class="nav-link active" href="cadastro.php">Cadastro</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Card Cadastro -->
<div class="center-container">
  <div class="card shadow" style="width: 100%; max-width: 450px;">
    <div class="card-body">
      <h5 class="card-title text-center mb-4">Cadastro</h5>

      <?php if (isset($erro)): ?>
        <div class="alert alert-danger text-center"><?= $erro ?></div>
      <?php elseif (isset($sucesso)): ?>
        <div class="alert alert-success text-center"><?= $sucesso ?></div>
      <?php endif; ?>

      <form method="POST" action="">
        <div class="input-group mb-3">
          <span class="input-group-text">Nome</span>
          <input type="text" class="form-control" name="nome" placeholder="Seu nome completo" required>
        </div>

        <div class="input-group mb-3">
          <span class="input-group-text">E-mail</span>
          <input type="email" class="form-control" name="email" placeholder="email@exemplo.com" required>
        </div>

        <div class="input-group mb-3">
          <span class="input-group-text">Senha</span>
          <input type="password" class="form-control" name="senha" placeholder="Crie uma senha" required>
        </div>

        <!-- Link abaixo dos inputs -->
        <div class="mb-3 text-center">
          <a href="login.php" class="text-decoration-none">Já tem uma conta? Faça login</a>
        </div>

        <!-- Botão de cadastro -->
        <div class="d-grid">
          <button class="btn btn-success" type="submit">Cadastrar</button>
        </div>
      </form>

    </div>
  </div>
</div>


<!-- Footer -->
<footer class="bg-primary text-white text-center py-3 mt-auto border-top border-light">
  <div class="container">
    <p class="mb-0">© 2025 Uniachados. Todos os direitos reservados.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
