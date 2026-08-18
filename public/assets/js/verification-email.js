// Usage : VerificationEmail.init('RESERVATION', function(emailVerifie) { ... suite du process ... });
const VerificationEmail = (function () {
  let typeActuel = "";
  let onVerifieCallback = null;
  let emailVerifie = false;

  function init(type, onVerifie) {
    typeActuel = type;
    onVerifieCallback = onVerifie;
    emailVerifie = false;

    $("#btn-envoyer-code").off("click").on("click", envoyerCode);
    $("#lien-renvoyer-code")
      .off("click")
      .on("click", function (e) {
        e.preventDefault();
        envoyerCode();
      });
    $("#btn-verifier-code").off("click").on("click", verifierCode);
  }

  function envoyerCode() {
    const email = $("#email-a-verifier").val().trim();
    if (!email) {
      $("#statut-email").text("Veuillez entrer un email.").css("color", "red");
      return;
    }

    $("#btn-envoyer-code").prop("disabled", true).text("Envoi...");

    $.post(
      "/verification/envoyer",
      { email: email, type: typeActuel },
      function (response) {
        $("#btn-envoyer-code").prop("disabled", false).text("Renvoyer");
        $("#statut-email")
          .text(response.message)
          .css("color", response.success ? "green" : "red");

        if (response.success) {
          $("#bloc-code-verification").removeClass("d-none");
        }
      },
      "json",
    ).fail(function () {
      $("#btn-envoyer-code").prop("disabled", false).text("Envoyer le code");
      $("#statut-email").text("Erreur lors de l'envoi.").css("color", "red");
    });
  }

  function verifierCode() {
    const email = $("#email-a-verifier").val().trim();
    const code = $("#code-verification").val().trim();

    if (!code) return;

    $.post(
      "/verification/verifier",
      { email: email, code: code, type: typeActuel },
      function (response) {
        if (response.success) {
          emailVerifie = true;
          $("#bloc-verification-email, #bloc-code-verification").addClass(
            "d-none",
          );
          $("#email-verifie-confirme").removeClass("d-none");
          if (onVerifieCallback) onVerifieCallback(email);
        } else {
          alert(response.message);
        }
      },
      "json",
    );
  }

  return {
    init: init,
    estVerifie: function () {
      return emailVerifie;
    },
  };
})();
