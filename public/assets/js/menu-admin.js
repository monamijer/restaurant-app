$(document).ready(function () {
    const modal = new bootstrap.Modal(document.getElementById('modalPlat'));

    // Ouvrir en mode "Ajouter"
    $('#btn-nouveau-plat').on('click', function () {
        $('#form-plat')[0].reset();
        $('#plat-id').val('');
        $('#modal-titre').text('Ajouter un plat');
    });

    // Ouvrir en mode "Modifier" (pré-remplissage)
    $(document).on('click', '.btn-editer', function () {
        $('#modal-titre').text('Modifier le plat');
        $('#plat-id').val($(this).data('id'));
        $('#plat-nom').val($(this).data('nom'));
        $('#plat-description').val($(this).data('description'));
        $('#plat-prix').val($(this).data('prix'));
        $('#plat-categorie').val($(this).data('categorie'));
        $('#plat-vegetarien').prop('checked', $(this).data('vegetarien') == 1);
        $('#plat-sansgluten').prop('checked', $(this).data('sansgluten') == 1);
        $('#plat-epice').prop('checked', $(this).data('epice') == 1);
        modal.show();
    });

    // Soumission du formulaire (ajout OU modification selon présence de l'ID)
    $('#form-plat').on('submit', function (e) {
        e.preventDefault();

        const id = $('#plat-id').val();
        const url = id ? '/admin/menu/update' : '/admin/menu/store';
        const formData = new FormData(this);

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    afficherAlerte('success', response.message);
                    modal.hide();
                    setTimeout(() => location.reload(), 800);
                } else {
                    afficherAlerte('danger', response.message);
                }
            },
            error: function () {
                afficherAlerte('danger', 'Une erreur est survenue.');
            }
        });
    });

    // Suppression
    $(document).on('click', '.btn-supprimer', function () {
        if (!confirm('Supprimer ce plat définitivement ?')) return;

        const id = $(this).data('id');
        $.post('/admin/menu/delete', { id: id }, function (response) {
            if (response.success) {
                $(`tr[data-id="${id}"]`).fadeOut(300, function () { $(this).remove(); });
                afficherAlerte('success', response.message);
            }
        }, 'json');
    });

    // Toggle disponibilité (instantané, sans recharger)
    $(document).on('change', '.toggle-dispo', function () {
        const id = $(this).data('id');
        const disponible = $(this).is(':checked') ? 1 : 0;
        $.post('/admin/menu/toggle', { id: id, disponible: disponible });
    });

    function afficherAlerte(type, message) {
        $('#alert-zone').html(
            `<div class="alert alert-${type} alert-dismissible fade show">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`
        );
    }
});