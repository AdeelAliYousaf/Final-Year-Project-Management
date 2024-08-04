<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>USKT FYP - Login</title>
  <!-- Latest Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap");

    body {
      background-image: url(Assets/uskt.jpg);
      background-position: center;
      background-repeat: no-repeat;
      background-size: cover;
      font-family: "Poppins", sans-serif;
    }
    .login-container {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }
    .login-box {
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      padding: 40px;
      width: 400px;
      max-width: 90%;
    }
    .login-title {
      font-size: 24px;
      font-weight: 600;
      margin-bottom: 30px;
      text-align: center;
    }
    .form-label {
      font-weight: 500;
    }
    .form-check-label {
      font-weight: normal;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="login-container">
      <div class="login-box">
        <h2 class="login-title">USKT FYP</h2>
        <form action="Login/login.php" method="post">
          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" class="form-control" id="username" placeholder="Enter your username" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" id="password" placeholder="Enter your password" required>
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-primary">Login</button>
          </div>
        </form>
        <hr>
        <div class="text-center">
          <a href="#">Forgot Password?</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Latest Bootstrap JS and dependencies -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
