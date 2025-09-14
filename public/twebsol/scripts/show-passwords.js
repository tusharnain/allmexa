// Jquery required!

(function () {
  for (let i = 1; i <= 5; i++) {
    let id = "Password-toggle" + i;

    if ($(`#${id} a`).length == 0) continue;

    $(`#${id} a`).on("click", function (event) {
      event.preventDefault();

      $(`#${id} i`).removeClass("fa-solid fa-eye fa-eye-slash");

      if ($(`#${id} input`).attr("type") == "text") {
        $(`#${id} input`).attr("type", "password");
        $(`#${id} i`).addClass("fa-solid fa-eye");
      } else if ($(`#${id} input`).attr("type") == "password") {
        $(`#${id} input`).attr("type", "text");
        $(`#${id} i`).addClass("fa-solid fa-eye-slash");
      }
    });
  }
})();
