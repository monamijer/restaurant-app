$(document).ready(function () {
  $("#admin-mobile-toggle").on("click", function () {
    $("#admin-sidebar").toggleClass("ouvert");
    $("#admin-overlay").toggleClass("actif");
  });

  $("#admin-overlay").on("click", function () {
    $("#admin-sidebar").removeClass("ouvert");
    $(this).removeClass("actif");
  });
});
