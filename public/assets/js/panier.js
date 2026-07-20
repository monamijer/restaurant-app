$(document).ready(function () {

    $('#type-commande').on('change', function () {
        $('#champ-adresse').toggleClass('d-none', $(this).val() !== 'LIVRAISON');
    });

    $(document).on('change', '.input-quantite', function () {
        const platId = $(this).data('plat-id');
        const quantite = parseInt($(this).val());

        $.post('/panier/modifier', { plat_id: platId, quantite: quantite }, function (response) {
            location.reload(); // simple, garantit un total toujours juste
        }, 'json');
    });

    $('#form-checkout').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: '/commande/checkout',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (response) {
                if (!response.success) {
                    afficherAlerte('danger', response.message);
                    return;
                }

                if (!response.paiement_requis) {
                    window.location.href = '/commande/suivi?id=' + response.commande_id;
                    return;
                }

                afficherAlerte('info', 'Redirection vers le paiement...');
                $.post('/commande/creer-paiement', { commande_id: response.commande_id }, function (paiement) {
                    if (paiement.success) {
                        window.location.href = paiement.checkout_url;
                    } else {
                        afficherAlerte('danger', paiement.message);
                    }
                }, 'json');
            },
            error: function () {
                afficherAlerte('danger', 'Une erreur est survenue.');
            }
        });
    });

    function afficherAlerte(type, message) {
        $('#alert-zone').html(`<div class="alert alert-${type}">${message}</div>`);
    }
});