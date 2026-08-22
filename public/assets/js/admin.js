$(document).ready(function () {
  $("#form-parametres").on("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    $.ajax({
      url: "/admin/parametres/update",
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (response) {
        if (response.success) {
          afficherToast("success", response.message);
          setTimeout(() => location.reload(), 1000);
        } else {
          afficherToast("danger", response.message);
        }
      },
      error: function (xhr) {
        afficherToast(
          "danger",
          "Une erreur est survenue (code " + xhr.status + ").",
        );
      },
    });
  });
});
