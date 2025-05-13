<?php
session_start();

// Connessione al database
$conn = new mysqli("localhost", "root", "", "as_app");
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

$username = $_SESSION['username'];
$query = $conn->prepare("SELECT * FROM users WHERE username = ?");
$query->bind_param("s", $username);
$query->execute();
$result = $query->get_result();
$utente = $result->fetch_assoc();

$messaggio = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuovoUsername = trim($_POST['username']);
    $nuovaEmail = trim($_POST['email']);
    $nuovaPassword = $_POST['password'];

    $checkUsername = $conn->prepare("SELECT id FROM users WHERE username = ? AND username != ?");
    $checkUsername->bind_param("ss", $nuovoUsername, $username);
    $checkUsername->execute();
    $resUsername = $checkUsername->get_result();

    $checkEmail = $conn->prepare("SELECT id FROM users WHERE email = ? AND username != ?");
    $checkEmail->bind_param("ss", $nuovaEmail, $username);
    $checkEmail->execute();
    $resEmail = $checkEmail->get_result();

    $pattern = "/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/";
    if (!preg_match($pattern, $nuovaPassword)) {
        $messaggio = "La password non rispetta i requisiti di sicurezza.";
    } elseif ($resUsername->num_rows > 0) {
        $messaggio = "Username già in uso.";
    } elseif ($resEmail->num_rows > 0) {
        $messaggio = "Email già registrata.";
    } else {
        $passwordHash = password_hash($nuovaPassword, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE users SET username = ?, email = ?, password = ?, updated_at = NOW() WHERE username = ?");
        $update->bind_param("ssss", $nuovoUsername, $nuovaEmail, $passwordHash, $username);
        if ($update->execute()) {
            $_SESSION['username'] = $nuovoUsername;
            $messaggio = "Profilo aggiornato con successo.";
            header("Refresh:0");
        } else {
            $messaggio = "Errore durante l'aggiornamento.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>Profilo Utente</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #121212;
      color: #e0e0e0;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 800px;
      margin: 60px auto;
      background-color: #1e1e1e;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.5);
    }

    h1 {
      color: #00ffd5;
      text-align: center;
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-top: 20px;
      font-weight: bold;
    }

    input[type="text"], input[type="email"], input[type="password"] {
      width: 100%;
      padding: 10px;
      border-radius: 5px;
      border: none;
      margin-top: 5px;
      background-color: #2c2c2c;
      color: #fff;
    }

    .info-box {
      background-color: #2c2c2c;
      padding: 15px;
      margin-top: 20px;
      border-left: 4px solid #00ffd5;
      border-radius: 5px;
      font-size: 14px;
    }

    .btn {
      background-color: #00ffd5;
      color: #121212;
      border: none;
      padding: 12px 20px;
      margin-top: 25px;
      cursor: pointer;
      font-weight: bold;
      border-radius: 5px;
    }

    .btn:hover {
      background-color: #00c9aa;
    }

    .message {
      margin-top: 20px;
      color: #00ff88;
      text-align: center;
    }
  </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">
  <h1>Il tuo Profilo</h1>

  <form method="POST">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required value="<?= htmlspecialchars($utente['username']) ?>">

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required value="<?= htmlspecialchars($utente['email']) ?>">

    <label for="password">Nuova Password:</label>
    <input type="password" id="password" name="password" required placeholder="Min. 8 caratteri, 1 maiuscola, 1 numero, 1 simbolo">

    <button class="btn" type="submit">Aggiorna Profilo</button>
  </form>

  <div class="info-box">
    <p><strong>Account creato il:</strong> <?= date('d/m/Y H:i', strtotime($utente['created_at'])) ?></p>
    <p><strong>Ultima modifica:</strong> <?= date('d/m/Y H:i', strtotime($utente['updated_at'])) ?></p>
  </div>

  <?php if ($messaggio): ?>
    <div class="message"><?= htmlspecialchars($messaggio) ?></div>
  <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
</body>
</html>

