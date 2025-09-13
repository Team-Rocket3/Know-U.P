<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

$message = ''; 

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "up_tourism";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    $message = 'Database connection failed.';
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($name) || empty($email) || empty($password)) {
        $message = 'All fields are required.';
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $message = 'Email already registered.';
            $stmt->close();
        } else {
            $stmt->close();
               $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
               $stmt->bind_param("sss", $name, $email, $password);

            if ($stmt->execute()) {
                $_SESSION['user_id']    = $stmt->insert_id;
                $_SESSION['user_email'] = $email;
                $_SESSION['user_name']  = $name;

                echo "<script>
                    alert('Signup successful!');
                    localStorage.setItem('userEmail', " . json_encode($email) . ");
                    window.location.href = 'profile.php';
                </script>";

                $stmt->close();
                $conn->close();
                exit;
            } else {
                $message = 'Signup failed. Please try again.';
                $stmt->close();
            }
        }
    }
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Sign Up</title>
  <style>
    *, *::before, *::after {
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background: url('../ExploreUP/photo/signup4.jpg') no-repeat center center fixed;
      background-size: cover;
      min-height: 100vh;
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .signup-box {
      background: rgba(0, 0, 0, 0.25);
      backdrop-filter: blur(6px);
      padding: 35px;
      border-radius: 20px;
      width: 360px;
      color: #fff;
      box-shadow: 0 8px 25px rgba(0,0,0,0.3);
      position: relative;
    }

    .home-icon {
      position: absolute;
      top: 12px;
      left: 15px;
      font-size: 22px;
      color: #fff;
      text-decoration: none;
      transition: color 0.2s;
    }
    .home-icon:hover {
      transform: scale(1.1);
      color: #ff0000ff;
    }

    .admin-icon {
      position: absolute;
      top: 12px;
      right: 15px;
      font-size: 22px;
      color: #fff;
      text-decoration: none;
      transition: color 0.2s;
    }

    .admin-icon:hover {
      transform: scale(1.1);
      color: #ffd700;
    }

    .signup-box h2 {
      text-align: center;
      margin-bottom: 25px;
      font-size: 1.8rem;
      font-weight: 700;
    }

    .signup-box .input-group {
      position: relative;
      margin-bottom: 20px;
    }

    .signup-box .input-group input {
      width: 100%;
      padding: 12px 10px;
      border: none;
      border-bottom: 1.5px solid #ddd;
      background: transparent;
      color: #fff;
      font-size: 1rem;
    }

    .signup-box .input-group input:focus {
      outline: none;
      border-color: #28a745;
    }

    .signup-box button {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 8px;
      background: linear-gradient(135deg, #0d1b2a, #1b263b);
      color: #fff;
      font-size: 1.05rem;
      font-weight: 600;
      cursor: pointer;
      transition: transform 0.4s ease, background 0.4s ease;
    }

    .signup-box button:hover {
      background: linear-gradient(135deg, #1b263b, #0d1b2a);
      transform: translateY(-2px);
    }

    .login-link {
      text-align: center;
      margin-top: 18px;
      font-size: 0.9rem;
    }

    .login-link a {
      color: #00c6ff;
      text-decoration: none;
      font-weight: 600;
    }

    .login-link a:hover {
      color: #22ff09ff;
      text-decoration: underline;
    }
  </style>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <?php if ($message) : ?>
    <script>alert(<?= json_encode($message) ?>);</script>
  <?php endif; ?>

  <div class="signup-box">
    <a href="../index.html" class="home-icon"><i class="fa fa-home"></i></a>
    <a href="../admin/login.php" class="admin-icon"><i class="fas fa-user-shield"></i></a>

    <h2>Create Account</h2>
    <form action="" method="POST" novalidate>
      <div class="input-group">
        <input type="text" id="name" name="name" placeholder="Full Name" required value="<?= $_POST['name'] ?? '' ?>" />
      </div>

      <div class="input-group">
        <input type="email" id="email" name="email" placeholder="Email" required value="<?= $_POST['email'] ?? '' ?>" />
      </div>

      <div class="input-group">
        <input type="password" id="password" name="password" placeholder="Password" required minlength="6" />
      </div>

      <button type="submit">Sign Up</button>

      <div class="login-link">
        Do you have a account?👉🏽 <a href="login.php">Login</a>  👈🏽
      </div>
    </form>
  </div>
</body>
</html>