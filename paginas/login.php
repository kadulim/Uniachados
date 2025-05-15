<?php
session_start();
include '../php/connection.php';

$mensagem = "";
$tipo = ""; // success ou danger

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = $_POST['email'] ?? '';
  $senhaDigitada = $_POST['senha'] ?? '';

  $sql = "SELECT nome, email, senha FROM users WHERE email = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();

    if (password_verify($senhaDigitada, $usuario['senha'])) {
      $mensagem = "Bem-vindo, {$usuario['nome']}!<br>Email: {$usuario['email']}";
      $tipo = "success";
    } else {
      $mensagem = "Senha incorreta.";
      $tipo = "danger";
    }
  } else {
    $mensagem = "Usuário não encontrado.";
    $tipo = "danger";
  }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="../storage/css/index.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
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
        <li class="nav-item"><a class="nav-link active" href="login.php">Login</a></li>
        <li class="nav-item"><a class="nav-link" href="cadastro.php">Cadastro</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Card Login -->
<div class="center-container">
  <div class="card shadow" style="width: 100%; max-width: 400px;">
    <div class="card-body">
      <h5 class="card-title text-center mb-4">Login</h5>

      <form method="POST" action="login.php">
        <div class="input-group mb-3">
          <span class="input-group-text">Email</span>
          <input type="email" name="email" class="form-control" required placeholder="seuemail@exemplo.com">
        </div>

        <div class="input-group mb-3">
          <span class="input-group-text">Senha</span>
          <input type="password" name="senha" class="form-control" required placeholder="********">
        </div>

        <div class="mb-3 text-center">
          <a href="cadastro.php" class="text-decoration-none">Não tem Conta?</a>
        </div>

        <div class="d-grid">
          <button class="btn btn-primary" type="submit">Entrar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal de Resposta -->
<?php if (!empty($mensagem)): ?>
<div class="modal fade" id="mensagemModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-<?= $tipo ?>">
      <div class="modal-header bg-<?= $tipo ?> text-white">
        <h5 class="modal-title"><?= $tipo === "success" ? "Login realizado" : "Erro no login" ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <?= $mensagem ?>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- Footer -->
<footer class="bg-primary text-white text-center py-3 mt-auto border-top border-light">
  <div class="container">
    <p class="mb-0">© 2025 Uniachados. Todos os direitos reservados.</p>
  </div>
</footer>

<!-- Primeiro: carregar o Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

<!-- Depois: chamar o modal se existir -->
<?php if (!empty($mensagem)): ?>
<script>
  const modal = new bootstrap.Modal(document.getElementById('mensagemModal'));
  modal.show();
</script>
<?php endif; ?>

</body>
</html>