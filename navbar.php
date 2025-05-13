<style>
  .navbar {
    background: linear-gradient(135deg, #1f1f1f, #2b2b2b);
    color: #e0e0e0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 30px;
    font-family: 'Segoe UI', sans-serif;
    box-shadow: 0 2px 10px rgba(0,0,0,0.3);
    position: sticky;
    top: 0;
    z-index: 1000;
  }

  .navbar-logo {
    font-size: 24px;
    font-weight: bold;
    color: #00ffd5;
    text-decoration: none;
  }

  .navbar-links {
    display: flex;
    gap: 20px;
  }

  .navbar-links a {
    color: #e0e0e0;
    text-decoration: none;
    font-size: 15px;
    transition: color 0.3s;
  }

  .navbar-links a:hover {
    color: #00ffd5;
  }

  .navbar-user {
    display: flex;
    align-items: center;
    gap: 15px;
  }

  .navbar-user i {
    font-size: 18px;
    cursor: pointer;
    color: #e0e0e0;
    transition: color 0.3s;
  }

  .navbar-user i:hover {
    color: #00ffd5;
  }

  @media (max-width: 768px) {
    .navbar {
      flex-direction: column;
      align-items: flex-start;
      padding: 15px;
    }

    .navbar-links {
      flex-direction: column;
      width: 100%;
      margin-top: 10px;
    }

    .navbar-links a {
      padding: 5px 0;
    }

    .navbar-user {
      margin-top: 10px;
    }
  }
</style>

<!-- Font Awesome (icone utente, logout, ecc) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<nav class="navbar">
  <a class="navbar-logo" href="home.php">A-S APP</a>

  <div class="navbar-links">
    <a href="home.php">Home</a>
    <a href="about_us.php">Chi siamo</a>
    <a href="termini.php">Termini</a>
  </div>

  <div class="navbar-user">
    <a href="profilo.php"><i class="fas fa-user"></i></a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i></a>
  </div>
</nav>

