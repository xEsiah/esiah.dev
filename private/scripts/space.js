// === Esiah.dev — Génération espace 3D ===
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
camera.position.set(0, 0, 2000);
camera.lookAt(0, 0, -350);

const renderer = new THREE.WebGLRenderer({ antialias: true });
renderer.setSize(innerWidth, innerHeight);
renderer.setPixelRatio(devicePixelRatio);
renderer.domElement.style.position = "fixed";
renderer.domElement.style.inset = "0";
renderer.domElement.style.zIndex = "-1";
document.body.appendChild(renderer.domElement);

// ---------- Stars
(function makeStars() {
  const count = 8000;
  const geo = new THREE.BufferGeometry();
  const pos = new Float32Array(count * 3);
  for (let i = 0; i < count; i++) {
    pos[i * 3 + 0] = (Math.random() - 0.5) * 6000;
    pos[i * 3 + 1] = (Math.random() - 0.5) * 6000;
    pos[i * 3 + 2] = (Math.random() - 0.5) * 6000; // étoiles dans tout l'espace
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

// ---------- Lights
scene.add(new THREE.AmbientLight(0xffffff, 0.35));
const keyLight = new THREE.PointLight(0xffffff, 1.1);
keyLight.position.set(400, 350, 200);
scene.add(keyLight);

// ---------- Helpers
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

function createPlanet({ radius = 80, color = 0xffffff, pos = [0, 0, -1500] }) {
  const geo = new THREE.SphereGeometry(radius, 64, 64);
  const mat = new THREE.MeshStandardMaterial({
    color,
    roughness: 0.4,
    metalness: 0.3,
  });
  const mesh = new THREE.Mesh(geo, mat);
  mesh.position.set(...pos);
  scene.add(mesh);
  return mesh;
}

function createBlackHole(radius = 200, pos = [0, 0, -800]) {
  const geo = new THREE.SphereGeometry(radius, 64, 64);
  const mat = new THREE.MeshStandardMaterial({
    color: 0x000000,
    metalness: 1,
    roughness: 0.2,
    emissive: 0x000000,
  });
  const mesh = new THREE.Mesh(geo, mat);
  mesh.position.set(...pos);

  const glowTex = makeRadialTexture("rgba(120,60,200,0.5)", "rgba(0,0,0,0)");
  const glow = new THREE.Sprite(
    new THREE.SpriteMaterial({
      map: glowTex,
      transparent: true,
      blending: THREE.AdditiveBlending,
      depthWrite: false,
    })
  );
  glow.scale.set(radius * 4, radius * 4, 1);
  mesh.add(glow);

  scene.add(mesh);
  return mesh;
}

function createRing(innerR, outerR, color = 0xffffff) {
  const geo = new THREE.RingGeometry(innerR, outerR, 64);
  const mat = new THREE.MeshBasicMaterial({
    color,
    side: THREE.DoubleSide,
    transparent: true,
    opacity: 0.35,
  });
  const mesh = new THREE.Mesh(geo, mat);
  mesh.rotation.x = -Math.PI / 2;
  return mesh;
}

// ---------- Planets
const planetA = createPlanet({
  radius: 180,
  color: 0xd32f2f,
  pos: [-300, 400, 1200],
});
planetA.add(createRing(160, 220, 0xffaaaa));

const planetB = createPlanet({
  radius: 150,
  color: 0x112a40,
  pos: [900, -150, -800],
});
planetB.add(createRing(180, 250, 0x99ccff));

const planetC = createBlackHole(400, [0, 0, -350]);

// ---------- Export
window.EsiahScene = {
  scene,
  camera,
  renderer,
  planetA,
  planetB,
  planetC,
  ease,
  lerp,
  clamp,
};
