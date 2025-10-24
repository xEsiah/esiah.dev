// === Esiah.dev — Génération espace 3D (Soleil + ombres dynamiques) ===
export function initScene(THREE) {
  if (document.querySelector("canvas.threejs-bg")) {
    console.warn("✅ Espace 3D déjà initialisé — on évite le doublon");
    return;
  }

  const { lerp, clamp } = THREE.MathUtils;
  const ease = (t) => t * t * (3 - 2 * t);

  // ---------- Scene & renderer
  const scene = new THREE.Scene();
  scene.fog = new THREE.FogExp2(0x000008, 0.00018);

  const camera = new THREE.PerspectiveCamera(
    75,
    innerWidth / innerHeight,
    0.1,
    8000
  );
  camera.position.set(10000, 10000, 10000);

  const renderer = new THREE.WebGLRenderer({ antialias: true });
  renderer.outputColorSpace = THREE.SRGBColorSpace;
  renderer.shadowMap.enabled = true;
  renderer.shadowMap.type = THREE.PCFSoftShadowMap;
  renderer.setSize(innerWidth, innerHeight);
  renderer.setPixelRatio(devicePixelRatio);
  renderer.domElement.classList.add("threejs-bg");
  renderer.domElement.style.position = "fixed";
  renderer.domElement.style.inset = "0";
  renderer.domElement.style.zIndex = "-1";
  document.body.appendChild(renderer.domElement);
  requestAnimationFrame(() => document.body.classList.add("ready"));

  // ---------- Étoiles (fond)
  (function makeStars() {
    const count = 8000;
    const geo = new THREE.BufferGeometry();
    const pos = new Float32Array(count * 3);
    for (let i = 0; i < count; i++) {
      pos[i * 3 + 0] = (Math.random() - 0.5) * 6000;
      pos[i * 3 + 1] = (Math.random() - 0.5) * 6000;
      pos[i * 3 + 2] = (Math.random() - 0.5) * 6000;
    }
    geo.setAttribute("position", new THREE.BufferAttribute(pos, 3));
    const mat = new THREE.PointsMaterial({
      size: 1.2,
      color: 0xffffff,
      depthWrite: false,
      transparent: true,
      opacity: 0.9,
    });
    scene.add(new THREE.Points(geo, mat));
  })();

  // ---------- Lumière ambiante (faible)
  scene.add(new THREE.AmbientLight(0xffffff, 0.7));

  // ---------- Soleil (source principale)
  const sun = new THREE.Mesh(
    new THREE.SphereGeometry(200, 64, 64),
    new THREE.MeshBasicMaterial({ color: 0xffd966, emissive: 0xffe58a })
  );
  sun.position.set(-2000, 1500, -2000);
  scene.add(sun);

  // Halo visuel du soleil
  const glowCanvas = document.createElement("canvas");
  glowCanvas.width = glowCanvas.height = 256;
  const ctx = glowCanvas.getContext("2d");
  const grad = ctx.createRadialGradient(128, 128, 40, 128, 128, 128);
  grad.addColorStop(0, "rgba(255,220,100,1)");
  grad.addColorStop(1, "rgba(255,220,100,0)");
  ctx.fillStyle = grad;
  ctx.fillRect(0, 0, 256, 256);
  const sunGlow = new THREE.Sprite(
    new THREE.SpriteMaterial({
      map: new THREE.CanvasTexture(glowCanvas),
      transparent: true,
      blending: THREE.AdditiveBlending,
    })
  );
  sunGlow.scale.set(1000, 1000, 1);
  sun.add(sunGlow);

  // ---------- Lumière directionnelle du soleil
  const sunlight = new THREE.DirectionalLight(0xfff1b2, 3);
  const fillLight = new THREE.DirectionalLight(0x88bbff, 0.8);
  sunlight.position.copy(sun.position);
  sunlight.target.position.set(0, 0, -500);
  fillLight.position.set(1500, -400, 800);
  sunlight.castShadow = true;

  // Qualité des ombres
  sunlight.shadow.mapSize.width = 2048;
  sunlight.shadow.mapSize.height = 2048;
  sunlight.shadow.camera.near = 100;
  sunlight.shadow.camera.far = 6000;
  sunlight.shadow.camera.left = -3000;
  sunlight.shadow.camera.right = 3000;
  sunlight.shadow.camera.top = 3000;
  sunlight.shadow.camera.bottom = -3000;

  scene.add(sunlight);
  scene.add(fillLight);
  scene.add(sunlight.target);

  // ---------- Outils
  function makeRadialTexture(
    inner = "rgba(120,60,200,0.5)",
    outer = "rgba(0,0,0,0)"
  ) {
    const c = document.createElement("canvas");
    c.width = c.height = 256;
    const ctx = c.getContext("2d", { alpha: true });
    const g = ctx.createRadialGradient(128, 128, 40, 128, 128, 128);
    g.addColorStop(0, inner);
    g.addColorStop(1, outer);
    ctx.fillStyle = g;
    ctx.fillRect(0, 0, 256, 256);
    return new THREE.CanvasTexture(c);
  }

  function createPlanet({
    radius = 80,
    color = 0xffffff,
    pos = [0, 0, -1500],
  }) {
    const geo = new THREE.SphereGeometry(radius, 64, 64);
    const mat = new THREE.MeshStandardMaterial({
      color,
      roughness: 0.45,
      metalness: 0.4,
    });
    const mesh = new THREE.Mesh(geo, mat);
    mesh.position.set(...pos);
    mesh.castShadow = true;
    mesh.receiveShadow = true;
    scene.add(mesh);
    return mesh;
  }

  function createBlackHole(radius = 200, pos = [0, 0, -800]) {
    const geo = new THREE.SphereGeometry(radius, 64, 64);

    // Matériau totalement insensible à la lumière
    const mat = new THREE.MeshBasicMaterial({
      color: 0x000000, // noir absolu
    });

    const mesh = new THREE.Mesh(geo, mat);
    mesh.position.set(...pos);

    // Halo violet autour du trou noir
    const glowTex = makeRadialTexture(
      "rgba(219, 60, 20, 0.94)",
      "rgba(0,0,0,0)"
    );
    const glow = new THREE.Sprite(
      new THREE.SpriteMaterial({
        map: glowTex,
        transparent: true,
        blending: THREE.AdditiveBlending,
        depthWrite: false,
      })
    );
    glow.scale.set(radius * 3.15, radius * 3.15, 1);
    mesh.add(glow);

    // Le trou noir ne projette ni ne reçoit d’ombre
    mesh.castShadow = false;
    mesh.receiveShadow = false;

    scene.add(mesh);
    return mesh;
  }

  function createRing(innerR, outerR, color = 0xffffff) {
    const geo = new THREE.RingGeometry(innerR, outerR, 64);
    const mat = new THREE.MeshBasicMaterial({
      color,
      side: THREE.DoubleSide,
      transparent: true,
      opacity: 0.5,
    });
    const mesh = new THREE.Mesh(geo, mat);
    mesh.rotation.x = -Math.PI / 2;
    mesh.receiveShadow = true;
    return mesh;
  }

  // ---------- Planètes
  const planetA = createPlanet({
    radius: 180,
    color: 0xd32f2f,
    pos: [-300, 400, 1200],
  });
  planetA.add(createRing(200, 250, 0xffaaaa));
  planetA.add(createRing(280, 330, 0xffaaaa));

  const planetB = createPlanet({
    radius: 150,
    color: 0x1e345a,
    pos: [900, -150, -800],
  });
  planetB.add(createRing(180, 250, 0x99ccff));

  const blackHole = createBlackHole(400, [0, 0, -350]); // le trou noir ne reçoit aucune lumière

  // ---------- Animation
  function animate() {
    requestAnimationFrame(animate);
    planetA.rotation.y += 0.0015;
    planetB.rotation.y += 0.001;
    sun.rotation.y += 0.0004;
    renderer.render(scene, camera);
  }

  animate();

  // ---------- Resize
  window.addEventListener("resize", () => {
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setPixelRatio(window.devicePixelRatio);
  });

  console.log(
    "🌞 Esiah.dev — Espace 3D initialisé avec soleil et ombres dynamiques"
  );
  return {
    scene,
    camera,
    renderer,
    planetA,
    planetB,
    blackHole,
    sun,
    lerp,
    clamp,
  };
}
