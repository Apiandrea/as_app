<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "as_app";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Errore CSRF: Il token non è valido.");
    }

    $inputUsername = trim($_POST['username']);
    $inputPassword = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $inputUsername);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($inputPassword, $row['password'])) {
            $_SESSION['username'] = $inputUsername;
            $_SESSION['user_id'] = $row['id'];
            $_SESSION["is_admin"] = $row['is_admin'];
            header("Location: home.php");
            exit();
        } else {
            $error_message = "Credenziali non valide!";
        }
    } else {
        $error_message = "Credenziali non valide!";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AS APP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(45deg, #fff4d8, #fff0ff);
            color: #fff;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .container {
            background: rgba(0, 0, 0, 0.6);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 14px;
        }

        .password-container {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-container input {
            width: 100%;
            padding-right: 40px;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            cursor: pointer;
            background: none;
            border: none;
            color: #fff;
            font-size: 18px;
            padding: 5px;
        }

        input {
            width: 95%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #fff;
            border-radius: 5px;
            background: #222;
            color: #fff;
            font-size: 16px;
        }

        .buttonAccedi {
            width: 100%;
            padding: 12px;
            background: #1b1c1c;
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .buttonAccedi:hover {
            background: #acadac;
        }

        .error {
            color: #ff0000;
            text-align: center;
            margin-top: 10px;
        }

        .link {
            text-align: center;
            margin-top: 20px;
        }

        .link a {
            color: #00b4d8;
            text-decoration: none;
        }

        .link a:hover {
            color: #0080ff;
        }

        .title {
            font-size: 32px;
            font-weight: bold;
            margin-top: 20px;
            text-align: center;
            color: #000000;
        }

        footer {
            background: rgba(0, 0, 0, 0.6);
            color: #fff;
            text-align: center;
            padding: 15px 0;
            font-size: 14px;
        }
    </style>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
          const toggleButtons = document.querySelectorAll(".toggle-password");

          toggleButtons.forEach(button => {
              button.addEventListener("click", function () {
                  const passwordField = this.previousElementSibling;
                  if (passwordField.type === "password") {
                      passwordField.type = "text";
                      this.textContent = "🙈";
                  } else {
                      passwordField.type = "password";
                      this.textContent = "👁️";
                  }
              });
          });
      });
    </script>
</head>
<body>
    <div class="title">Apicella - Squitieri APP</div>

    <main>
        <div class="container">
            <h2>Login</h2>

            <?php
            if (isset($error_message)) {
                echo "<div class='error'>$error_message</div>";
            }
            ?>

            <form method="POST" action="login.php">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <label for="username">Nome Utente</label>
                <input type="text" name="username" required>

                <label for="password">Password</label>

                <div class="password-container">
                    <input type="password" name="password" id="password" required>
                    <button type="button" class="toggle-password" id="togglePassword" onclick="togglePassword()">👁️</button>
                </div>

                <button type="submit" class="buttonAccedi">Accedi</button>
            </form>

            <div class="link">
                <p>Non hai un account? <a href="register.php">Registrati</a></p>
            </div>
        </div>
    </main>

    <?php include "footer.php"; ?>
</body>
</html>

