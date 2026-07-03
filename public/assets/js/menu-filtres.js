$(document).ready(function () {
    let filtresActifs = { categorie_id: '', vegetarien: 0, sans_gluten: 0, epice: 0 };

    function chargerPlats() {
        $('#plats-liste').html('<p class="text-center w-100">Chargement...</p>');

        $.ajax({
            url: '/api/menu/filtrer',
            method: 'GET',
            data: filtresActifs,
            dataType: 'json',
            success: function (plats) {
                if (plats.length === 0) {
                    $('#plats-liste').html('<p class="text-center w-100" style="color: var(--text-secondary);">Aucun plat ne correspond à ces filtres.</p>');
                    return;
                }
                let html = '';
                plats.forEach(function (plat) {
                    const badges = [];
                    if (plat.vegetarien == 1) badges.push('🌱');
                    if (plat.sans_gluten == 1) badges.push('🌾');
                    if (plat.epice == 1) badges.push('🌶️');

                    const image = plat.image ? `/assets/uploads/${plat.image}` : '/assets/images/plat-default.jpg';

                    html += `
                        <div class="col-md-4">
                            <div class="plat-card h-100">
                                <img src="${image}" alt="${plat.nom}">
                                <div class="p-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h5 class="font-title mb-1">${plat.nom}</h5>
                                        <span>${badges.join(' ')}</span>
                                    </div>
                                    <p style="color: var(--text-secondary); font-size: 0.9rem;">${plat.description}</p>
                                    <strong style="color: var(--accent);">${parseFloat(plat.prix).toLocaleString('fr-FR')} BIF</strong>
                                </div>
                            </div>
                        </div>`;
                });
                $('#plats-liste').html(html);
            },
            error: function () {
                $('#plats-liste').html('<p class="text-center w-100">Erreur de chargement du menu.</p>');
            }
        });
    }

    // Filtre par catégorie (un seul actif à la fois)
    $('.btn-filtre').on('click', function () {
        $('.btn-filtre').removeClass('active');
        $(this).addClass('active');
        filtresActifs.categorie_id = $(this).data('categorie');
        chargerPlats();
    });

    // Filtres toggle (cumulables)
    $('.btn-toggle-filtre').on('click', function () {
        $(this).toggleClass('active');
        const filtre = $(this).data('filtre');
        filtresActifs[filtre] = $(this).hasClass('active') ? 1 : 0;
        chargerPlats();
    });

    chargerPlats(); // chargement initial
});