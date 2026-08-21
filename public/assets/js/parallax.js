document.addEventListener("DOMContentLoaded", function () {
  const elements = document.querySelectorAll(".parallax-element");

  function updateParallax() {
    const scrollY = window.scrollY;

    elements.forEach(function (el) {
      const speed = parseFloat(el.dataset.speed || 0.3);
      const rect = el.getBoundingClientRect();
      const offset = rect.top + scrollY - scrollY;
      const translateY =
        (scrollY - (rect.top + scrollY - window.innerHeight)) * speed;
      el.style.transform = `translateY(${translateY * 0.1}px)`;
    });

    // Barre de progression de lecture
    const hauteurTotale =
      document.documentElement.scrollHeight - window.innerHeight;
    const pourcentage = hauteurTotale > 0 ? (scrollY / hauteurTotale) * 100 : 0;
    const bar = document.getElementById("progress-bar");
    if (bar) bar.style.width = pourcentage + "%";
  }

  window.addEventListener("scroll", updateParallax, { passive: true });
  updateParallax();
});
