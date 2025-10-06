// Esiah.dev — Scroll segmenté (phases nettes, panels au seuil)
(function () {
  function onReady(fn) {
    document.readyState !== "loading"
      ? fn()
      : document.addEventListener("DOMContentLoaded", fn);
  }
  function waitScene(cb) {
    if (window.EsiahScene) cb(window.EsiahScene);
    else requestAnimationFrame(() => waitScene(cb));
  }
  onReady(() => waitScene(init));

  function init(S) {
    const { scene, camera, renderer, planetA, planetB, planetC } = S;
    const { lerp, clamp } = THREE.MathUtils;
    const ease = (t) => t * t * (3 - 2 * t);

    // --- Panels
    const htmlEl = document.documentElement;
    const panels = {
      hero: document.getElementById("panel-hero"),
      last: document.getElementById("panel-last"),
      all: document.getElementById("panel-all"),
      about: document.getElementById("panel-about"),
      contact: document.getElementById("panel-contact"),
    };
    function setLastInteractivity(enabled) {
      const el = panels.last;
      if (!el) return;
      // Désactive les clics + focus clavier quand non interactif
      el.style.pointerEvents = enabled ? "auto" : "none";
      el.toggleAttribute("inert", !enabled); // coupe la navigation focus (si supporté)
      el.querySelectorAll("a,button,input,select,textarea,[tabindex]").forEach(
        (n) => (n.tabIndex = enabled ? 0 : -1)
      );
    }

    function show(id) {
      Object.values(panels).forEach(
        (el) => el && el.classList.remove("is-visible")
      );
      if (!id) return;
      const el = panels[id];
      const theme = el?.getAttribute("data-theme");
      theme
        ? htmlEl.setAttribute("data-theme", theme)
        : htmlEl.removeAttribute("data-theme");
      el?.classList.add("is-visible");
    }

    // --- Trajectoires
    const BH = planetC.position.clone(); // centre / trou noir
    const PB = planetB.position.clone(); // planète cible

    function orbit(
      target,
      p,
      { radius = 800, elev = 160, sweep = Math.PI / 2 } = {}
    ) {
      const a = sweep * ease(clamp(p, 0, 1));
      camera.position.set(
        target.x + Math.cos(a) * radius,
        target.y + elev * p,
        target.z + Math.sin(a) * radius
      );
      camera.lookAt(target);
    }
    function zoomToward(target, from, to, p) {
      camera.position.set(
        lerp(from.x, to.x, ease(p)),
        lerp(from.y, to.y, ease(p)),
        lerp(from.z, to.z, ease(p))
      );
      camera.lookAt(target);
    }
    function travel(from, to, p) {
      camera.position.set(
        lerp(from.x, to.x, ease(p)),
        lerp(from.y, to.y, ease(p)),
        lerp(from.z, to.z, ease(p))
      );
      camera.lookAt(to);
    }
    function orbitDezoom(
      target,
      p,
      {
        rStart = 550, // distance initiale = 550 (fin de zoom2)
        rEnd = 1000, // dézoom
        elevStart = 220, // y de fin zoom2
        elevEnd = 160,
        sweep = Math.PI * 2.5,
        aStart = Math.PI / 2, // angle initial pour être sur l’axe +Z (Δx=0, Δz=+rStart)
      } = {}
    ) {
      const pp = THREE.MathUtils.clamp(p, 0, 1);
      const t = pp * pp * (3 - 2 * pp); // ease
      const r = THREE.MathUtils.lerp(rStart, rEnd, t);
      const elev = THREE.MathUtils.lerp(elevStart, elevEnd, t);
      const a = aStart + sweep * t; // démarre où la phase précédente s'arrête
      const x = target.x + Math.cos(a) * r;
      const y = target.y + elev;
      const z = target.z + Math.sin(a) * r;

      const fov = THREE.MathUtils.lerp(60, 75, t); // dézoom ciné (optionnel)
      camera.fov = fov;
      camera.updateProjectionMatrix();

      camera.position.set(x, y, z);
      camera.lookAt(target);
    }
    // angle dans [0, 2π)

    // --- Phases nettes (ordre & panneaux)
    // lenVH = durée de phase en hauteurs de viewport → segments égaux et prévisibles
    const phases = [
      {
        key: "orbit1", // Phase 1 : ORBITE autour du trou noir
        panelEnter: "hero", // → Affiche la section "hero" en entrant dans la phase
        panelExit: null, // → Rien à masquer explicitement en sortie
        lenVH: 140, // → Longueur de la phase = 140 hauteurs de viewport (scroll)
        update: (
          p // p ∈ [0..1] : progression locale dans la phase
        ) => orbit(BH, p, { radius: 1100, elev: 120, sweep: Math.PI / 2 }), // orbite large, légère montée
      },

      {
        key: "zoom1", // Phase 2 : ZOOM LÉGER vers le trou noir
        panelEnter: "last", // → Affiche "last projects"
        panelExit: null, // → Pas de masquage forcé ici (hero se coupe car nouvelle phase)
        lenVH: 120, // → 120vh de scroll pour ce zoom
        update: (p) =>
          zoomToward(
            // rapprochement de la caméra vers le centre BH
            BH,
            new THREE.Vector3(BH.x, BH.y + 160, BH.z + 1100), // position de départ du zoom
            new THREE.Vector3(BH.x, BH.y + 200, BH.z + 650), // position d'arrivée (un peu plus près)
            p
          ),
      },

      {
        key: "zoom2", // Phase 3 : ZOOM PLUS PROFOND (toujours sur le trou noir)
        panelEnter: "all", // → Affiche "all projects"
        panelExit: "last", // → Masque explicitement "last projects" à l’entrée de cette phase
        lenVH: 120, // → 120vh pour ce second zoom
        update: (p) =>
          zoomToward(
            BH,
            new THREE.Vector3(BH.x, BH.y + 200, BH.z + 650), // repart de la fin de zoom1
            new THREE.Vector3(BH.x, BH.y + 220, BH.z + 550), // se rapproche nettement
            p
          ),
      },

      {
        key: "unzoomRotation", // Phase 4 : dezoom + rotation
        panelEnter: "about",
        panelExit: "all",
        lenVH: 160,
        update: (p) =>
          orbitDezoom(BH, p, {
            rStart: 550, // = distance à la fin de zoom2
            rEnd: 1000,
            elevStart: 220, // = y à la fin de zoom2
            elevEnd: 160,
            sweep: Math.PI * 1.5,
            aStart: Math.PI / 2, // place la caméra sur l’axe +Z au départ
          }),
      },
    ];
    // fait défiler jusqu'au début d'une phase (index = 0..phases.length-1)
    // Fait défiler jusqu'au début d'une phase + un offset "dans" la phase
    function scrollToPhase(index, { intoVH = 12 } = {}) {
      const vh = innerHeight;

      // somme des lenVH avant la phase
      const beforeVH = phases.slice(0, index).reduce((s, f) => s + f.lenVH, 0);

      // limite l'offset pour ne pas dépasser la phase
      const safeInto = Math.max(
        0,
        Math.min(intoVH, (phases[index]?.lenVH || 0) - 2)
      );

      const yPx = ((beforeVH + safeInto) / 100) * vh;
      window.scrollTo({ top: yPx, behavior: "smooth" });
    }

    // bouton -> phase 1 ("zoom1" qui affiche #panel-last), avec +12vh dans la phase
    document.getElementById("go-last")?.addEventListener("click", (e) => {
      e.preventDefault();
      scrollToPhase(1, { intoVH: 12 }); // augmente ce nombre si tu veux "encore un peu"
    });
    // Lien "Qui suis-je ?" -> début de la phase 3 ("unzoomRotation" qui affiche #panel-about)
    document.querySelectorAll('a[href="#panel-about"]').forEach((a) => {
      a.addEventListener("click", (e) => {
        e.preventDefault();
        scrollToPhase(3, { intoVH: 80 }); // pousse un peu "dans" la phase; ajuste 8..16 à ton goût
      });
    });

    // Orbit lent, sans self-rotation, scale par profondeur, bordure teintée
    const cards = Array.from(
      document.querySelectorAll("#panel-last .work-card")
    );
    const GRID = document.querySelector("#panel-last .work__grid");

    function orbitCards(time, intensity = 1) {
      if (!cards.length || !GRID) return;

      const w = GRID.clientWidth;
      const h = GRID.clientHeight;

      const rx = Math.max(180, Math.min(0.36 * w, 360)) * intensity;
      const rz = Math.max(140, Math.min(0.32 * w, 300)) * intensity;
      const bobAmp = Math.min(22, h * 0.04) * intensity;

      const speed = 0.08; // <<< beaucoup moins rapide (tours/seconde)
      const a = time * speed * 2 * Math.PI;
      const baseAngles = [0, (2 * Math.PI) / 3, (4 * Math.PI) / 3];

      cards.forEach((card, i) => {
        const ang = a + baseAngles[i];
        const x = Math.cos(ang) * rx;
        const z = Math.sin(ang) * rz; // profondeur
        const y = Math.sin(ang * 2) * bobAmp;

        const depth01 = (z + rz) / (2 * rz); // 0..1 (loin→près)
        const scale = 0.9 + depth01 * 0.22; // ~0.9 → ~1.12
        const alpha = 0.78 + depth01 * 0.22; // 0.78 → 1.0
        const shadow = 0.18 + depth01 * 0.32; // ombre selon proximité

        card.style.transform = `translate(-50%,-50%) translate3d(${x}px, ${y}px, ${z}px) scale(${scale})`;
        card.style.opacity = alpha.toFixed(3);
        card.style.boxShadow = `0 10px 30px rgba(0,0,0,${shadow.toFixed(3)})`;
        card.style.zIndex = String(100 + Math.round(depth01 * 900));

        // bordure légèrement plus vive quand la carte est proche
        const nearTint = 20 + Math.round(depth01 * 40); // 20%→60% du texte
        card.style.borderColor = `color-mix(in oklab, var(--clr-text) ${nearTint}%, transparent)`;

        // plus de restriction de clic : toutes restent cliquables
        card.style.pointerEvents = "auto";
      });
    }

    // --- Scroll proxy height = somme des phases
    const proxy =
      document.getElementById("scroll-proxy") ||
      (() => {
        const d = document.createElement("div");
        d.id = "scroll-proxy";
        document.body.appendChild(d);
        return d;
      })();
    function setProxyHeight() {
      const totalVH = phases.reduce((s, f) => s + f.lenVH, 0);
      proxy.style.height = `${totalVH}vh`;
    }
    setProxyHeight();
    addEventListener("resize", setProxyHeight);

    // --- Trouver phase courante à partir du scroll
    let active = -1;
    let localP = 0; // progression locale [0..1] de la phase
    let scrollY = 0; // cible
    let smoothY = 0; // lissé pour enlever le jitter

    function computePhase(y) {
      const vh = innerHeight;
      const yVH = (y / vh) * 100; // en "vh"
      let acc = 0;
      for (let i = 0; i < phases.length; i++) {
        const start = acc;
        const end = acc + phases[i].lenVH;
        if (yVH >= start && yVH < end) {
          const p = (yVH - start) / phases[i].lenVH;
          return { index: i, p: clamp(p, 0, 1) };
        }
        acc = end;
      }
      // tout en bas
      return { index: phases.length - 1, p: 1 };
    }

    // --- Entrées/sorties de phase → affichage panels net
    function applyPhaseChange(idx) {
      if (idx === active) return;
      const prev = phases[active];
      const next = phases[idx];
      if (prev?.panelExit) show(null); // masquage fort si défini
      show(next.panelEnter);
      active = idx;
    }
    let tCards0 = performance.now();

    // --- Boucle de rendu
    function animate() {
      // lissage vers scrollY (évite saccades mais reste fidèle)
      smoothY += (scrollY - smoothY) * 0.15;

      const { index, p } = computePhase(smoothY);
      applyPhaseChange(index);
      localP = p;
      // Actif uniquement au centre de la phase 1 ("zoom1" = Last projects)
      const lastActive = typeof active !== "undefined" && active === 1;
      const interactive = lastActive && localP > 0.12 && localP < 0.88; // fenêtre "cliquable"
      setLastInteractivity(interactive);

      // cam path de la phase
      phases[index].update(localP);

      // rotations d'ambiance
      planetA.rotation.y += 0.0012;
      planetB.rotation.y += 0.001;
      planetC.rotation.y += 0.0005;
      if (planetA.children.length) planetA.children[0].rotation.z += 0.0008;
      if (planetB.children.length) planetB.children[0].rotation.z -= 0.0006;

      renderer.render(scene, camera);
      // animation orbit des cartes quand le panel Last est affiché
      const lastVisible =
        panels.last && panels.last.classList.contains("is-visible");
      if (lastVisible) {
        const tCards = (performance.now() - tCards0) / 1000; // secondes
        // intensité 1 en plein centre de phase, un peu plus faible aux bords (optionnel)
        let intensity = 1;
        try {
          // si tu as la version "scroll segmenté": phase index 1 = "zoom1"
          if (typeof active !== "undefined" && typeof localP !== "undefined") {
            intensity =
              active === 1 ? 0.8 + 0.2 * Math.sin(localP * Math.PI) : 0.7;
          }
        } catch (e) {}
        orbitCards(tCards, intensity);
      }

      requestAnimationFrame(animate);
    }
    requestAnimationFrame(animate);

    // --- Listener scroll (utilisateur maître du voyage)
    addEventListener(
      "scroll",
      () => {
        scrollY = scrollY = pageYOffset || document.documentElement.scrollTop;
      },
      { passive: true }
    );

    // Init affichage
    show("hero");
  }
})();
