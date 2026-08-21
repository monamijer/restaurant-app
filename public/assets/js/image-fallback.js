// Remplace toute image cassée par un placeholder visuel, sans dépendre d'un fichier par défaut
function gererImagesCassees() {
  document
    .querySelectorAll("img:not([data-fallback-ok])")
    .forEach(function (img) {
      img.setAttribute("data-fallback-ok", "1");
      img.addEventListener("error", function () {
        const placeholder = document.createElement("div");
        placeholder.className = "img-placeholder-fallback";
        placeholder.style.width = "100%";
        placeholder.style.height = img.offsetHeight
          ? img.offsetHeight + "px"
          : "200px";
        placeholder.textContent = "🍽️";
        img.replaceWith(placeholder);
      });
    });
}

document.addEventListener("DOMContentLoaded", gererImagesCassees);
// Réexécute après chaque chargement AJAX de contenu dynamique (menu, plats signature...)
const observateurImages = new MutationObserver(gererImagesCassees);
observateurImages.observe(document.body, { childList: true, subtree: true });
