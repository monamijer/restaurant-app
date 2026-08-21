$.ajax({
  url: "/api/avis-google",
  method: "GET",
  dataType: "json",
  success: function (avis) {
    if (!avis.length) return;

    // Duplique la liste pour un défilement infini sans coupure visible
    const liste = [...avis, ...avis];
    let html = "";
    liste.forEach(function (a) {
      const etoiles = "⭐".repeat(a.note);
      html += `
                <div class="avis-marquee-card">
                    <div class="mb-2">${etoiles}</div>
                    <p style="color: var(--text-secondary); font-size:0.9rem;">${a.commentaire}</p>
                    <strong>${a.nom_client}</strong>
                </div>`;
    });
    $("#avis-marquee-track").html(html);
  },
});
