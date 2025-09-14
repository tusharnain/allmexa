function sAlert(icon, title, html, options = {}) {
  return Swal.fire({
    title: title,
    html: html,
    icon: icon,
    ...options,
  });
}

function sConfirm(cb, options = {}) {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: !0,
    confirmButtonText: options.confirmButtonText ?? "Yes, confirm it!",
    showLoaderOnConfirm: true,
    preConfirm: () => {
      return new Promise((resolve, reject) => {
        cb();
      });
    },
    allowOutsideClick: () => !Swal.isLoading(),

    ...options,
  });
}

function sProcessingPopup(title = "", text = "", options = {}) {
  Swal.fire({
    title,
    text,
    showConfirmButton: false,
    showCancelButton: false,
    allowOutsideClick: false,
    showLoaderOnConfirm: true,
    preConfirm: () => {},
    didOpen: () => {
      Swal.showLoading();
    },
    ...options,
  });
}

function showModal(selector) {
  $(selector).modal("show"); // jquery
}

function notify(notif = {}, options = {}) {
  //bootstrap notify
  $.notify(
    {
      title: "",
      message: "",
      ...notif,
    },
    {
      type: "primary",
      allow_dismiss: true,
      newest_on_top: true,
      mouse_over: true,
      showProgressbar: false,
      spacing: 10,
      timer: 2000,
      placement: {
        from: "top",
        align: "right",
      },
      offset: {
        x: 30,
        y: 30,
      },
      delay: 1000,
      z_index: 10000,
      animate: {
        enter: "animated fadeIn",
        exit: "animated fadeOut",
      },
      ...options,
    }
  );
}
