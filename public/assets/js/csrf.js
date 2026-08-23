$(document).ready(function () {
  const token = $('meta[name="csrf-token"]').attr("content");
  $.ajaxSetup({
    headers: { "X-CSRF-TOKEN": token },
  });
});
