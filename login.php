<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Login - Laundry Asqia</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="assets/img/icon.ico" />

  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(to right, #007bff, #00c6ff);
      font-family: 'Open Sans', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-box {
      background: #fff;
      border-radius: 12px;
      padding: 40px;
      width: 100%;
      max-width: 400px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .login-title {
      font-size: 28px;
      font-weight: 600;
      color: #007bff;
      margin-bottom: 30px;
      text-align: center;
    }

    .form-control:focus {
      box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
      border-color: #007bff;
    }

    .btn-login {
      background-color: #007bff;
      color: white;
      font-weight: 600;
    }

    .btn-login:hover {
      background-color: #0056b3;
    }

    .alert-message {
      font-size: 14px;
      padding: 8px 15px;
      margin-bottom: 20px;
    }
  </style>
</head>

<body>

  <div class="login-box">
    <div class="login-title">Laundry Asqia</div>

    <!-- Menampilkan pesan error jika ada -->
    <?php if (isset($_GET['message'])) : ?>
      <div class="alert alert-danger alert-message text-center">
        <?= htmlspecialchars($_GET['message']); ?>
      </div>
    <?php endif; ?>

    <form action="cek_login.php" method="post">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" id="username" class="form-control" required autocomplete="off" placeholder="Enter your username">
      </div>

      <div class="mb-4">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" id="password" class="form-control" required autocomplete="off" placeholder="Enter your password">
      </div>

      <button type="submit" class="btn btn-login w-100">Login</button>
    </form>
  </div>

  <!-- Bootstrap JS (optional, untuk komponen interaktif) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
