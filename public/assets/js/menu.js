$(document).ready(function () {
    $.ajax({
        url: '/api/plats-signature',
        method: 'GET',
        dataType: 'json',
        success: function (plats) {
            let html = '';
            plats.forEach(function (plat, index) {
    html += `
        <div class="col-md-4">
            <div class="plat-card h-100">
                ${index === 0 ? '<span class="badge-flottant">✨ Coup de cœur</span>' : ""}
                <img src="/assets/uploads/${plat.image}" alt="${plat.nom}" onerror="this.onerror=null;this.classList.add('img-placeholder-fallback');this.removeAttribute('src');this.after(Object.assign(document.createElement('span'),{textContent:'🍽️',className:'img-placeholder-fallback'}));this.style.display='none';">
                <div class="p-3">
                    <h5 class="font-title">${plat.nom}</h5>
                    <p style="color: var(--text-secondary); font-size: 0.9rem;">${plat.description}</p>
                    <strong style="color: var(--accent);">${plat.prix} ${window.deviseActuelle}</strong>
                </div>
            </div>
        </div>`;
});
            $('#plats-container').html(html);
        },
        error: function () {
            $('#plats-container').html('<p class="text-center">Impossible de charger le menu.</p>');
        }
    });
});