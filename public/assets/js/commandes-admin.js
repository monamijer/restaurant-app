$(document).ready(function () {
    $(document).on('change', '.select-statut-commande', function () {
        const id = $(this).data('id');
        const statut = $(this).val();

        $.post('/admin/commandes/statut', { id: id, statut: statut }, function (response) {
            if (response.success) {
                afficherAlerte('success', 'Statut mis à jour.');
            }
        }, 'json');
    });

    function afficherAlerte(type, message) {
        $('#alert-zone').html(`<div class="alert alert-${type}">${message}</div>`);
    }

    // Rafraîchissement auto toutes les 15 secondes (nouvelles commandes)
    setInterval(function () {
        // Rechargement simple pour l'instant ; on pourra affiner en AJAX pur plus tard
        // location.reload();
    }, 15000);
});