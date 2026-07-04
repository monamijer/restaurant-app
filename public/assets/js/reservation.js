$(document).ready(function () {
    $('#form-reservation').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: '/reserver',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (response) {
                if (!response.success) {
                    afficherAlerte('danger', response.message);
                    return;
                }

                if (!response.acompte_requis) {
                    afficherAlerte('success', response.message);
                    setTimeout(() => window.location.href = '/reservation/confirmation?id=' + response.reservation_id, 1200);
                    return;
                }

                // Acompte requis : redirection vers Stripe Checkout
                afficherAlerte('info', 'Redirection vers le paiement sécurisé...');
                $.post('/reservation/creer-paiement', { reservation_id: response.reservation_id }, function (paiement) {
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
        $('#alert-zone').html(
            `<div class="alert alert-${type}">${message}</div>`
        );
    }
});