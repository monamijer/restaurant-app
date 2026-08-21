$(document).ready(function () {
  const modal = new bootstrap.Modal(document.getElementById("modalEmploye"));
  let modeEdition = false;

  $("#btn-nouvel-employe").on("click", function () {
    $("#form-employe")[0].reset();
    $("#emp-id").val("");
    $("#modal-employe-titre").text("Ajouter un employé");
    modeEdition = false;

    $("#bloc-verif-employe").show();
    $("#emp-password").prop("required", true);
    $("#label-password").text("Mot de passe");
    $("#hint-password").text("");
    $("#btn-enregistrer-employe")
      .prop("disabled", true)
      .text("Vérifiez d'abord l'email");

    VerificationEmail.init("EMPLOYE", function () {
      $("#btn-enregistrer-employe").prop("disabled", false).text("Enregistrer");
    });
  });

  $(document).on("click", ".btn-editer-employe", function () {
    $("#modal-employe-titre").text("Modifier l'employé");
    $("#emp-id").val($(this).data("id"));
    $("#emp-nom").val($(this).data("nom"));
    $("#emp-telephone").val($(this).data("telephone"));
    $("#emp-role").val($(this).data("role"));
    modeEdition = true;

    $("#bloc-verif-employe").hide();
    $("#emp-password").val("").prop("required", false);
    $("#label-password").text("Nouveau mot de passe (optionnel)");
    $("#hint-password").text(
      "Laissez vide pour conserver le mot de passe actuel.",
    );
    $("#btn-enregistrer-employe").prop("disabled", false).text("Enregistrer");

    modal.show();
  });

  $("#btn-nouvel-employe").on("click", function () {
    modal.show();
  });

  $("#form-employe").on("submit", function (e) {
    e.preventDefault();
    const id = $("#emp-id").val();
    const url = id ? "/admin/employes/update" : "/admin/employes/store";

    $.post(
      url,
      $(this).serialize(),
      function (response) {
        if (response.success) {
          afficherAlerte("success", response.message);
          modal.hide();
          setTimeout(() => location.reload(), 800);
        } else {
          afficherAlerte("danger", response.message);
        }
      },
      "json",
    ).fail(function () {
      afficherAlerte("danger", "Une erreur est survenue.");
    });
  });

  $(document).on("click", ".btn-supprimer-employe", function () {
    if (!confirm("Supprimer cet employé ?")) return;
    const id = $(this).data("id");

    $.post(
      "/admin/employes/delete",
      { id: id },
      function (response) {
        if (response.success) {
          $(`tr[data-id="${id}"]`).fadeOut(300, function () {
            $(this).remove();
          });
          afficherAlerte("success", response.message);
        } else {
          afficherAlerte("danger", response.message);
        }
      },
      "json",
    );
  });

  function afficherAlerte(type, message) {
    $("#alert-zone").html(
      `<div class="alert alert-${type} alert-dismissible fade show">${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>`,
    );
  }
});
