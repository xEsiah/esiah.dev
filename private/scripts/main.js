import * as THREE from "https://unpkg.com/three@0.160.0/build/three.module.js";
import { initScene } from "/private/scripts/space.js";

// Initialise la scène de base
const sceneData = initScene(THREE);

// --- Détection de la page actuelle
const path = location.pathname;

// --- Table de correspondance page → module
const cameraModules = {
  "/": "/private/scripts/cameraIndex.js",
  "/projects/": "/private/scripts/cameraProject.js",
  "/contact/": "/private/scripts/cameraContact.js",
  "/Echoes-of-the-Last-Stop/": "/private/scripts/cameraEotLS.js",
  "/Neo-Tokyo-Rush/": "/private/scripts/cameraNTR.js",
};

// --- Recherche du module correspondant
const moduleToLoad = Object.entries(cameraModules).find(([page]) =>
  path.endsWith(page)
)?.[1];

// --- Import dynamique selon la page
if (moduleToLoad) {
  import(moduleToLoad)
    .then(({ initCamera }) => {
      console.log(`🎥 Chargement de ${moduleToLoad}`);
      initCamera(sceneData);
    })
    .catch((err) => console.error("Erreur d’import caméra :", err));
} else {
  console.log("ℹ️ Aucune caméra spécifique pour cette page.");
}
