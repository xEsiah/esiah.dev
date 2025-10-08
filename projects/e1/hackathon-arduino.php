<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Portfolio Numerique - Hackathon Arduino</title>
  <link rel="icon" href="/projects/e1/images/icon.png">
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/projects/e1/styles/index.css">
  <link rel="stylesheet" href="/projects/e1/styles/pages_secondaires.css">
</head>


<body>
  <header>
    <div class="profile">
      <a href="/projects/" class="back-btn">Back to projects list</a>
      <h1>DUPRE THEO</h1>
      <p id="honorific_title">Explorateur Numérique</p>
    </div>
  </header>
  <div class="margin_body">
    <div class="video-container">
      <h2>Panneau conneccté</h2>
      <img src="/projects/e1/images/prototype3D.gif" alt="Prototype 3D du panneau connecté">
      <video src="/projects/e1/videos/Hackathon Arduino.mp4" title="Présentation Hackathon Arduino"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
        allowfullscreen></video>
    </div>
    <div class="code-container">
      <div class="code-wrapper">
        <h3>Programme du module d'envoie</h3>
        <pre>
      <span class="comment">// Inclusion des bibliothèques</span>
      <span class="keyword">#include</span> <span class="string">&lt;SoftwareSerial.h&gt;</span>
      <span class="keyword">#include</span> <span class="string">"Ultrasonic.h"</span>
      <span class="keyword">#include</span> <span class="string">"Adafruit_NeoPixel.h"</span>
      <span class="keyword">#include</span> <span class="string">&lt;Arduino.h&gt;</span>
      <span class="keyword">#include</span> <span class="string">&lt;HardwareSerial.h&gt;</span>
      <span class="keyword">#include</span> <span class="string">"DFRobot_SerialScreen771.h"</span>
      
      <span class="comment">// Définition des broches et constantes</span>
      <span class="keyword">#define</span> LED2 5
      <span class="keyword">#define</span> NUMLEDS2 10
      <span class="keyword">#define</span> PIR_PIN 6
      <span class="keyword">#define</span> ULTRASONIC_PIN 7
      <span class="keyword">#define</span> LIDAR_THRESHOLD 10
      <span class="keyword">#define</span> buttonPin 4
      
      SoftwareSerial SerialLidar(2, 3);
      SoftwareSerial afficheurLED(A0, A1);
      SoftwareSerial xbee(A2, A3);
      Adafruit_NeoPixel pixels2 = Adafruit_NeoPixel(NUMLEDS2, LED2, NEO_GRB + NEO_KHZ800);
      DFRobot_SerialScreen771 screen(afficheurLED);
      Ultrasonic ultrasonic(ULTRASONIC_PIN);
      
      <span class="comment">// Messages affichés</span>
      <span class="keyword">const char</span>* messages[] = {
          <span class="string">"&lt;CGD&gt;Aucun risque present, bonne promenade !"</span>,
          <span class="string">"&lt;CYL&gt;Presence de chenilles urticantes. Restez vigilants"</span>,
          <span class="string">"&lt;CRD&gt;Abattage d'arbres en cours, passage defendu !"</span>,
          <span class="string">"&lt;CRD&gt;Chasse en cours, passage defendu !"</span>,
          <span class="string">"&lt;CDD&gt;null"</span>
      };

      <span class="keyword">void</span> <span class="function">setup</span>() {
          afficheurLED.<span class="function">begin</span>(19200);
          SerialLidar.<span class="function">begin</span>(19200);
          screen.<span class="function">begin</span>();
          xbee.<span class="function">begin</span>(9600);
          
          pinMode(buttonPin, INPUT_PULLUP);
          pinMode(PIR_PIN, INPUT);
          
          pixels2.<span class="function">setBrightness</span>(100);
          pixels2.<span class="function">begin</span>();
          <span class="keyword">for</span> (<span class="keyword">int</span> i = 0; i &lt; NUMLEDS2; i++) {
              pixels2.<span class="function">setPixelColor</span>(i, pixels2.<span class="function">Color</span>(0, 150, 0));
          }
          pixels2.<span class="function">show</span>();
          screen.<span class="function">setBrightness</span>(55);
          <span class="keyword">for</span> (<span class="keyword">int</span> i = 0; i &lt; 5; i++) {
              screen.<span class="function">setMessageList</span>(screen.eBanner_1 + i, messages[i]);
          }
      }

      <span class="keyword">void</span> <span class="function">loop</span>() {
          updateDisplayWithButton();
          <span class="keyword">int</span> pirState = digitalRead(PIR_PIN);
          <span class="keyword">long</span> ultraDist = ultrasonic.<span class="function">MeasureInCentimeters</span>();
          <span class="keyword">int</span> ultraState = (ultraDist &gt; 0 &amp;&amp; ultraDist &lt; 100) ? 1 : 0;
          <span class="keyword">int</span> distance = getLidarDistance();
          <span class="keyword">int</span> lidarState = (abs(distance - lastLidarDist) &gt; LIDAR_THRESHOLD) ? 1 : 0;
          lastLidarDist = distance;
          
          <span class="keyword">int</span> totalDetected = pirState + ultraState + lidarState;
          
          <span class="keyword">if</span> (totalDetected &gt;= 2) {
              xbee.<span class="function">write</span>(etat);
              changement_couleur();
              screen.<span class="function">displayBanner</span>(screen.eBanner_1+ etat);
              delay(3000);
          } <span class="keyword">else</span> {
              <span class="keyword">for</span> (<span class="keyword">int</span> i = 0; i &lt; NUMLEDS2; i++) {
                  pixels2.<span class="function">setPixelColor</span>(i, pixels2.<span class="function">Color</span>(0, 0, 0));
              }
              screen.<span class="function">displayBanner</span>(screen.eBanner_5);
              pixels2.<span class="function">show</span>();
          }
      }

      <span class="keyword">void</span> <span class="function">updateDisplayWithButton</span>() {
          <span class="keyword">bool</span> buttonState = digitalRead(buttonPin);
          <span class="keyword">if</span> (buttonState == LOW &amp;&amp; lastButtonState == HIGH) {
              lastDebounceTime = millis();
              etat = (etat + 1) % 4;
              changement_couleur();
              delay(1000);
          }
          lastButtonState = buttonState;
      }
      
      <span class="keyword">void</span> <span class="function">changement_couleur</span>() {
          <span class="keyword">uint32_t</span> colors[] = {
              pixels2.<span class="function">Color</span>(0, 150, 0),
              pixels2.<span class="function">Color</span>(255, 253, 0),
              pixels2.<span class="function">Color</span>(255, 0, 0),
              pixels2.<span class="function">Color</span>(255, 0, 0)
          };
          <span class="keyword">for</span> (<span class="keyword">int</span> i = 0; i &lt; NUMLEDS2; i++) {
              pixels2.<span class="function">setPixelColor</span>(i, colors[etat]);
          }
          pixels2.<span class="function">show</span>();
      }
        </pre>
      </div>
      <div class="code-wrapper">
        <h3>Programme du module de réception</h3>
        <pre>
      <span class="comment">// Inclusion des bibliothèques</span>
      <span class="keyword">#include</span> <span class="string">&lt;SoftwareSerial.h&gt;</span>
      <span class="keyword">#include</span> <span class="string">"Adafruit_NeoPixel.h"</span>
      <span class="keyword">#include</span> <span class="string">&lt;Wire.h&gt;</span>
      <span class="keyword">#include</span> <span class="string">"rgb_lcd.h"</span>
      
      <span class="comment">// Création de l'objet LCD</span>
      rgb_lcd lcd;
      
      <span class="keyword">#define</span> LED3 5 <span class="comment">// Petite broche</span>
      <span class="keyword">#define</span> NUMLEDS3 10 <span class="comment">// Nombre de LEDS</span>
      
      Adafruit_NeoPixel pixels3 = Adafruit_NeoPixel(NUMLEDS3, LED3, NEO_GRB + NEO_KHZ800);
      <span class="keyword">int</span> etat = 0; <span class="comment">// État</span>
      SoftwareSerial xbee(2,3); <span class="comment">// RX, TX pour le XBee</span>
      
      <span class="keyword">void</span> <span class="function">setup</span>() {
          Serial.<span class="function">begin</span>(9600);
          xbee.<span class="function">begin</span>(9600);
          pixels3.<span class="function">setBrightness</span>(100);
          pixels3.<span class="function">begin</span>();
      }
      <span class="keyword">void</span> <span class="function">loop</span>() {
          <span class="keyword">for</span> (<span class="keyword">int</span> i = 0; i &lt; NUMLEDS3; i++) {
              pixels3.<span class="function">setPixelColor</span>(i, pixels3.<span class="function">Color</span>(0, 0, 0));
              pixels3.<span class="function">show</span>();
          }
          etat = xbee.<span class="function">read</span>();
          <span class="keyword">if</span> (etat &gt; -1) {
              lcd.<span class="function">begin</span>(16, 2); 
              <span class="function">changement_couleur</span>();
              <span class="keyword">switch</span> (etat) {
                  <span class="keyword">case</span> 0:
                      lcd.<span class="function">setRGB</span>(0, 150, 0);
                      lcd.<span class="function">setCursor</span>(0, 0);
                      lcd.<span class="function">print</span>(<span class="string">"Aucun risque,"</span>);
                      lcd.<span class="function">setCursor</span>(0, 1);
                      lcd.<span class="function">print</span>(<span class="string">"bonne promenade !"</span>); 
                      <span class="function">delay</span>(3000); 
                      <span class="keyword">break</span>;
                  <span class="keyword">case</span> 1:
                      lcd.<span class="function">setRGB</span>(255, 255, 0);
                      lcd.<span class="function">print</span>(<span class="string">"Chenilles,"</span>);
                      lcd.<span class="function">setCursor</span>(0, 1);
                      lcd.<span class="function">print</span>(<span class="string">"Restez vigilants!"</span>);
                      <span class="function">delay</span>(3000);
                      <span class="keyword">break</span>;
                  <span class="keyword">case</span> 2:
                  <span class="keyword">case</span> 3:
                      lcd.<span class="function">setRGB</span>(255, 0, 0);
                      lcd.<span class="function">print</span>(<span class="string">"Passage interdit!"</span>);
                      <span class="function">delay</span>(3000);
                      <span class="keyword">break</span>;
              }
              lcd.<span class="function">setRGB</span>(0, 0, 0);
              lcd.<span class="function">noDisplay</span>();
          }
      }
      
      <span class="keyword">void</span> <span class="function">changement_couleur</span>() {
          <span class="keyword">uint32_t</span> colors[] = {
              pixels3.<span class="function">Color</span>(0, 150, 0), 
              pixels3.<span class="function">Color</span>(255, 255, 0), 
              pixels3.<span class="function">Color</span>(255, 0, 0),
              pixels3.<span class="function">Color</span>(255, 0, 0)
          };
          <span class="keyword">uint32_t</span> color = colors[etat];
          <span class="keyword">for</span> (<span class="keyword">int</span> i = 0; i &lt; NUMLEDS3; i++) {
              pixels3.<span class="function">setPixelColor</span>(i, color);
          }
          pixels3.<span class="function">show</span>();
      }
        </pre>
      </div>
    </div>
    <div class="site-container">
      <h3 class="h3revert">Site internet du projet</h3>
      <a href="https://hackathon2siteweb.vercel.app/" target="_blank">
        <img src="/projects/e1/images/previewSiteHackathonIoT.png"
          alt="ACCUEIL PROJET ZONES À RISQUE - Site de sensibilisation à la sécurité en forêt">
      </a>
    </div>
    <a id="centrer_retour_index" href="/projects/e1">Retour au portfolio</a>
  </div>

  <footer>
    <h2>Contact</h2>
    <div class="icons_liens">
      <div class="social-icons" style="display: flex; gap: 15px">
        <a href="mailto:esiah.dev@gmail.com?subject=Demande%20d%27information&body=Bonjour%20Th%C3%A9o,"
          class="liens_footer" target="_blank">
          Contactez-moi
        </a>

        <a href="https://github.com/xEsiah" class="liens_footer" target="_blank">
          <img src="/projects/e1/images/icon_github.png" alt="GitHub" class="icon">
        </a>

        <a href="https://www.linkedin.com/in/xesiah/" class="liens_footer" target="_blank">
          <img src="/projects/e1/images/icon_linkedin.png" alt="LinkedIn" class="icon">
        </a>
      </div>
    </div>
    <p id="about_email">
      Envoie d'email automatisé uniquement supporté si une messagerie est
      définie par défaut
    </p>
  </footer>
</body>

</html>