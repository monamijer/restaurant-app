$(document).ready(function () {
  let ligneCourante = [];

  function rendreLignes() {
    let html = "";
    let total = 0;
    ligneCourante.forEach(function (l, i) {
      const sousTotal = l.prix * l.quantite;
      total += sousTotal;
      html += `<tr>
                <td>${l.nom}</td>
                <td>${l.quantite}</td>
                <td>${sousTotal.toLocaleString("fr-FR")} ${window.deviseActuelle}</td>
                <td><button type="button" class="btn btn-sm btn-outline-danger btn-retirer-ligne" data-index="${i}">✕</button></td>
            </tr>`;
    });
    $("#lignes-entree").html(html);
    $("#total-entree-courante").text(
      total.toLocaleString("fr-FR") + " " + window.deviseActuelle,
    );
    $("#btn-enregistrer-entree").prop("disabled", ligneCourante.length === 0);
  }

  $("#btn-ajouter-ligne").on("click", function () {
    const select = $("#select-plat");
    const platId = select.val();
    const nom = select.find(":selected").data("nom");
    const prix = parseFloat(select.find(":selected").data("prix"));
    const quantite = parseInt($("#input-quantite-ligne").val()) || 1;

    if (!platId) return;

    const existant = ligneCourante.find((l) => l.plat_id == platId);
    if (existant) {
      existant.quantite += quantite;
    } else {
      ligneCourante.push({
        plat_id: platId,
        nom: nom,
        prix: prix,
        quantite: quantite,
      });
    }

    $("#input-quantite-ligne").val(1);
    rendreLignes();
  });

  $(document).on("click", ".btn-retirer-ligne", function () {
    const index = $(this).data("index");
    ligneCourante.splice(index, 1);
    rendreLignes();
  });

  $("#form-journal").on("submit", function (e) {
    e.preventDefault();
    if (ligneCourante.length === 0) return;

    const formData = new FormData();
    ligneCourante.forEach(function (l) {
      formData.append("plat_id[]", l.plat_id);
      formData.append("quantite[]", l.quantite);
    });
    formData.append("mode_paiement", $("#mode-paiement-journal").val());

    $.ajax({
      url: "/admin/journal/store",
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (response) {
        if (response.success) {
          ligneCourante = [];
          rendreLignes();
          afficherAlerte("success", response.message);
          setTimeout(() => location.reload(), 800);
        } else {
          afficherAlerte("danger", response.message);
        }
      },
      error: function () {
        afficherAlerte("danger", "Une erreur est survenue.");
      },
    });
  });

  function afficherAlerte(type, message) {
    $("#alert-zone").html(
      `<div class="alert alert-${type} alert-dismissible fade show">${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>`,
    );
  }
});
