// === Esiah.dev — Scroll segmenté (phases nettes, panels au seuil) ===
import * as THREE from "https://unpkg.com/three@0.160.0/build/three.module.js";

/**
 * Initialise la logique de caméra et de navigation par scroll.
 * @param {object} S - Objet retourné par initScene() (scene, camera, renderer, etc.)
 */
export function initCamera(S) {
  const { scene, camera, renderer, planetA, planetB, blackHole, lerp, clamp } =
    S;
  const ease = (t) => t * t * (3 - 2 * t);

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
    el.style.pointerEvents = enabled ? "auto" : "none";
    el.toggleAttribute("inert", !enabled);
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

  // === Positions clés ===
  const BH = blackHole.position.clone(); // trou noir

  // === Fonctions de trajectoire ===
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

  function orbitDezoom(
    target,
    p,
    {
      rStart = 550,
      rEnd = 1000,
      elevStart = 220,
      elevEnd = 160,
      sweep = Math.PI * 2.5,
      aStart = Math.PI / 2,
    } = {}
  ) {
    const t = ease(clamp(p, 0, 1));
    const r = lerp(rStart, rEnd, t);
    const elev = lerp(elevStart, elevEnd, t);
    const a = aStart + sweep * t;
    const x = target.x + Math.cos(a) * r;
    const y = target.y + elev;
    const z = target.z + Math.sin(a) * r;

    camera.fov = lerp(60, 75, t);
    camera.updateProjectionMatrix();

    camera.position.set(x, y, z);
    camera.lookAt(target);
  }

  // === Phases (segments de scroll) ===
  const phases = [
    {
      key: "orbit1",
      panelEnter: "hero",
      lenVH: 140,
      update: (p) =>
        orbit(BH, p, { radius: 1100, elev: 120, sweep: Math.PI / 2 }),
    },
    {
      key: "zoom1",
      panelEnter: "last",
      lenVH: 120,
      update: (p) =>
        zoomToward(
          BH,
          new THREE.Vector3(BH.x, BH.y + 160, BH.z + 1100),
          new THREE.Vector3(BH.x, BH.y + 200, BH.z + 650),
          p
        ),
    },
    {
      key: "zoom2",
      panelEnter: "all",
      panelExit: "last",
      lenVH: 120,
      update: (p) =>
        zoomToward(
          BH,
          new THREE.Vector3(BH.x, BH.y + 200, BH.z + 650),
          new THREE.Vector3(BH.x, BH.y + 220, BH.z + 550),
          p
        ),
    },
    {
      key: "unzoomRotation",
      panelEnter: "about",
      panelExit: "all",
      lenVH: 160,
      update: (p) =>
        orbitDezoom(BH, p, {
          rStart: 550,
          rEnd: 1000,
          elevStart: 220,
          elevEnd: 160,
          sweep: Math.PI * 1.5,
          aStart: Math.PI / 2,
        }),
    },
  ];

  // === Orbit des cartes "Last Projects" ===
  const cards = Array.from(document.querySelectorAll("#panel-last .work-card"));
  const GRID = document.querySelector("#panel-last .work__grid");

  function orbitCards(time, intensity = 1) {
    if (!cards.length || !GRID) return;

    const w = GRID.clientWidth;
    const h = GRID.clientHeight;

    const rx = Math.max(180, Math.min(0.36 * w, 360)) * intensity;
    const rz = Math.max(140, Math.min(0.32 * w, 300)) * intensity;
    const bobAmp = Math.min(22, h * 0.04) * intensity;

    const speed = 0.08;
    const a = time * speed * 2 * Math.PI;
    const baseAngles = [0, (2 * Math.PI) / 3, (4 * Math.PI) / 3];

    cards.forEach((card, i) => {
      const ang = a + baseAngles[i];
      const x = Math.cos(ang) * rx;
      const z = Math.sin(ang) * rz;
      const y = Math.sin(ang * 2) * bobAmp;

      const depth01 = (z + rz) / (2 * rz);
      const scale = 0.9 + depth01 * 0.22;
      const alpha = 0.78 + depth01 * 0.22;
      const shadow = 0.18 + depth01 * 0.32;

      card.style.transform = `translate(-50%,-50%) translate3d(${x}px, ${y}px, ${z}px) scale(${scale})`;
      card.style.opacity = alpha.toFixed(3);
      card.style.boxShadow = `0 10px 30px rgba(0,0,0,${shadow.toFixed(3)})`;
      card.style.zIndex = String(100 + Math.round(depth01 * 900));
      card.style.pointerEvents = "auto";
    });
  }

  // === Scroll proxy ===
  const proxy =
    document.getElementById("scroll-proxy") ??
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

  // === Gestion du scroll ===
  let active = -1;
  let localP = 0;
  let scrollY = 0;
  let smoothY = 0;

  function computePhase(y) {
    const vh = innerHeight;
    const yVH = (y / vh) * 100;
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
    return { index: phases.length - 1, p: 1 };
  }

  function applyPhaseChange(idx) {
    if (idx === active) return;
    const prev = phases[active];
    const next = phases[idx];
    if (prev?.panelExit) show(null);
    show(next.panelEnter);
    active = idx;
  }

  // === Boucle principale ===
  function animate() {
    smoothY += (scrollY - smoothY) * 0.15;
    const { index, p } = computePhase(smoothY);
    applyPhaseChange(index);
    localP = p;

    const lastActive = active === 1 && localP > 0.12 && localP < 0.88;
    setLastInteractivity(lastActive);

    phases[index].update(localP);

    planetA.rotation.y += 0.0012;
    planetB.rotation.y += 0.001;
    blackHole.rotation.y += 0.0005;
    if (planetA.children.length) planetA.children[0].rotation.z += 0.0008;
    if (planetB.children.length) planetB.children[0].rotation.z -= 0.0006;

    // Orbit cartes "Last Projects"
    const lastVisible =
      panels.last && panels.last.classList.contains("is-visible");
    if (lastVisible) {
      const tCards = (performance.now() % 60000) / 1000;
      orbitCards(tCards, 1);
    }

    renderer.render(scene, camera);
    requestAnimationFrame(animate);
  }

  requestAnimationFrame(animate);

  addEventListener(
    "scroll",
    () => {
      scrollY = pageYOffset || document.documentElement.scrollTop;
    },
    { passive: true }
  );

  // === Navigation par boutons ===
  setTimeout(() => {
    const btnLast = document.getElementById("go-last");
    if (btnLast) {
      btnLast.addEventListener("click", (e) => {
        e.preventDefault();
        const vh = innerHeight;
        const beforeVH = phases[0].lenVH; // après orbit1
        const offset = ((beforeVH + 20) / 100) * vh;
        window.scrollTo({ top: offset, behavior: "smooth" });
      });
    }

    document.querySelectorAll('a[href="#panel-about"]').forEach((a) => {
      a.addEventListener("click", (e) => {
        e.preventDefault();
        const vh = innerHeight;
        const beforeVH = phases.slice(0, 3).reduce((s, f) => s + f.lenVH, 0);
        const offset = ((beforeVH + 150) / 100) * vh;
        window.scrollTo({ top: offset, behavior: "smooth" });
      });
    });
  }, 1000);

  show("hero");
}
