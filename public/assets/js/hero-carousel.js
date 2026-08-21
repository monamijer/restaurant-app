$(document).ready(function () {
  const slides = $(".hero-slide");
  const dots = $(".hero-dot");
  let index = 0;

  if (slides.length <= 1) return;

  function afficherSlide(i) {
    slides.removeClass("actif");
    dots.removeClass("actif");
    slides.eq(i).addClass("actif");
    dots.eq(i).addClass("actif");
    index = i;
  }

  $(".hero-dot").on("click", function () {
    afficherSlide($(this).data("slide"));
  });

  setInterval(function () {
    afficherSlide((index + 1) % slides.length);
  }, 6000);
});
