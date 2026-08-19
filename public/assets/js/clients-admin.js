$(document).ready(function () {
  let timeout = null;

  $("#recherche-client").on("keyup", function () {
    clearTimeout(timeout);
    const terme = $(this).val();

    timeout = setTimeout(function () {
      $.get(
        "/admin/clients/rechercher-ajax",
        { q: terme },
        function (clients) {
          let html = "";
          clients.forEach(function (c) {
            html += `
                        <tr>
                            <td>${c.nom}</td>
                            <td>${c.email}</td>
                            <td>${c.telephone || "—"}</td>
                            <td>${c.nb_reservations || 0}</td>
                            <td>${c.nb_commandes || 0}</td>
                            <td><strong>${Number(c.total_depense || 0).toLocaleString("fr-FR")} ${window.deviseActuelle}</strong></td>
                            <td>${c.nb_no_show > 0 ? `<span class="badge bg-danger">${c.nb_no_show}</span>` : '<span style="color: var(--text-secondary);">0</span>'}</td>
                            <td><a href="/admin/clients/detail?id=${c.id}" class="btn btn-sm btn-outline-primary">Voir</a></td>
                        </tr>`;
          });
          $("#clients-table-body").html(html);
        },
        "json",
      );
    }, 300);
  });
});
