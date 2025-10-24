<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Home | Esiah's Universe</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="/manag_access/image.php?file=icon.png">
    <link rel="stylesheet" href="/manag_access/style.php?file=global.css">
    <link rel="stylesheet" href="/manag_access/style.php?file=index.css">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&display=swap" rel="stylesheet">
    <script type="module" src="/private/scripts/main.js"></script>
</head>

<body class="cinematic">
    <?php include 'private/includes/header.php'; ?>

    <main>
        <section class="panel panel--center is-visible" id="panel-hero" data-theme="">
            <div class="container">
                <h1 class="title">Welcome to Esiah’s Universe</h1>
                <p class="subtitle">A journey through code, design, and imagination.</p>
                <div class="btn-group">
                    <a class="btn outline" id="go-last" href="#">Discover my latest projects</a>
                    <a class="btn" href="/blog/">Discover the blog</a>
                </div>
            </div>
        </section>

        <section class="panel work" id="panel-last" data-theme="midnight-red" aria-hidden="true">
            <div class="container">
                <h2>Latest Projects</h2>
                <div class="work__grid">
                    <a class="card work-card" target="_blank" href="https://metzcampus.fr">
                        <img alt="Metz Campus website" src="/manag_access/image.php?file=MC-preview.png">
                        <div class="work-card__body">
                            <h3>Metz Campus</h3>
                            <p>Website crafted for the Metz Campus organization during my first-year internship.</p>
                        </div>
                    </a>
                    <a class="card work-card" href="/Neo-Tokyo-Rush">
                        <img alt="Neo Tokyo Rush cover" src="/manag_access/image.php?file=N-T-R-LvlPre.png">
                        <div class="work-card__body">
                            <h3>Neo Tokyo Rush</h3>
                            <p>Fast-paced PvE action 2D game.</p>
                        </div>
                    </a>
                    <a class="card work-card" href="/projects/e1/">
                        <img alt="1st Year Portfolio cover" src="/manag_access/image.php?file=Portfolio-preview.png">
                        <div class="work-card__body">
                            <h3>First-Year Portfolio</h3>
                            <p>A curated journey through my early experiments and builds.</p>
                        </div>
                    </a>
                </div>
            </div>
        </section>

        <section class="panel panel--center" id="panel-all" data-theme="midnight-red" aria-hidden="true">
            <div class="container">
                <h2>All Projects</h2>
                <p>Browse the full spectrum of my work — web, games, and UI/UX explorations.</p>
                <div class="btn-group">
                    <a class="btn outline" href="#panel-about">Who am I?</a>
                    <a class="btn" href="/projects">See the full list</a>
                </div>
            </div>
        </section>

        <section class="panel panel--center" id="panel-about" data-theme="midnight-red" aria-hidden="true">
            <div class="container">
                <h2>About</h2>
                <p>I’m Esiah — an explorer of code, design, and poetry, building immersive experiences.</p>
                <div class="btn-group">
                    <a class="btn outline" href="#">Back to start</a>
                    <a class="btn" href="/contact">Contact me</a>
                </div>
            </div>
        </section>
    </main>

    <div id="scroll-proxy" aria-hidden="true"></div>
    <?php include 'private/includes/footer.html'; ?>
</body>

</html>