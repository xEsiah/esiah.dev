import * as THREE from "https://unpkg.com/three@0.160.0/build/three.module.js";
import { initScene } from "/private/scripts/space.js";

// === Initialisation de la scène ===
const sceneData = window.EsiahScene || initScene(THREE);
window.EsiahScene = sceneData;

// --- Normalisation du chemin ---
let path = location.pathname.replace(/^\/EnvTest/, ""); // si ton site est en /EnvTest/
if (path === "" || path === "/" || path === "/index.php") path = "/";

// --- Table de correspondance page → caméra ---
const cameraModules = {
  "/Neo-Tokyo-Rush/": "/private/scripts/cameraNTR.js",
  "/Echoes-of-the-Last-Stop/": "/private/scripts/cameraEotLS.js",
  "/projects/": "/private/scripts/cameraProject.js",
  "/contact/": "/private/scripts/cameraContact.js",
  "/": "/private/scripts/cameraIndex.js",
};

// --- Recherche du module correspondant (du plus long au plus court) ---
const moduleToLoad = Object.entries(cameraModules)
  .sort(([a], [b]) => b.length - a.length)
  .find(([page]) => path.endsWith(page))?.[1];

// --- Import dynamique du bon module ---
if (moduleToLoad) {
  import(moduleToLoad)
    .then(({ initCamera }) => {
      console.log(`🎥 Caméra chargée : ${moduleToLoad}`);
      initCamera(sceneData);
    })
    .catch((err) => console.error("Erreur d’import caméra :", err));
} else {
  console.log("ℹ️ Aucune caméra spécifique pour cette page :", path);
}
