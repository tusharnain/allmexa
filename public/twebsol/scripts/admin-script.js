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
    confirmButtonText: "Yes, confirm it!",
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

class toast {
  static destroyIfNeeded = () => {
    if ($(".iziToast-capsule").length >= 3) {
      $(".iziToast-capsule").eq(0).remove();
    }
  };

  static info = (message, title = "") => {
    toast.destroyIfNeeded();
    iziToast.info({ title, message });
  };

  static success = (message, title = "") => {
    toast.destroyIfNeeded();
    iziToast.success({ title, message });
  };

  static warning = (message, title = "") => {
    toast.destroyIfNeeded();
    iziToast.warning({ title, message });
  };

  static error = (message, title = "") => {
    toast.destroyIfNeeded();
    iziToast.error({ title, message });
  };
}
