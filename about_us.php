<!DOCTYPE html>
<html lang="it">
<head>
  <?php include 'navbar.php'; ?>
  <meta charset="UTF-8">
  <title>Chi siamo - A-S APP</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #121212;
      color: #e0e0e0;
      line-height: 1.6;
    }

    .container {
      max-width: 900px;
      margin: 60px auto;
      padding: 20px;
      background-color: #1e1e1e;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.3);
    }

    h1 {
      color: #00ffd5;
      margin-bottom: 20px;
      font-size: 32px;
      text-align: center;
    }

    h2 {
      margin-top: 30px;
      color: #ffffff;
      font-size: 22px;
    }

    p, ul {
      color: #cccccc;
      font-size: 15px;
    }

    ul {
      padding-left: 20px;
    }

    a {
      color: #00ffd5;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }

    .text-center {
      text-align: center;
    }

    .btn-toggle {
      display: block;
      margin: 20px auto;
      background-color: #00ffd5;
      color: #121212;
      padding: 10px 20px;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
    }

    .extra-info {
      display: none;
      margin-top: 20px;
      background-color: #2a2a2a;
      padding: 20px;
      border-radius: 10px;
    }

    .carousel {
      margin-top: 40px;
    }

    .carousel-item img {
      height: 300px;
      object-fit: cover;
      border-radius: 10px;
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
      background-color: rgba(0, 0, 0, 0.5);
      border-radius: 50%;
    }
  </style>
</head>
<body>

<div class="container">
  <h1><strong>Chi siamo</strong></h1>
  
  <h2>1. Introduzione</h2>
  <p>Siamo Apicella e Squitieri, due studenti appassionati di sviluppo web e tecnologia. Questo sito nasce come progetto congiunto per semplificare la creazione e la gestione di contenuti personalizzati.</p>

  <h2>2. Il nostro obiettivo</h2>
  <p>Vogliamo offrire un'esperienza semplice, intuitiva ed efficace per permettere a chiunque di pubblicare i propri contenuti in maniera immediata e personalizzata.</p>

  <h2>3. Funzionalità principali</h2>
  <ul>
    <li>Creazione di post personalizzati con titolo, contenuto e colore.</li>
    <li>Visualizzazione e gestione dei post.</li>
    <li>Interfaccia responsive e veloce.</li>
    <li>Possibilità di modificare il proprio profilo e gestire l’account.</li>
  </ul>

  <h2 class="text-center">Approfondimento</h2>
  <button class="btn-toggle" id="toggleInfo">Scopri di più</button>

  <div class="extra-info" id="extraInfo">
    <p><strong>Apicella</strong> si dedica principalmente alla logica di backend e alla struttura del database, assicurando efficienza e sicurezza.</p>
    <p><strong>Squitieri</strong> si occupa dell’interfaccia utente e dell’esperienza utente (UI/UX), rendendo il sito esteticamente piacevole e semplice da usare.</p>
  </div>

  <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="images/image1.png" class="d-block w-100" alt="Team Lavoro 1">
      </div>
      <div class="carousel-item">
        <img src="images/image2.jpg" class="d-block w-100" alt="Team Lavoro 2">
      </div>
      <div class="carousel-item">
        <img src="images/image3.png" class="d-block w-100" alt="Team Lavoro 3">
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Precedente</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Successivo</span>
    </button>
  </div>

</div>

<!-- Bootstrap Bundle con JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
  const btn = document.getElementById('toggleInfo');
  const info = document.getElementById('extraInfo');

  btn.addEventListener('click', () => {
    if (info.style.display === "none" || info.style.display === "") {
      info.style.display = "block";
      btn.textContent = "Nascondi informazioni";
    } else {
      info.style.display = "none";
      btn.textContent = "Scopri di più";
    }
  });
</script>

<?php include 'footer.php'; ?>
</body>
</html>

