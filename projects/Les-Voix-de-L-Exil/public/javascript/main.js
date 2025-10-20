document.addEventListener("DOMContentLoaded", () => {
  const dialogueElements = document.querySelectorAll(".dialogues div");
  const dialogues = Array.from(dialogueElements).map((el) => el.innerHTML);

  const azhari = document.getElementById("azhari");
  const lysandor = document.getElementById("lysandor");
  const darius = document.getElementById("darius");
  const contrebandier = document.getElementById("contrebandier");
  const nika = document.getElementById("nika");
  const teeva = document.getElementById("teeva");
  const dialogueBox = document.getElementById("dialogueBox");
  const button = document.getElementById("cta-button");

  let index = 0;
  let nombreClick = 0;

  const bodyClass = document.body.classList;
  const chapterClass = bodyClass.toString().match(/background-chapter\d+/);
  const showFormAtEnd =
    (chapterClass && chapterClass[0] === "background-chapter3") ||
    (chapterClass && chapterClass[0] === "background-chapter2") ||
    (chapterClass && chapterClass[0] === "background-chapter1");

  let clickTrigger = 2;
  if (chapterClass && chapterClass[0] === "background-chapter3") {
    if (window.location.pathname.includes("chapter3/answer.php")) {
      clickTrigger = azhariAlive ? Infinity : 12;
    } else {
      clickTrigger = azhariAlive ? 2 : 1;
    }
  }

  const chapterMessages = {
    "background-chapter0":
      "Dans les tribunes sombres de l’arène, l’odeur du sang flotte encore...",

    "background-chapter1": (() => {
      if (typeof goodAnswer !== "undefined") {
        if (goodAnswer === true) {
          return "Darius semble surpris par cette réponse. Marcus est une figure respectée. Peut-être que cela les sauvera…";
        } else {
          return "Le regard de Darius se durcit. Il ne semble pas croire un mot de leur justification. La fuite devient inévitable.";
        }
      }
      return "Dans les ruelles du Bastion Immortel, Azhari et Lysandor glissent entre les ombres. La garde sommeille. Trop facile...";
    })(),
    "background-chapter2":
      "Sous le vent sec de Drazhan, la capitale de Noxus s’efface. Devant eux, une plaine désertique... et derrière, un empire qu’ils ont défié refusant de les laisser fuir.",
    "background-chapter3": (() => {
      const path = window.location.pathname;
      if (path.includes("index.php")) {
        if (typeof azhariAlive !== "undefined") {
          if (azhariAlive === true) {
            return "Azhari et Lysandor survécurent à la traque de Darius. À Zaun, ils reprennent leur souffle, traqués mais unis.";
          } else {
            return "Darius, méfiant, envoya des soldats aux trousses de Lysandor. Azhari périt pendant la traversée jusqu’à Zaun.";
          }
        }
        return "Zaun, l'enfer sous Piltover, où la loi du plus fort est de mise.";
      } else {
        if (typeof azhariAlive !== "undefined") {
          if (azhariAlive === true) {
            if (typeof nikasOffer !== "undefined") {
              if (nikasOffer === "accept") {
                return "Azhari et Lysandor, acceptant l'offre de Nika, trouveront refuge à Zaun pendant 3 mois avant de rejoindre Piltover.";
              } else {
                return "Azhari et Lysandor refusant l'aide de Nika regretteront bientôt leur choix.";
              }
            }
            return "Azhari et Lysandor survécurent à la traque de Darius. À Zaun, ils reprennent leur souffle, traqués mais unis.";
          } else {
            return "Lysandor, receuilli par Teeva erra 6 mois dans les bas-fonds de Zaun, hanté par la mort d’Azhari. Durant ce temps il rencontrera une certaine Nika.";
          }
        }
      }
    })(),

    "background-chapter4": (() => {
      if (
        typeof azhariAlive !== "undefined" &&
        typeof nikasOffer !== "undefined"
      ) {
        if (azhariAlive === true && nikasOffer === "refuse") {
          // Fin 1 : Azhari seul à Piltover
          return "Vient Piltover, éclatante et indifférente. Azhari foule ses rues seul, le cœur alourdi par la perte de Lysandor.";
        } else if (azhariAlive === true && nikasOffer === "accept") {
          // Fin 2 : Azhari, Lysandor et Nika arrivent ensemble
          return "Piltover s’élève, majestueuse. Deux âmes marquées par Noxus puis par Zaun cherchent ici un nouveau départ.";
        } else if (azhariAlive === false && nikasOffer === "accept") {
          // Fin 3 : Lysandor reste à Zaun
          return "Piltover brille à l’horizon… mais Lysandor détourne les yeux. L’ombre de Zaun lui semble plus familière, plus honnête.";
        } else if (azhariAlive === false && nikasOffer === "refuse") {
          // Fin 4 : Lysandor part pour Piltover seul
          return "Piltover étend ses ponts et ses tours. Seul, Lysandor avance, porteur du souvenir d’Azhari et d'une promesse tenue.";
        }
      }
      return "Piltover, la cité de la lumière, mais aussi de la discorde.";
    })(),
  };

  const narrationText =
    chapterMessages[chapterClass ? chapterClass[0] : "default"] ||
    "Dans un monde de ténèbres, la lumière n'est jamais loin...";

  showNarration(narrationText, 4000, () => {
    document
      .querySelectorAll(".sprite")
      .forEach((el) => el.classList.remove("invisible-init"));
    button.classList.remove("invisible-init");

    setTimeout(() => {
      document
        .querySelectorAll(".sprite")
        .forEach((el) => el.classList.add("fade-in"));
      button.classList.add("fade-in");
      nextDialogue();
    }, 50);
  });

  function showNarration(text, duration = 3000, callback = null) {
    const box = document.getElementById("narration-box");
    const textElement = document.getElementById("narration-text");

    textElement.textContent = text;
    box.classList.add("show");

    setTimeout(() => {
      box.classList.remove("show");
      document
        .querySelectorAll(".invisible-init")
        .forEach((el) => el.classList.remove("invisible-init"));
      if (callback) callback();
    }, duration);
  }

  function highlightSpeakerFromDialogue() {
    // Révélations selon chapitre et clic
    if (
      chapterClass &&
      chapterClass[0] === "background-chapter1" &&
      nombreClick === clickTrigger
    ) {
      azhari.src = "../images/AzhariShen.png";
      azhari.classList.remove(
        "sprite-left",
        "sprite-right",
        "sprite-right-right"
      );
      azhari.classList.add("sprite-right-right");

      if (darius) {
        darius.classList.remove("hidden");
        darius.classList.add("reveal");
      }
    }

    if (
      chapterClass &&
      chapterClass[0] === "background-chapter2" &&
      nombreClick === clickTrigger
    ) {
      azhari.src = "../images/AzhariShen.png";
      azhari.classList.remove(
        "sprite-left",
        "sprite-right",
        "sprite-right-right"
      );
      azhari.classList.add("sprite-right-right");

      if (contrebandier) {
        contrebandier.classList.remove("hidden");
        contrebandier.classList.add("reveal");
      }
    }

    if (
      chapterClass &&
      chapterClass[0] === "background-chapter3" &&
      nombreClick === clickTrigger
    ) {
      const isAnswerPage = window.location.pathname.includes(
        "chapter3/answer.php"
      );

      if (azhariAlive) {
        lysandor.src = "../images/LysandorDuCouteau.png";
        lysandor.classList.remove(
          "sprite-left",
          "sprite-right",
          "sprite-right-right"
        );
        lysandor.classList.add("sprite-right-right");

        if (nika) {
          nika.classList.remove("hidden");
          nika.classList.add("reveal");
        }
      } else {
        lysandor.src = "../images/LysandorDuCouteauReversed.png";
        lysandor.classList.remove(
          "sprite-right",
          "sprite-left",
          "sprite-right-right"
        );
        lysandor.classList.add("sprite-left");

        if (teeva) {
          teeva.classList.remove("hidden");
          teeva.classList.add("reveal");
        }

        if (isAnswerPage && nika) {
          nika.classList.remove("hidden");
          nika.classList.add("reveal");
          teeva.classList.remove(
            "sprite-left",
            "sprite-right",
            "sprite-right-right"
          );
          teeva.classList.add("sprite-right-right");
        }
      }
    }

    const characters = {
      azhari,
      lysandor,
      darius,
      contrebandier,
      nika,
      teeva,
    };

    Object.values(characters).forEach((el) => {
      if (el) el.classList.remove("active-speaker");
    });
    dialogueBox.classList.remove("dialogue-left", "dialogue-right");

    const strongEl = dialogueBox.querySelector("strong");
    if (!strongEl) return;

    const speaker = strongEl.textContent.split(":")[0].trim().toLowerCase();
    const characterEl = characters[speaker];

    if (characterEl) {
      characterEl.classList.add("active-speaker");

      const isRight =
        characterEl.classList.contains("sprite-right") ||
        characterEl.classList.contains("sprite-right-right");

      dialogueBox.classList.add(isRight ? "dialogue-right" : "dialogue-left");
    }
  }

  function nextDialogue() {
    if (index < dialogues.length) {
      dialogueBox.innerHTML = dialogues[index];
      highlightSpeakerFromDialogue();

      index++;
      nombreClick++;

      if (index === dialogues.length && showFormAtEnd) {
        const form = document.getElementById("choice-form");
        if (form) {
          form.classList.add("visible", "fade-in");
          button.style.display = "none";
          form.style.display = "block";
        }

        if (window.location.pathname.includes("chapter1/answer.php")) {
          button.textContent = goodAnswer === true ? "S'échapper !" : "Fuir…";
        }

        button.onclick = () => {
          const form = document.createElement("form");
          form.method = "POST";

          const currentPath = window.location.pathname;

          if (currentPath.includes("chapter3/index.php")) {
            form.action = "../chapter3/answer.php";
          } else if (currentPath.includes("chapter3/answer.php")) {
            form.action = "../chapter4/index.php";
          } else if (currentPath.includes("chapter1/answer.php")) {
            form.action = "../chapter2/index.php";
          } else {
            form.action = "./";
          }

          const inputGoodAnswer = document.createElement("input");
          inputGoodAnswer.type = "hidden";
          inputGoodAnswer.name = "good_answer";
          inputGoodAnswer.value = goodAnswer;
          form.appendChild(inputGoodAnswer);

          const inputNikas = document.createElement("input");
          inputNikas.type = "hidden";
          inputNikas.name = "nikas_offer";
          inputNikas.value = "";
          form.appendChild(inputNikas);

          document.body.appendChild(form);
          form.submit();
        };
      }
    }
  }

  button.addEventListener("click", () => {
    if (index < dialogues.length) {
      nextDialogue();
    } else if (!showFormAtEnd) {
      const currentPath = window.location.pathname;
      const match = currentPath.match(/(chapter)(\d+)/i);
      if (match) {
        const currentChapNum = parseInt(match[2], 10);
        let nextChapUrl;
        if (currentChapNum === 4) {
          nextChapUrl = "/projects/Les-Voix-De-L-Exil/public/epilogue.php";
        } else {
          nextChapUrl = currentPath.replace(
            /chapter\d+/i,
            `chapter${currentChapNum + 1}`
          );
        }
        window.location.href = nextChapUrl;
      } else {
        window.location.href = "/chapter2/index.php";
      }
    }
  });
});
