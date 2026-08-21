document.addEventListener("DOMContentLoaded", function () {
  initAnimationsScroll();
});

function initAnimationsScroll() {
  const elements = document.querySelectorAll(
    ".animate-on-scroll:not([data-observed])",
  );

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("visible");
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.15 },
  );

  elements.forEach((el) => {
    el.dataset.observed = "1";
    observer.observe(el);
  });
}
