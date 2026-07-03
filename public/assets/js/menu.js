$(document).ready(function () {
    $.ajax({
        url: '/api/plats-signature',
        method: 'GET',
        dataType: 'json',
        success: function (plats) {
            let html = '';
            plats.forEach(function (plat) {
                html += `
                    <div class="col-md-4">
                        <div class="plat-card h-100">
                            <img src="/assets/uploads/${plat.image}" alt="${plat.nom}">
                            <div class="p-3">
                                <h5 class="font-title">${plat.nom}</h5>
                                <p style="color: var(--text-secondary); font-size: 0.9rem;">${plat.description}</p>
                                <strong style="color: var(--accent);">${plat.prix} BIF</strong>
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