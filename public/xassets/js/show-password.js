(function () {
  for (let i = 1; i <= 3; i++) {
    let id = "Password-toggle" + i;

    $(`#${id} a`).on("click", function (event) {
      event.preventDefault();

      $(`#${id} i`).removeClass("far fa-eye-slash fa-eye");

      if ($(`#${id} input`).attr("type") == "text") {
        $(`#${id} input`).attr("type", "password");
        $(`#${id} i`).addClass("far fa-eye");
      } else if ($(`#${id} input`).attr("type") == "password") {
        $(`#${id} input`).attr("type", "text");
        $(`#${id} i`).addClass("far fa-eye-slash");
      }
    });
  }
})();
