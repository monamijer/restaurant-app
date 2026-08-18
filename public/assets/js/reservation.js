$(document).ready(function () {
  let emailConfirme = null;

  VerificationEmail.init("RESERVATION", function (email) {
    emailConfirme = email;
    $("#btn-soumettre-reservation").prop("disabled", false).text("Réserver");
  });

  $("#form-reservation").on("submit", function (e) {
    e.preventDefault();

    if (!emailConfirme) {
      afficherAlerte(
        "danger",
        "Veuillez vérifier votre email avant de continuer.",
      );
      return;
    }

    $.ajax({
      url: "/reserver",
      method: "POST",
      data: $(this).serialize(),
      dataType: "json",
      success: function (response) {
        if (!response.success) {
          afficherAlerte("danger", response.message);
          return;
        }

        if (!response.acompte_requis) {
          afficherAlerte("success", response.message);
          setTimeout(
            () =>
              (window.location.href =
                "/reservation/confirmation?id=" + response.reservation_id),
            1200,
          );
          return;
        }

        PaiementModal.ouvrir({
          type: "reservation",
          id: response.reservation_id,
          params: parametresPaiement,
          onSuccess: function () {
            window.location.href =
              "/reservation/confirmation?id=" + response.reservation_id;
          },
        });
      },
      error: function () {
        afficherAlerte("danger", "Une erreur est survenue.");
      },
    });
  });

  function afficherAlerte(type, message) {
    $("#alert-zone").html(`<div class="alert alert-${type}">${message}</div>`);
  }
});
