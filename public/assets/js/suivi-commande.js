$(document).ready(function () {
    const commandeId = $('.suivi-etapes').data('commande-id');
    if (!commandeId) return;

    const ordreStatuts = ['EN_ATTENTE', 'EN_CUISINE', 'PRETE', 'SERVIE'];

    function mettreAJourAffichage(statut) {
        const indexActuel = ordreStatuts.indexOf(statut);
        $('.etape').each(function (i) {
            $(this).toggleClass('actif', i <= indexActuel);
        });
    }

    function verifierStatut() {
        $.get('/commande/statut-ajax', { id: commandeId }, function (response) {
            if (response.statut) {
                mettreAJourAffichage(response.statut);
                if (response.statut === 'SERVIE') {
                    clearInterval(interval); // arrête le polling une fois terminé
                }
            }
        }, 'json');
    }

    verifierStatut();
    const interval = setInterval(verifierStatut, 5000); // vérifie toutes les 5 secondes
});