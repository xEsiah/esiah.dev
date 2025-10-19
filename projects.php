<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Projects | Esiah's Universe</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="icon" href="/manag_access/image.php?file=icon.jpg">
    <link rel="stylesheet" href="/manag_access/style.php?file=global.css">
    <link rel="stylesheet" href="/manag_access/style.php?file=projects.css">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&display=swap" rel="stylesheet">
    <script type="module" src="/private/scripts/main.js"></script>
</head>

<body>
    <?php include 'private/includes/header.php'; ?>
    <main>
        <section class="projects-panel">
            <h1 class="title">Projects</h1>
            <p class="subtitle">Browse the different stages of my work.</p>
            <div class="btn-group">
                <a class="btn" target="_blank" href="https://github.com/xEsiah">Learn about me</a>
                <a class="btn outline" href="/contact.php">Contact me</a>
            </div>
        </section>
        <section class="panel container">
            <div class="projects-tabs">
                <input type="radio" name="tabs-vert" id="tab-pro" checked>
                <input type="radio" name="tabs-vert" id="tab-per">
                <div class="projects-tabs-layout">
                    <div class="projects-tab-bar">
                        <label class="projects-tab" for="tab-pro">Professional</label>
                        <label class="projects-tab" for="tab-per">Personal</label>
                    </div>
                    <div class="projects-tab-panels">
                        <div class="projects-tab-panel" id="projects-panel-pro">
                            <a class="projects-card" href="/projects/e1/">
                                <h3>E1 ESTIAM</h3>
                                <p>Portfolio that gathers all my first year's projects</p>
                            </a>
                            <a class="projects-card" href="/projects/">
                                <h3>E2 ESTIAM</h3>
                                <p>Work in progress...</p>
                            </a>
                            <a class="projects-card" href="/projects/">
                                <h3>Building...</h3>
                                <p>Work in progress...</p>
                            </a>
                            <a class=" projects-card" href="/projects/">
                                <h3>Building...</h3>
                                <p>Work in progress...</p>
                            </a>
                            <a class=" projects-card" href="/projects/">
                                <h3>Building...</h3>
                                <p>Work in progress...</p>
                            </a>
                        </div>
                        <div class=" projects-tab-panel" id="projects-panel-per">
                            <a class="projects-card" href="/Neo-Tokyo-Rush/">
                                <h3>Neo Tokyo Rush</h3>
                                <p>Cyberpunk-inspired beat ’em All game</p>
                            </a>
                            <a class="projects-card" href="/Echoes-of-the-Last-Stop/">
                                <h3>Echoes of the Last Stop</h3>
                                <p>Psychological horror game project with my partner.</p>
                            </a>
                            <a class=" projects-card" href="/projects/">
                                <h3>Building...</h3>
                                <p>Work in progress...</p>
                            </a>
                            <a class=" projects-card" href="/projects/">
                                <h3>Building...</h3>
                                <p>Work in progress...</p>
                            </a>
                            <a class=" projects-card" href="/projects/">
                                <h3>Building...</h3>
                                <p>Work in progress...</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="panel">
            <div class="container">
                <h2>Contact</h2>
                <p>Reach me at <a href="mailto:contact@esiah.dev">contact@esiah.dev</a></p>
            </div>
        </section>
    </main>
    <?php include 'private/includes/footer.html'; ?>
</body>

</html>