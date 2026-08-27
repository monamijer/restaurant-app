$(document).ready(function () {
  $(".checkbox-ferme").on("change", function () {
    const jour = $(this).data("jour");
    const estFerme = $(this).is(":checked");
    $(`.input-heure[data-jour="${jour}"]`).prop("disabled", estFerme);
  });

  $("#form-horaires").on("submit", function (e) {
    e.preventDefault();

    $.post(
      "/admin/horaires/update",
      $(this).serialize(),
      function (response) {
        if (response.success) {
          afficherToast("success", response.message);
        } else {
          afficherToast("danger", response.message);
        }
      },
      "json",
    ).fail(function () {
      afficherToast("danger", "Une erreur est survenue.");
    });
  });

  $("#form-fermeture").on("submit", function (e) {
    e.preventDefault();

    $.post(
      "/admin/horaires/fermeture/ajouter",
      $(this).serialize(),
      function (response) {
        if (response.success) {
          afficherToast("success", response.message);
          setTimeout(() => location.reload(), 800);
        } else {
          afficherToast("danger", response.message);
        }
      },
      "json",
    );
  });

  $(document).on("click", ".btn-supprimer-fermeture", function () {
    if (!confirm("Supprimer cette fermeture ?")) return;
    const id = $(this).data("id");

    $.post(
      "/admin/horaires/fermeture/supprimer",
      { id: id },
      function (response) {
        if (response.success) {
          $(`tr[data-id="${id}"]`).fadeOut(300, function () {
            $(this).remove();
          });
          afficherToast("success", response.message);
        }
      },
      "json",
    );
  });
});
