<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Portfolio Numérique</title>
  <link rel="icon" href="images/icon.png">
  <script src="hoverJS.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="styles/index.css">
</head>

<body>
  <!-- En-tête du portfolio -->
  <header>
    <div class="profile">
      <a href="/projects/" class="back-btn">Back to projects list</a>
      <h1>DUPRE THEO</h1>
      <p id="honorific_title">Explorateur Numérique</p>
      <img class="pfp" src="images/dupre_theo.jpg" alt="Photo de profil">
    </div>
  </header>
  <div class="margin_body">
    <!-- Section Projets -->
    <section id="projects">
      <h2>Mes Projets</h2>

      <div class="project-grid">
        <a href="https://firsthtmlproject-xesiahs-projects.vercel.app" class="project-card" target="_blank">
          <div class="project-info">
            <h3>Ascending Legends</h3>
            <p>
              Premier projet en HTML/CSS déployé grâce à Vercel pour témoigner
              de mes débuts en développement web.
            </p>
          </div>
          <div class="project-icons">
            <img src="images/icon_html.png" alt="icon_html">
            <img src="images/icon_css.png" alt="icon_css">
          </div>
        </a>
        <a href="portfolio-wordpress/" class="project-card">
          <div class="project-info">
            <h3>Portfolio No Code</h3>
            <p>Un Portfolio conçu avec Wordpress hébergé localement.</p>
          </div>
          <div class="project-icons">
            <img src="images/icon_wordpress.png" alt="icon_wordpress">
            <img src="images/icon_wamp.png" alt="icon_wamp">
          </div>
        </a>
        <a href="hackathon-wordpress/" class="project-card">
          <div class="project-info">
            <h3>Hackathon No Code</h3>
            <p>Un site vitrine conçu avec Wordpress hébergé localement.</p>
          </div>
          <div class="project-icons">
            <img src="images/icon_wordpress.png" alt="icon_wordpress">
            <img src="images/icon_wamp.png" alt="icon_wamp">
          </div>
        </a>
        <a href="https://github.com/xEsiah/Labyrinthe" class="project-card" target="_blank">
          <div class="project-info">
            <h3>Labyrinthe</h3>
            <p>
              Un jeu de labyrinthe événementiel conçu en Python avec la
              bibliothèque tkinter et publié sur GitHub.
            </p>
          </div>
          <div class="project-icons">
            <img src="images/icon_python.png" alt="icon_python">
            <img src="images/icon_github.png" alt="icon_github">
          </div>
        </a>
        <a href="hackathon-arduino/" class="project-card">
          <div class="project-info">
            <h3>Hackathon IoT</h3>
            <p>
              Un panneau de signalisation connecté conçu avec les modules
              Arduino relié à un site vitrine traquant la position GPS de ce
              dernier.
            </p>
          </div>
          <div class="project-icons">
            <img src="images/icon_arduino.png" alt="icon_arduino">
            <img src="images/icon_c++.png" alt="icon_c++">
          </div>
        </a>
        <a href="https://projet-php-blog.onrender.com" class="project-card" target="_blank">
          <div class="project-info">
            <h3>Esiah's Corner</h3>
            <p>Un blog conçu avec PHP et hébergé sur Render.</p>
          </div>
          <div class="project-icons">
            <img src="images/icon_php.png" alt="icon_php">
            <img src="images/icon_render.png" alt="icon_render">
          </div>
        </a>
        <a href="https://metzcampus.fr" class="project-card" target="_blank">
          <div class="project-info">
            <h3>Metz Campus</h3>
            <p>
              Projet de migration et de refonte du site web de Metz Campus à l'occasion de mon stage de fin d'année.
            </p>
          </div>
          <div class="project-icons">
            <img src="images/icon_hostinger.png" alt="icon_hostinger">
            <img src="images/icon_wordpress.png" alt="icon_wordpress">
          </div>
        </a>
      </div>
    </section>

    <!-- Section Compétences -->
    <h2>Mes Compétences</h2>
    <section id="skills">
      <ul>
        <li>HTML CSS 🎨</li>
        <li id="JS" onmouseover="showSmiley()" onmouseout="resetText()">
          JavaScript ⚡
        </li>
        <li>PHP 🌐</li>
        <li>WordPress 🖋️</li>

        <!-- Langages de programmation -->
        <li>Python 🐍</li>
        <li>C C++ 🖥️</li>
        <li>C# Unity 🎮</li>
        <li>Arduino ⚙️</li>

        <!-- Bases de données -->
        <li>MySQL 🐬</li>
        <li>DBeaver 🛢️</li>

        <!-- Outils -->
        <li>GitHub 🐙</li>
      </ul>
    </section>
  </div>
  <!-- Section Contact -->
  <footer>
    <h2>Contact</h2>
    <div class="icons_liens">
      <div class="social-icons" style="display: flex; gap: 15px">
        <a href="mailto:contact@esiah.dev?subject=Demande d'information&body=Bonjour Théo," class="liens_footer"
          target="_blank">
          <img src="images/icon_mail.png" alt="Mail" class="icon" />
        </a>

        <a href="https://github.com/xEsiah" class="liens_footer" target="_blank">
          <img src="images/icon_github.png" alt="GitHub" class="icon">
        </a>

        <a href="https://www.linkedin.com/in/xesiah/" class="liens_footer" target="_blank">
          <img src="images/icon_linkedin.png" alt="LinkedIn" class="icon">
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