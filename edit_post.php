<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "as_app");
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Recupera il post solo se appartiene all'utente
$stmt = $conn->prepare("SELECT title, content, color FROM posts WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $post_id, $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    $stmt->close();
    header("Location: home.php");
    exit();
}

$stmt->bind_result($title, $content, $color);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_title = trim($_POST['title']);
    $new_content = trim($_POST['content']);
    $new_color = $_POST['color'];

    if (!empty($new_title) && !empty($new_content)) {
        $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ?, color = ? updated_at = NOW() WHERE id = ? AND user_id = ?");
        $stmt->bind_param("sssii", $new_title, $new_content, $new_color, $post_id, $user_id);
        $stmt->execute();
        $stmt->close();

        header("Location: home.php");
        exit();
    } else {
        $error = "Titolo e contenuto non possono essere vuoti.";
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Modifica Post - AS APP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            color: #e0e0e0;
            font-family: 'Segoe UI', sans-serif;
        }

        .card {
            background-color: #1e1e1e;
            border: 1px solid #2c2c2c;
            border-radius: 10px;
        }

        .card-header {
            background-color: #00ffd5;
            color: #121212;
            font-weight: bold;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .form-label {
            color: #cccccc;
        }

        .btn-primary {
            background-color: #00ffd5;
            border: none;
            color: #121212;
            font-weight: bold;
        }

        .btn-primary:hover {
            background-color: #00e6c0;
        }

        .btn-secondary {
            background-color: #333;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #444;
        }

        .alert-danger {
            background-color: #ff4c4c;
            color: #fff;
            border: none;
        }

        a {
            color: #00ffd5;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <h4>Modifica Post</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="title" class="form-label">Titolo</label>
                            <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($title); ?>" required maxlength="50">
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">Contenuto</label>
                            <textarea name="content" class="form-co
                        </div>
                        <div class="mb-3">
                            <label for="color" class="form-label">Colore</label>
                            <input type="color" name="color" class="form-control" value="<?php echo htmlspecialchars($color); ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Salva Modifiche</button>
                        <a href="home.php" class="btn btn-secondary w-100 mt-2">Annulla</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

