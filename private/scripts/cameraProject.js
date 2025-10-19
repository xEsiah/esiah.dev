// === Esiah.dev — Caméra spécifique (page Neo Tokyo Rush) ===
import * as THREE from "https://unpkg.com/three@0.160.0/build/three.module.js";

/**
 * @param {object} S - Objet retourné par initScene()
 */
export function initCamera(S) {
  const { scene, camera, renderer, planetA, lerp, clamp } = S;
  const ease = (t) => t * t * (3 - 2 * t);

  // === Centre orbital : planète B ===
  const PA = planetA.position.clone();

  // === Lumière d’ambiance cyberpunk (bleu/rose) ===
  const lightBlue = new THREE.PointLight(0x66ccff, 1.4, 3000);
  const lightMagenta = new THREE.PointLight(0xff33aa, 0.8, 2500);
  lightBlue.position.set(PA.x + 600, PA.y + 300, PA.z + 800);
  lightMagenta.position.set(PA.x - 800, PA.y - 400, PA.z - 500);
  scene.add(lightBlue, lightMagenta);

  // === Trajectoire orbitale fluide autour de la planète ===
  function orbitAroundA(
    p,
    { radius = 500, elev = 180, sweep = Math.PI * 2.5 } = {}
  ) {
    const a = sweep * ease(clamp(p, 0, 1));
    camera.position.set(
      PA.x + Math.cos(a) * radius,
      PA.y + elev * Math.sin(a * 0.75),
      PA.z + Math.sin(a) * radius
    );
    camera.lookAt(PA);
  }

  // === Animation continue ===
  let t = 0;
  function animate() {
    requestAnimationFrame(animate);
    t += 0.0028; // vitesse d’orbite un peu plus rapide
    orbitAroundA((Math.sin(t) + 1) / 2);

    // rotation lente de la planète
    planetA.rotation.y += 0.002;
    if (planetA.children.length) planetA.children[0].rotation.z -= 0.001;

    renderer.render(scene, camera);
  }

  animate();
}
