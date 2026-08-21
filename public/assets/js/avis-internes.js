$.ajax({
  url: "/api/avis-internes",
  method: "GET",
  dataType: "json",
  success: function (avis) {
    if (!avis.length) {
      $("#avis-container").html(
        '<p class="text-center w-100" style="color: var(--text-secondary);">Aucun avis pour le moment. <a href="/avis">Soyez le premier à en laisser un</a> !</p>',
      );
      return;
    }

    let html = "";
    avis.forEach(function (a, i) {
      const etoiles = "⭐".repeat(a.note);
      html += `
                <div class="col-md-4 animate-on-scroll delay-${(i % 3) + 1}">
                    <div class="plat-card h-100 p-4">
                        <div class="mb-2">${etoiles}</div>
                        <p style="color: var(--text-secondary); font-size:0.9rem;">${a.commentaire}</p>
                        <strong>${a.user_nom}</strong>
                    </div>
                </div>`;
    });
    $("#avis-container").html(html);
    initAnimationsScroll();
  },
  error: function () {
    $("#avis-container").html(
      '<p class="text-center w-100">Impossible de charger les avis.</p>',
    );
  },
});
