// Module réutilisable : gère le modal de choix de paiement.
// Utilisation : PaiementModal.ouvrir({ type: 'reservation'|'commande', id: X, params: {...} })

const PaiementModal = (function () {
  let modal,
    config = {};

  function init() {
    modal = new bootstrap.Modal(document.getElementById("modalChoixPaiement"));

    $(".btn-methode-paiement").on("click", function () {
      const methode = $(this).data("methode");

      if (methode === "STRIPE") {
        lancerStripe();
        return;
      }

      if (methode === "CONTACT_RESTAURANT") {
        afficherEtape("contact-restaurant");
        $("#tel-contact-affiche").text(
          config.params.telephone_contact || "nous contacter",
        );
        enregistrerMethode("CONTACT_RESTAURANT", null);
        return;
      }

      // Mobile money
      afficherEtape("mobile-money");
      const numeros = {
        AIRTEL_MONEY: config.params.numero_airtel_money,
        ORANGE_MONEY: config.params.numero_orange_money,
        MPESA: config.params.numero_mpesa,
      };
      const noms = {
        AIRTEL_MONEY: "Airtel Money",
        ORANGE_MONEY: "Orange Money",
        MPESA: "M-Pesa",
      };
      $("#instructions-mobile-money").html(
        `Envoyez le montant au numéro <strong>${numeros[methode] || "(non configuré)"}</strong> (${noms[methode]}), puis collez la référence reçue par SMS ci-dessous.`,
      );
      $("#btn-confirmer-mobile-money").data("methode", methode);
    });

    $("#btn-retour-choix").on("click", function () {
      afficherEtape("choix-methode");
    });

    $("#btn-confirmer-mobile-money").on("click", function () {
      const reference = $("#reference-transaction").val().trim();
      if (!reference) {
        alert("Veuillez entrer la référence de transaction.");
        return;
      }
      const methode = $(this).data("methode");
      enregistrerMethode(methode, reference);
    });

    $("#btn-confirmer-contact").on("click", function () {
      modal.hide();
      if (config.onSuccess) config.onSuccess();
    });
  }

  function afficherEtape(nom) {
    $(
      "#etape-choix-methode, #etape-mobile-money, #etape-contact-restaurant, #etape-confirmation-envoyee",
    ).addClass("d-none");
    $("#etape-" + nom).removeClass("d-none");
  }

  function lancerStripe() {
    const url =
      config.type === "reservation"
        ? "/reservation/creer-paiement"
        : "/commande/creer-paiement";
    const cleId =
      config.type === "reservation" ? "reservation_id" : "commande_id";

    $.post(
      url,
      { [cleId]: config.id, mode_paiement: "STRIPE" },
      function (response) {
        if (response.success) {
          window.location.href = response.checkout_url;
        } else {
          alert(response.message);
        }
      },
      "json",
    );
  }

  function enregistrerMethode(methode, reference) {
    const url =
      config.type === "reservation"
        ? "/reservation/paiement-manuel"
        : "/commande/paiement-manuel";
    const cleId =
      config.type === "reservation" ? "reservation_id" : "commande_id";

    $.post(
      url,
      {
        [cleId]: config.id,
        mode_paiement: methode,
        reference_paiement: reference,
      },
      function (response) {
        if (response.success) {
          afficherEtape("confirmation-envoyee");
          setTimeout(() => {
            modal.hide();
            if (config.onSuccess) config.onSuccess();
          }, 2000);
        } else {
          alert(response.message);
        }
      },
      "json",
    );
  }

  return {
    ouvrir: function (options) {
      config = options;
      if (!modal) init();
      afficherEtape("choix-methode");
      modal.show();
    },
  };
})();
