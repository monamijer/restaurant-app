function afficherToast(type, message, duree = 4000) {
  let container = document.getElementById("toast-container-custom");
  if (!container) {
    container = document.createElement("div");
    container.id = "toast-container-custom";
    container.className = "toast-container-custom";
    document.body.appendChild(container);
  }

  const toast = document.createElement("div");
  toast.className = `toast-custom ${type}`;
  toast.innerHTML = `<span>${message}</span><button class="toast-close">✕</button>`;

  container.appendChild(toast);

  function retirer() {
    toast.classList.add("sortant");
    setTimeout(() => toast.remove(), 300);
  }

  toast.querySelector(".toast-close").addEventListener("click", retirer);
  setTimeout(retirer, duree);
}
