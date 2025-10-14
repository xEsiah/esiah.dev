<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Neo Tokyo Rush | Esiah's Universe</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="/manag_access/image.php?file=icon.jpg">
    <link rel="stylesheet" href="/manag_access/style.php?file=global.css">
    <link rel="stylesheet" href="/manag_access/style.php?file=ntr.css">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&display=swap" rel="stylesheet">
</head>

<body>
    <?php include 'private/includes/header.php'; ?>

    <main>
        <section class="panel hero container" data-bg="">
            <h1 class="title">Neo Tokyo Rush</h1>
            <p class="subtitle">PvE cyberpunk game.</p>
            <div class="btn-group">
                <a class="btn" href="/projects/">Back to projects</a>
                <a class="btn outline" href="/contact/">Contact me</a>
            </div>
        </section>
        <section class="section NTR-container">
            <article class="NTR-card">
                <h2>Overview</h2>
                <div class="ntr-gallery">
                    <figure>
                        <figcaption>Main menu</figcaption>
                        <img alt="Neo Tokyo Rush Main Menu" src="/manag_access/image.php?file=N-T-R-Menu.png">
                    </figure>

                    <figure>
                        <figcaption>Preview of level 1</figcaption>
                        <img alt="Neo Tokyo Rush Level 1 Preview" src="/manag_access/image.php?file=N-T-R-LvlPre.png">
                    </figure>
                </div>
                <a class="btn" href="/private/data/downloads/ntr-download.php">Download the game</a>

                <p class="subtitle">Downloads: <span id="dl-count">—</span></p>
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        fetch('/private/data/downloads/ntr-count.php', { cache: 'no-store' })
                            .then(r => r.json())
                            .then(d => { document.getElementById('dl-count').textContent = (d.downloads ?? 0).toLocaleString(); })
                            .catch(() => { document.getElementById('dl-count').textContent = '0'; });
                    });
                </script>

            </article>

        </section>
    </main>

    <?php include 'private/includes/footer.html'; ?>
</body>

</html>