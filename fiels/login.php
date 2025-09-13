<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Handle logout
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    session_unset();
    session_destroy();
    header("Location: ../index.html");
    exit();
}

// Default message
$message = '';
$email_value = '';

// If form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // DB connection
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $dbname = 'up_tourism';

    $conn = new mysqli($host, $user, $pass, $dbname);

    if ($conn->connect_error) {
        $message = "Database connection error.";
    } else {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $email_value = $email; // repopulate form

        if (empty($email) || empty($password)) {
            $message = "Please fill all fields.";
        } else {
            $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email=? LIMIT 1");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows === 1) {
                $stmt->bind_result($id, $name, $email_db, $db_password);
                $stmt->fetch();

                // Plain text password comparison
                if ($password === $db_password) {
                    // Set session variables and redirect
                    $_SESSION['user_id'] = $id;
                    $_SESSION['user_name'] = $name;
                    $_SESSION['user_email'] = $email_db;
                    echo "<script>
                        alert('Login successful!');
                        localStorage.setItem('userEmail', " . json_encode($email) . ");
                        window.location.href = 'profile.php';
                    </script>";

                    $stmt->close();
                    $conn->close();
                    exit;
                } else {
                    $message = "Incorrect password.";
                }
            } else {
                $message = "No user found with this email.";
            }
            $stmt->close();
        }
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Login</title>
  <style>
    *, *::before, *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Segoe UI', Roboto, Arial, sans-serif;
      background: url('../ExploreUP/photo/loging1.jpg') no-repeat center center fixed;
      background-size: cover;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .login-box {
      background: rgba(0, 0, 0, 0.4);
      backdrop-filter: blur(12px);
      padding: 40px 35px;
      border-radius: 18px;
      width: 360px;
      color: #fff;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.45);
      position: relative;
    }


    .home-icon {
      position: absolute;
      top: 14px;
      left: 15px;
      font-size: 22px;
      color: #fff;
      text-decoration: none;
      transition: color 0.2s ease;
    }

    .home-icon:hover {
    transform: scale(1.1);
      color: #ff0000ff;
    }

    .admin-icon {
      position: absolute;
      top: 14px;
      right: 15px;
      font-size: 22px;
      color: #fff;
      text-decoration: none;
      transition: color 0.2s ease;
    }

    .admin-icon:hover {
      transform: scale(1.1);
      color: #ffd700;
    }

    .login-box h2 {
      text-align: center;
      margin-bottom: 28px;
      font-size: 1.9rem;
      font-weight: 700;
      color: #f9f9f9;
      letter-spacing: 0.5px;
    }

    .input-group {
      position: relative;
      margin-bottom: 22px;
    }

    .input-group input {
      width: 100%;
      padding: 12px 10px;
      border: none;
      border-bottom: 1.5px solid rgba(255, 255, 255, 0.5);
      background: transparent;
      color: #fff;
      font-size: 1rem;
      transition: border-color 0.3s, box-shadow 0.3s;
    }

    .input-group input:focus {
      outline: none;
      border-color: #00c6ff;
      box-shadow: 0 2px 6px rgba(0, 198, 255, 0.25);
    }

    .input-group i {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 18px;
      color: #bbb;
      pointer-events: none;
    }

    button {
      width: 100%;
      padding: 13px;
      border: none;
      border-radius: 10px;
      background: linear-gradient(135deg, #0d1b2a, #1b263b);
      color: #fff;
      font-size: 1.05rem;
      font-weight: 600;
      cursor: pointer;
      transition: transform 0.4s ease, background 0.4s ease;
    }

    button:hover {
      background: linear-gradient(135deg, #1b263b, #0d1b2a);
      transform: translateY(-2px);
    }

    .signup {
      text-align: center;
      margin-top: 20px;
      font-size: 0.92rem;
      color: #e0e0e0;
    }

    .signup a {
      color: #00c6ff;
      text-decoration: none;
      font-weight: 600;
      transition: color 0.2s;
    }
    .signup a:hover {
      color: #22ff09ff;
      text-decoration: underline;
    }
  </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <?php if ($message): ?>
  <script>alert(<?= json_encode($message) ?>);</script>
  <?php endif; ?>

  <div class="login-box">
    <a href="../index.html" class="home-icon"><i class="fa fa-home"></i></a>
    <a href="../admin/login.php" class="admin-icon"><i class="fa fa-user-secret"></i></a>
    <h2>Login</h2>
    <form action="" method="POST" novalidate>
      <div class="input-group">
        <input type="email" id="email" name="email" placeholder="Email" required value="<?= $_POST['email'] ?? '' ?>" />
      </div>
      <div class="input-group">
        <input type="password" id="password" name="password" placeholder="Password" required />
      </div>
      <button type="submit">Login</button>
      <div class="signup">
        Don’t have an account?👉🏽<a href="../fiels/signup.php">Sign Up</a>  👈🏽
      </div>
    </form>
  </div>
</body>
</html>