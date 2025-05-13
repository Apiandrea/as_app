<style>
  .footer {
    background: linear-gradient(135deg, #1f1f1f, #2b2b2b);
    color: #e0e0e0;
    padding: 40px 20px 20px;
    font-family: 'Segoe UI', sans-serif;
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.2);
  }

  .footer-inner {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: flex-start;
    max-width: 1200px;
    margin: auto;
    gap: 20px;
  }

  .footer-left h2 {
    color: #00ffd5;
    font-size: 24px;
    margin-bottom: 10px;
  }

  .footer-left p {
    color: #aaa;
    font-size: 14px;
  }

  .footer-center {
    display: flex;
    flex-direction: column;
    gap: 8px;
  }

  .footer-center a {
    color: #ccc;
    text-decoration: none;
    font-size: 14px;
    transition: color 0.3s;
  }

  .footer-center a:hover {
    color: #00ffd5;
  }

  .footer-right a {
    color: #ccc;
    font-size: 18px;
    margin-right: 12px;
    transition: color 0.3s;
  }

  .footer-right a:hover {
    color: #00ffd5;
  }

  .footer-bottom {
    text-align: center;
    font-size: 13px;
    color: #777;
    margin-top: 30px;
    border-top: 1px solid #333;
    padding-top: 15px;
  }

  @media (max-width: 768px) {
    .footer-inner {
      flex-direction: column;
      align-items: center;
      text-align: center;
    }

    .footer-center {
      flex-direction: row;
      flex-wrap: wrap;
      justify-content: center;
      gap: 15px;
      margin: 20px 0;
    }
  }
</style>

<!-- Font Awesome (per icone social) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<footer class="footer">
  <div class="footer-inner">
    <div class="footer-left">
      <h2>A-S APP</h2>
      <p>Il tuo social smart e veloce.</p>
    </div>

    <div class="footer-center">
      <a href="about_us.php">About Us</a>
      <a href="termini.php">Termini</a>
    </div>

    <div class="footer-right">
      <a href="#"><i class="fab fa-instagram"></i></a>
      <a href="https://github.com/Apiandrea"><i class="fab fa-github"></i></a>
      <a href="#"><i class="fab fa-x-twitter"></i></a>
      <a href="#"><i class="fab fa-linkedin-in"></i></a>
    </div>
  </div>

  <div class="footer-bottom">
    <p>&copy; 2025 A-S APP — Tutti i diritti riservati</p>
  </div>
</footer>

