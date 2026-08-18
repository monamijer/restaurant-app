$(document).ready(function () {
  $("#form-reservation").on("submit", function (e) {
    e.preventDefault();

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

        // Acompte requis : ouvre le choix du mode de paiement
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
