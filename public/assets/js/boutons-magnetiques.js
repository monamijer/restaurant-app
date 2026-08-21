$(document).ready(function () {
  $(".btn-magnetic").on("mousemove", function (e) {
    const rect = this.getBoundingClientRect();
    const x = e.clientX - rect.left - rect.width / 2;
    const y = e.clientY - rect.top - rect.height / 2;
    $(this).css("transform", `translate(${x * 0.25}px, ${y * 0.25}px)`);
  });

  $(".btn-magnetic").on("mouseleave", function () {
    $(this).css("transform", "translate(0, 0)");
  });
});
