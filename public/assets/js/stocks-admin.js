$(document).ready(function () {
    const modalIngredient = new bootstrap.Modal(document.getElementById('modalIngredient'));
    const modalAjust = new bootstrap.Modal(document.getElementById('modalAjustStock'));

    $('#btn-nouvel-ingredient').on('click', function () {
        $('#form-ingredient')[0].reset();
        $('#ing-id').val('');
        $('#modal-ingredient-titre').text('Ajouter un ingrédient');
    });

    $(document).on('click', '.btn-editer-ingredient', function () {
        $('#modal-ingredient-titre').text('Modifier l\'ingrédient');
        $('#ing-id').val($(this).data('id'));
        $('#ing-nom').val($(this).data('nom'));
        $('#ing-stock').val($(this).data('stock'));
        $('#ing-unite').val($(this).data('unite'));
        $('#ing-seuil').val($(this).data('seuil'));
        $('#ing-peremption').val($(this).data('peremption'));
        $('#ing-fournisseur').val($(this).data('fournisseur'));
        modalIngredient.show();
    });

    $('#form-ingredient').on('submit', function (e) {
        e.preventDefault();
        const id = $('#ing-id').val();
        const url = id ? '/admin/stocks/update' : '/admin/stocks/store';

        $.post(url, $(this).serialize(), function (response) {
            if (response.success) {
                afficherAlerte('success', response.message);
                modalIngredient.hide();
                setTimeout(() => location.reload(), 800);
            } else {
                afficherAlerte('danger', response.message);
            }
        }, 'json').fail(function () {
            afficherAlerte('danger', 'Une erreur est survenue.');
        });
    });

    $(document).on('click', '.btn-ajuster-stock', function () {
        $('#ajust-id').val($(this).data('id'));
        $('#ajust-nom-affiche').text($(this).data('nom'));
        $('#ajust-quantite').val($(this).data('stock'));
        $('#ajust-unite-affiche').text('Unité : ' + $(this).data('unite'));
        modalAjust.show();
    });

    $('#form-ajust-stock').on('submit', function (e) {
        e.preventDefault();
        $.post('/admin/stocks/ajuster', $(this).serialize(), function (response) {
            if (response.success) {
                afficherAlerte('success', response.message);
                modalAjust.hide();
                setTimeout(() => location.reload(), 800);
            } else {
                afficherAlerte('danger', response.message);
            }
        }, 'json');
    });

    $(document).on('click', '.btn-supprimer-ingredient', function () {
        if (!confirm('Supprimer cet ingrédient ?')) return;
        const id = $(this).data('id');

        $.post('/admin/stocks/delete', { id: id }, function (response) {
            if (response.success) {
                $(`tr[data-id="${id}"]`).fadeOut(300, function () { $(this).remove(); });
                afficherAlerte('success', response.message);
            }
        }, 'json');
    });

    function afficherAlerte(type, message) {
        $('#alert-zone').html(`<div class="alert alert-${type} alert-dismissible fade show">${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>`);
    }
});