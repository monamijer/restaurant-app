$(document).ready(function () {
  let noteChoisie = 0;
  let emailConfirme = null;

  VerificationEmail.init("AVIS", function (email) {
    emailConfirme = email;
    $("#btn-soumettre-avis").prop("disabled", false).text("Publier mon avis");
  });

  $("#etoiles-select span").on("click", function () {
    noteChoisie = $(this).data("valeur");
    $("#note-selectionnee").val(noteChoisie);
    $("#etoiles-select span").each(function (i) {
      $(this).toggleClass("active", i < noteChoisie);
    });
  });

  $("#form-avis").on("submit", function (e) {
    e.preventDefault();

    if (!emailConfirme) {
      afficherAlerte(
        "danger",
        "Veuillez vérifier votre email avant de continuer.",
      );
      return;
    }
    if (noteChoisie === 0) {
      afficherAlerte("danger", "Veuillez sélectionner une note.");
      return;
    }

    $.post(
      "/avis/ajouter",
      $(this).serialize(),
      function (response) {
        if (response.success) {
          afficherAlerte("success", response.message);
          setTimeout(() => location.reload(), 1200);
        } else {
          afficherAlerte("danger", response.message);
        }
      },
      "json",
    ).fail(function () {
      afficherAlerte("danger", "Une erreur est survenue.");
    });
  });

  function afficherAlerte(type, message) {
    $("#alert-zone").html(`<div class="alert alert-${type}">${message}</div>`);
  }
});
