$(document).ready(function () {
  const modal = new bootstrap.Modal(document.getElementById("modalDepense"));

  $("#btn-nouvelle-depense").on("click", function () {
    $("#form-depense")[0].reset();
    $("#dep-id").val("");
    $("#dep-date").val(new Date().toISOString().split("T")[0]);
    $("#modal-depense-titre").text("Ajouter une dépense");
  });

  $(document).on("click", ".btn-editer-depense", function () {
    $("#modal-depense-titre").text("Modifier la dépense");
    $("#dep-id").val($(this).data("id"));
    $("#dep-categorie").val($(this).data("categorie"));
    $("#dep-montant").val($(this).data("montant"));
    $("#dep-description").val($(this).data("description"));
    $("#dep-fournisseur").val($(this).data("fournisseur"));
    $("#dep-date").val($(this).data("date"));
    modal.show();
  });

  $("#form-depense").on("submit", function (e) {
    e.preventDefault();
    const id = $("#dep-id").val();
    const url = id ? "/admin/depenses/update" : "/admin/depenses/store";

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

  $(document).on("click", ".btn-supprimer-depense", function () {
    if (!confirm("Supprimer cette dépense ?")) return;
    const id = $(this).data("id");

    $.post(
      "/admin/depenses/delete",
      { id: id },
      function (response) {
        if (response.success) {
          $(`tr[data-id="${id}"]`).fadeOut(300, function () {
            $(this).remove();
          });
          afficherAlerte("success", response.message);
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
