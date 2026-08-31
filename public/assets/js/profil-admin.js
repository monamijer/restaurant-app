$(document).ready(function () {
  VerificationEmail.init("PROFIL", function () {
    $("#btn-changer-email").prop("disabled", false).text("Changer l'email");
  });

  $("#form-infos").on("submit", function (e) {
    e.preventDefault();
    $.post(
      "/admin/profil/infos",
      $(this).serialize(),
      function (response) {
        afficherToast(
          response.success ? "success" : "danger",
          response.message,
        );
      },
      "json",
    ).fail(function () {
      afficherToast("danger", "Une erreur est survenue.");
    });
  });

  $("#form-email").on("submit", function (e) {
    e.preventDefault();
    $.post(
      "/admin/profil/email",
      $(this).serialize(),
      function (response) {
        afficherToast(
          response.success ? "success" : "danger",
          response.message,
        );
        if (response.success) {
          setTimeout(() => location.reload(), 1200);
        }
      },
      "json",
    );
  });

  $("#form-password").on("submit", function (e) {
    e.preventDefault();
    $.post(
      "/admin/profil/mot-de-passe",
      $(this).serialize(),
      function (response) {
        afficherToast(
          response.success ? "success" : "danger",
          response.message,
        );
        if (response.success) {
          $("#form-password")[0].reset();
        }
      },
      "json",
    );
  });
});
