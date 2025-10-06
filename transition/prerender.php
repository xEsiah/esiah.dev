<?php
/* prerender.php — drop-in include
   Options (définis-les AVANT l'include si tu veux surcharger) :
   $PRERENDER_MS     (int)   durée de l’overlay en ms — default 2000
   $PRERENDER_SCOPE  (string)pattern de prerender (RegExp-like) — default '/*'
   $PRERENDER_BG     (string)fond overlay — default '#0e0e11'
   $PRERENDER_LABEL  (string)texte overlay — default 'Warp jump…'
*/
if (!defined('PRERENDER_INCLUDED')) {
    define('PRERENDER_INCLUDED', true);
    $PRERENDER_MS = isset($PRERENDER_MS) ? (int) $PRERENDER_MS : 2000;
    $PRERENDER_SCOPE = isset($PRERENDER_SCOPE) ? (string) $PRERENDER_SCOPE : '/*';
    $PRERENDER_BG = isset($PRERENDER_BG) ? (string) $PRERENDER_BG : '#0e0e11';
    $PRERENDER_LABEL = isset($PRERENDER_LABEL) ? (string) $PRERENDER_LABEL : 'Warp jump…';
    ?>
    <!-- prerender.php (include safe, idempotent) -->
    <style>
        /* Overlay */
        #page-transition {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: none;
            place-items: center;
            background:
                <?= htmlspecialchars($PRERENDER_BG, ENT_QUOTES) ?>
            ;
            color: #fff;
            font-family: system-ui, Inter, sans-serif;
        }

        #page-transition.is-active {
            display: grid;
        }

        #page-transition .pt-ring,
        #page-transition .pt-ring::before,
        #page-transition .pt-ring::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, .08);
            width: 280px;
            height: 280px;
            animation: ptr
                <?= (int) $PRERENDER_MS ?>
                ms ease-in-out forwards;
        }

        #page-transition .pt-ring::before {
            width: 180px;
            height: 180px;
        }

        #page-transition .pt-ring::after {
            width: 80px;
            height: 80px;
        }

        #page-transition .pt-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #ff3347;
            box-shadow: 0 0 26px rgba(255, 51, 71, .55);
            animation: ptd
                <?= (int) $PRERENDER_MS ?>
                ms ease-in-out infinite;
        }

        #page-transition .pt-label {
            margin-top: 18px;
            opacity: .75;
            letter-spacing: .08em;
            font-size: .9rem;
        }

        @keyframes ptr {
            0% {
                transform: scale(.8);
                opacity: .6
            }

            60% {
                opacity: .9
            }

            100% {
                transform: scale(1.25);
                opacity: 0
            }
        }

        @keyframes ptd {
            0% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(-12px)
            }

            100% {
                transform: translateY(0)
            }
        }

        @media (prefers-reduced-motion: reduce) {

            #page-transition .pt-ring,
            #page-transition .pt-ring::before,
            #page-transition .pt-ring::after,
            #page-transition .pt-dot {
                animation: none;
            }
        }
    </style>

    <div id="page-transition" aria-hidden="true" role="status" aria-live="polite">
        <div class="pt-ring"></div>
        <div class="pt-dot"></div>
        <div class="pt-label"><?= htmlspecialchars($PRERENDER_LABEL, ENT_QUOTES) ?></div>
    </div>

    <script>
        (function () {
            if (window.__PRERENDER_BOOTSTRAPPED__) return;
            window.__PRERENDER_BOOTSTRAPPED__ = true;

            // 1) Inject Speculation Rules (prerender) dynamiquement dans <head>
            try {
                var sr = document.createElement('script');
                sr.type = 'speculationrules';
                sr.text = JSON.stringify({
                    prerender: [{
                        source: 'document',
                        where: { href_matches: <?= json_encode($PRERENDER_SCOPE) ?> },
                        eagerness: 'moderate'
                    }]
                });
                document.head && document.head.appendChild(sr);
            } catch (e) {/* pas grave si non supporté */ }

            var PT = document.getElementById('page-transition');
            var DUR = <?= (int) $PRERENDER_MS ?>; // ms

            function shouldIntercept(a) {
                if (!a) return false;
                if (a.target && a.target !== '_self') return false;
                if (a.hasAttribute('download')) return false;
                var href = a.getAttribute('href') || '';
                if (href.startsWith('mailto:') || href.startsWith('tel:')) return false;
                var url; try { url = new URL(a.href, location.href); } catch (e) { return false; }
                if (url.origin !== location.origin) return false; // même domaine
                if (url.hash && url.pathname === location.pathname) return false; // ancre interne
                return true;
            }

            document.addEventListener('click', function (e) {
                if (e.defaultPrevented) return;
                if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return; // new tab etc.
                var a = e.target.closest && e.target.closest('a[href]');
                if (!shouldIntercept(a)) return;

                e.preventDefault();

                // 2) Active l’overlay immédiatement (cache les flashes/canvas)
                if (PT) PT.classList.add('is-active');

                var to = a.href;
                var start = performance.now();
                var warmup = 150; // laisse le prerender se lancer

                setTimeout(function () {
                    var remain = Math.max(0, DUR - (performance.now() - start));
                    setTimeout(function () { location.href = to; }, remain);
                }, warmup);
            }, { capture: true });

            // 3) En sortie de page : fond solide au cas où
            function solidBg() {
                document.body.style.background = <?= json_encode($PRERENDER_BG) ?>;
            }
            window.addEventListener('beforeunload', solidBg);
            window.addEventListener('pagehide', solidBg);
        })();
    </script>
<?php } ?>