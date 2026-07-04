$(document).ready(function () {

    // Changement de statut via le select
    $(document).on('change', '.select-statut', function () {
        const statut = $(this).val();
        if (!statut) return;

        const id = $(this).data('id');
        const $ligne = $(`tr[data-id="${id}"]`);

        if (statut === 'NO_SHOW' && !confirm('Confirmer le no-show ? L\'acompte sera retenu et le compteur du client sera incrémenté.')) {
            $(this).val('');
            return;
        }

        $.post('/admin/reservations/statut', { id: id, statut: statut }, function (response) {
            if (response.success) {
                afficherAlerte('success', response.message);
                setTimeout(() => location.reload(), 800);
            } else {
                afficherAlerte('danger', response.message);
            }
        }, 'json').fail(function () {
            afficherAlerte('danger', 'Une erreur est survenue.');
        });
    });

    // Filtre par statut (affichage simple, côté client, pas de rechargement)
    $('.btn-filtre-statut').on('click', function () {
        $('.btn-filtre-statut').removeClass('active');
        $(this).addClass('active');

        const statut = $(this).data('statut');
        if (!statut) {
            $('.ligne-statut').show();
        } else {
            $('.ligne-statut').hide();
            $(`.ligne-statut[data-statut-actuel="${statut}"]`).show();
        }
    });

    function afficherAlerte(type, message) {
        $('#alert-zone').html(`<div class="alert alert-${type}">${message}</div>`);
    }
});