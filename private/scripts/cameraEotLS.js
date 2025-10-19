// === Esiah.dev — Caméra spécifique (page Neo Tokyo Rush) ===
import * as THREE from "https://unpkg.com/three@0.160.0/build/three.module.js";

/**
 * @param {object} S - Objet retourné par initScene()
 */
export function initCamera(S) {
  const { scene, camera, renderer, planetB, lerp, clamp } = S;
  const ease = (t) => t * t * (3 - 2 * t);

  // === Centre orbital : planète B ===
  const PB = planetB.position.clone();

  // === Lumière d’ambiance cyberpunk (bleu/rose) ===
  const lightBlue = new THREE.PointLight(0x66ccff, 1.4, 3000);
  const lightMagenta = new THREE.PointLight(0xff33aa, 0.8, 2500);
  lightBlue.position.set(PB.x + 600, PB.y + 300, PB.z + 800);
  lightMagenta.position.set(PB.x - 800, PB.y - 400, PB.z - 500);
  scene.add(lightBlue, lightMagenta);

  // === Trajectoire orbitale fluide autour de la planète ===
  function orbitAroundB(
    p,
    { radius = 1000, elev = 180, sweep = Math.PI * 2.5 } = {}
  ) {
    const a = sweep * ease(clamp(p, 0, 1));
    camera.position.set(
      PB.x + Math.cos(a) * radius,
      PB.y + elev * Math.sin(a * 0.5),
      PB.z + Math.sin(a) * radius
    );
    camera.lookAt(PB);
  }

  // === Animation continue ===
  let t = 0;
  function animate() {
    requestAnimationFrame(animate);
    t += 0.0028; // vitesse d’orbite un peu plus rapide
    orbitAroundB((Math.sin(t) + 1) / 2);

    // rotation lente de la planète
    planetB.rotation.y += 0.002;
    if (planetB.children.length) planetB.children[0].rotation.z -= 0.001;

    renderer.render(scene, camera);
  }

  animate();
}
