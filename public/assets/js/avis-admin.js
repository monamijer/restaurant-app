$(document).ready(function () {
    $(document).on('submit', '.form-reponse', function (e) {
        e.preventDefault();
        const id = $(this).data('id');
        const reponse = $(this).find('input[name="reponse"]').val();

        $.post('/admin/avis/repondre', { id: id, reponse: reponse }, function (response) {
            if (response.success) {
                afficherAlerte('success', response.message);
                setTimeout(() => location.reload(), 800);
            }
        }, 'json');
    });

    $(document).on('click', '.btn-supprimer-avis', function () {
        if (!confirm('Supprimer cet avis ?')) return;
        const id = $(this).data('id');

        $.post('/admin/avis/delete', { id: id }, function (response) {
            if (response.success) {
                $(`.stat-card[data-id="${id}"]`).closest('.col-md-6').fadeOut(300, function () { $(this).remove(); });
            }
        }, 'json');
    });

    function afficherAlerte(type, message) {
        $('#alert-zone').html(`<div class="alert alert-${type}">${message}</div>`);
    }
});