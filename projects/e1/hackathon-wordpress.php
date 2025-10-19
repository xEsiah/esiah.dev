<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Portfolio Numerique - Hackathon Wordpress</title>
  <link rel="icon" href="/projects/e1/images/icon.png">
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/projects/e1/styles/index.css">
  <link rel="stylesheet" href="/projects/e1/styles/pages_secondaires.css">
</head>
<?php include 'private/includes/header.html'; ?>

<body>
  <header>
    <div class="profile">
      <a href="/projects/" class="back-btn">Back to projects list</a>
      <h1>DUPRE THEO</h1>
      <p id="honorific_title">Explorateur Numérique</p>
    </div>
  </header>
  <div class="margin_body">
    <div class="video-container">
      <h2>ESTIAM Link'Up</h2>
      <video src="/projects/e1/videos/Hackathon Wordpress.mp4" title="Présentation Hackathon Wordpress"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
        allowfullscreen></video>
    </div>
    <a id="centrer_retour_index" href="/projects/e1">Retour au portfolio</a>
  </div>
  <footer>
    <h2>Contact</h2>
    <div class="icons_liens">
      <div class="social-icons" style="display: flex; gap: 15px">
        <a href="mailto:contact@esiah.dev?subject=Demande d'information&body=Bonjour Théo," class="liens_footer"
          target="_blank">
          <img src="/projects/e1/images/icon_mail.png" alt="Mail" class="icon" />
        </a>

        <a href="https://github.com/xEsiah" class="liens_footer" target="_blank">
          <img src="/projects/e1/images/icon_github.png" alt="GitHub" class="icon">
        </a>

        <a href="https://www.linkedin.com/in/xesiah/" class="liens_footer" target="_blank">
          <img src="/projects/e1/images/icon_linkedin.png" alt="LinkedIn" class="icon">
        </a>
      </div>
    </div>
    <p id="about_email">
      Envoie d'email automatisé uniquement supporté si une messagerie par
      défaut est définie
    </p>
  </footer>
</body>

</html>