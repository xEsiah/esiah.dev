// === Esiah.dev — Caméra spécifique (page Neo Tokyo Rush) ===
import * as THREE from "https://unpkg.com/three@0.160.0/build/three.module.js";

/**
 * @param {object} S - Objet retourné par initScene()
 */
export function initCamera(S) {
  const { scene, camera, renderer, sun, lerp, clamp } = S;
  const ease = (t) => t * t * (3 - 2 * t);

  // === Centre orbital : planète B ===
  const SUN = sun.position.clone();

  // === Lumière d’ambiance cyberpunk (bleu/rose) ===
  const lightBlue = new THREE.PointLight(0x66ccff, 1.4, 3000);
  const lightMagenta = new THREE.PointLight(0xff33aa, 0.8, 2500);
  lightBlue.position.set(SUN.x + 600, SUN.y + 300, SUN.z + 800);
  lightMagenta.position.set(SUN.x - 800, SUN.y - 400, SUN.z - 500);
  scene.add(lightBlue, lightMagenta);

  // === Trajectoire orbitale fluide autour de la planète ===
  function orbitAroundSun(
    p,
    { radius = 600, elev = 180, sweep = Math.PI * 3 } = {}
  ) {
    const a = sweep * ease(clamp(p, 0, 1));

    // Hauteur de caméra : monte progressivement à la fin
    const height = elev + 400 * ease(Math.max(0, p - 0.7) / 0.3); // +400 dès p>0.7

    // Distance radiale : légère réduction pour l’effet "zoom plongeant"
    const r = radius * (1 - 0.1 * ease(Math.max(0, p - 0.7) / 0.3));

    // Position caméra
    camera.position.set(
      SUN.x + Math.cos(a) * r,
      SUN.y + height * Math.sin(a / 1.2),
      SUN.z + Math.sin(a) * r
    );

    // La caméra se met à regarder un peu en dessous du soleil à la fin
    const targetY = THREE.MathUtils.lerp(SUN.y, SUN.y - 300, ease(p));
    camera.lookAt(SUN.x, targetY, SUN.z);
  }

  // === Animation continue ===
  let t = 0;
  function animate() {
    requestAnimationFrame(animate);
    t += 0.0028; // vitesse d’orbite un peu plus rapide
    orbitAroundSun((Math.sin(t) + 1) / 2);

    // rotation lente de la planète
    sun.rotation.y += 0.002;
    if (sun.children.length) sun.children[0].rotation.z -= 0.001;

    renderer.render(scene, camera);
  }

  animate();
}
