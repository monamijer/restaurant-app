$(document).ready(function () {
  let emailConfirme = null;

  VerificationEmail.init("COMMANDE", function (email) {
    emailConfirme = email;
    $("#btn-soumettre-commande")
      .prop("disabled", false)
      .text("Valider la commande");
  });

  $("#type-commande").on("change", function () {
    $("#champ-adresse").toggleClass("d-none", $(this).val() !== "LIVRAISON");
  });

  $(document).on("change", ".input-quantite", function () {
    const platId = $(this).data("plat-id");
    const quantite = parseInt($(this).val());

    $.post(
      "/panier/modifier",
      { plat_id: platId, quantite: quantite },
      function (response) {
        location.reload();
      },
      "json",
    );
  });

  $("#form-checkout").on("submit", function (e) {
    e.preventDefault();

    if (!emailConfirme) {
      afficherAlerte(
        "danger",
        "Veuillez vérifier votre email avant de continuer.",
      );
      return;
    }

    $.ajax({
      url: "/commande/checkout",
      method: "POST",
      data: $(this).serialize(),
      dataType: "json",
      success: function (response) {
        if (!response.success) {
          afficherAlerte("danger", response.message);
          return;
        }

        if (!response.paiement_requis) {
          window.location.href = "/commande/suivi?id=" + response.commande_id;
          return;
        }

        PaiementModal.ouvrir({
          type: "commande",
          id: response.commande_id,
          params: parametresPaiement,
          onSuccess: function () {
            window.location.href = "/commande/suivi?id=" + response.commande_id;
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
