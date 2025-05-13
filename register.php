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

$success_message = "";
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Errore CSRF: Il token non è valido.");
    }

    $inputUsername = trim($_POST['username']);
    $inputEmail = trim($_POST['email']);
    $inputPassword = trim($_POST['password']);
    $inputConfirmPassword = trim($_POST['confirmPassword']);

    if ($inputPassword === $inputConfirmPassword) {
        $passwordHash = password_hash($inputPassword, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $inputUsername, $passwordHash, $inputEmail);

        if ($stmt->execute()) {
            $success_message = "Registrazione avvenuta con successo!";
        } else {
            $error_message = "Errore nella registrazione: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error_message = "Le password non coincidono!";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="it">

<script>
    function checkAvailability(field, value) {
        if (value.trim() === "") {
            document.getElementById(field + "-feedback").textContent = "";
            return;
        }

        const formData = new FormData();
        formData.append('field', field);
        formData.append('value', value);

        fetch('check_user.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            const feedbackElement = document.getElementById(field + "-feedback");
            feedbackElement.textContent = data.message;
            feedbackElement.style.color = data.status === 'available' ? 'lightgreen' : 'red';
        })
        .catch(error => {
            console.error('Errore:', error);
        });
    }
</script>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione - AS APP</title>
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
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
            color: #fff;
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

        .buttonRegistrati {
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

        .buttonRegistrati:hover {
            background: #acadac;
        }

        .error, .success {
            text-align: center;
            margin-top: 10px;
            font-size: 14px;
            padding: 10px;
            border-radius: 5px;
        }

        .error {
            background-color: #ff4d4d;
        }

        .success {
            background-color: #28a745;
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
            <h2>Registrazione</h2>

            <?php
            if (!empty($success_message)) {
                echo "<div class='success'>$success_message</div>";
            }
            if (!empty($error_message)) {
                echo "<div class='error'>$error_message</div>";
            }
            ?>

            <form method="POST" action="register.php">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <label for="username">Nome Utente</label>
                <input type="text" name="username" id="username" required onkeyup="checkAvailability('username', this.value)">
                <div id="username-feedback" class="feedback"></div>

                <label for="email">Email</label>
                <input type="email" name="email" id="email" required onkeyup="checkAvailability('email', this.value)">
                <div id="email-feedback" class="feedback"></div>

                <label for="password">Password</label>
                <div class="password-container">
                    <input type="password" name="password" id="password" required pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$" title="Almeno 8 caratteri, una maiuscola, un numero e un carattere speciale">
                    <button type="button" class="toggle-password">👁️</button>
                </div>

                <label for="confirmPassword">Conferma Password</label>
                <div class="password-container">
                    <input type="password" name="confirmPassword" id="confirmPassword" required pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$" title="Almeno 8 caratteri, una maiuscola, un numero e un carattere speciale">
                    <button type="button" class="toggle-password">👁️</button>
                </div>

                <button type="submit" class="buttonRegistrati">Registrati</button>
            </form>

            <div class="link">
                <p>Hai già un account? <a href="login.php">Accedi</a></p>
            </div>
        </div>
    </main>

    <?php include "footer.php"; ?>
</body>
</html>

