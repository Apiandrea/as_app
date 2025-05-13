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

$stmt = $conn->prepare("SELECT is_admin FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($is_admin);
$stmt->fetch();
$stmt->close();
$_SESSION['is_admin'] = $is_admin;

if (isset($_POST['delete_post_id']) && ($_SESSION['is_admin'] || isset($_SESSION['username']))) {
    $delete_id = intval($_POST['delete_post_id']);

    // Controlla che l'utente sia admin o autore del post
    $stmt = $conn->prepare("SELECT user_id FROM posts WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->bind_result($author_id);
    $stmt->fetch();
    $stmt->close();

    if ($_SESSION['is_admin'] || $author_id == $user_id) {
        $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
        $stmt->close();
        header("Location: home.php");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $color = $_POST['color'];

    if (!empty($title) && !empty($content)) {
        $stmt = $conn->prepare("INSERT INTO posts (user_id, title, content, color) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $user_id, $title, $content, $color);
        $stmt->execute();
        $stmt->close();
        header("Location: home.php");
        exit();
    } else {
        $error = "Il titolo e il contenuto non possono essere vuoti.";
    }
}

$postsPerPage = 5;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $postsPerPage;

$result = $conn->query("SELECT p.id, p.title, p.content, p.color, u.username, p.created_at FROM posts p JOIN users u ON p.user_id = u.id ORDER BY p.id DESC LIMIT $postsPerPage OFFSET $offset");
$countResult = $conn->query("SELECT COUNT(*) AS total FROM posts");
$totalPosts = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalPosts / $postsPerPage);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Home Page - AS APP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            color: #e0e0e0;
            font-family: 'Segoe UI', sans-serif;
        }

        h2 {
            color: #00ffd5;
            margin-bottom: 30px;
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

        .btn-danger {
            background-color: #ff4c4c;
            border: none;
            color: white;
        }

        .btn-danger:hover {
            background-color: #ff1f1f;
        }

        .post {
            background-color: #2a2a2a;
            border-radius: 8px;
            border-left: 10px solid;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        .post h5,
        .post p {
            color: #e0e0e0;
        }

        .text-muted {
            color: #999 !important;
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
    <h2 class="text-center">Benvenuto, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
    <div class="row">
        <!-- Crea Post -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header text-center">
                    Crea Post
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Titolo</label>
                            <input type="text" name="title" class="form-control" maxlength="50" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contenuto</label>
                            <textarea name="content" class="form-control" rows="5" maxlength="200" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Colore</label>
                            <input type="color" name="color" class="form-control form-control-color" value="#00b4d8" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Pubblica</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Post Recenti -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header text-center">
                    Post Recenti
                </div>
                <div class="card-body">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="post" style="border-left-color: <?php echo htmlspecialchars($row['color']); ?>;">
                            <h5><?php echo htmlspecialchars($row['title']); ?></h5>
                            <small class="text-muted">di <?php echo htmlspecialchars($row['username']); ?> - <?php echo htmlspecialchars($row['created_at']); ?></small>
                            <p class="mt-2"><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>

                            <?php if ($_SESSION['is_admin'] || $row['username'] === $_SESSION['username']): ?>
                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    <form method="POST" onsubmit="return confirm('Sei sicuro di voler eliminare questo post?');">
                                        <input type="hidden" name="delete_post_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash-alt"></i> Elimina
                                        </button>
                                    </form>
                                    <a href="edit_post.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-edit"></i> Modifica
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>

                    <!-- Paginazione -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?php echo $page - 1; ?>" class="btn btn-secondary">⬅️</a>
                        <?php else: ?>
                            <div></div>
                        <?php endif; ?>

                        <span style="color: white;">Pagina <?php echo $page; ?> di <?php echo $totalPages; ?></span>

                        <?php if ($page < $totalPages): ?>
                            <a href="?page=<?php echo $page + 1; ?>" class="btn btn-secondary">➡️</a>
                        <?php else: ?>
                            <div></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

