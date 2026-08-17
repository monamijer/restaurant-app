$(document).ready(function () {

    $('#form-nouvelle-categorie').on('submit', function (e) {
        e.preventDefault();

        $.post('/admin/categories/store', $(this).serialize(), function (response) {
            if (response.success) {
                afficherAlerte('success', response.message);
                setTimeout(() => location.reload(), 700);
            } else {
                afficherAlerte('danger', response.message);
            }
        }, 'json').fail(function () {
            afficherAlerte('danger', 'Une erreur est survenue.');
        });
    });

    $(document).on('click', '.btn-renommer', function () {
        const id = $(this).data('id');
        const ancienNom = $(this).data('nom');
        const nouveauNom = prompt('Nouveau nom de la catégorie :', ancienNom);

        if (!nouveauNom || nouveauNom.trim() === '' || nouveauNom === ancienNom) return;

        $.post('/admin/categories/update', { id: id, nom: nouveauNom }, function (response) {
            if (response.success) {
                afficherAlerte('success', response.message);
                setTimeout(() => location.reload(), 700);
            } else {
                afficherAlerte('danger', response.message);
            }
        }, 'json');
    });

    $(document).on('click', '.btn-supprimer-categorie', function () {
        if (!confirm('Supprimer cette catégorie ?')) return;
        const id = $(this).data('id');

        $.post('/admin/categories/delete', { id: id }, function (response) {
            if (response.success) {
                $(`li[data-id="${id}"]`).fadeOut(300, function () { $(this).remove(); });
                afficherAlerte('success', response.message);
            } else {
                afficherAlerte('danger', response.message);
            }
        }, 'json');
    });

    function afficherAlerte(type, message) {
        $('#alert-zone').html(`<div class="alert alert-${type} alert-dismissible fade show">${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>`);
    }
});
