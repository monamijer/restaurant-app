$(document).ready(function () {
    $('#form-parametres').on('submit', function (e) {
        e.preventDefault();

        const formData = $(this).serialize();

        $.ajax({
            url: '/admin/parametres/update',
            method: 'POST',
            data: formData,
            dataType: 'json',
            beforeSend: function () {
                $('#alert-zone').html('');
            },
            success: function (response) {
                if (response.success) {
                    $('#alert-zone').html(
                        `<div class="alert alert-success alert-dismissible fade show">
                            ${response.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>`
                    );
                }
            },
            error: function () {
                $('#alert-zone').html(
                    `<div class="alert alert-danger">Une erreur est survenue. Réessayez.</div>`
                );
            }
        });
    });
});