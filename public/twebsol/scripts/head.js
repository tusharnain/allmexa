if (window.history.replaceState) {
  window.history.replaceState(null, null, window.location.href);
}

function sAlertServerError1() {
  sAlert(
    "error",
    "Something Went Wrong!",
    "Some Error/Exception has been occurred <br /> Try refreshing the page <br /> or Try again later"
  );
}

document.addEventListener("DOMContentLoaded", function () {
  // Your code that relies on jQuery
  $(document).on("ajaxComplete", function (event, xhr, settings) {
    const res = xhr.responseJSON || xhr.responseTEXT;

    switch (xhr.status) {
      /*
       *------------------------------------------------------------------------------------
       * Server Error
       *------------------------------------------------------------------------------------
       */
      case 500: {
        if (
          res !== undefined &&
          res.title !== undefined &&
          res.title.includes("Honeypot")
        ) {
          return sAlert(
            "error",
            "That input wasn't for you!",
            "Remove that input you just filled, and then try again."
          );
        }

        sAlertServerError1();

        break;
      }
    }
  });

  $(document).on("ajaxError", function (event, request, settings) {
    if (request.status === 400) {
      try {
        var res = JSON.parse(request.responseText);

        if (res && res.f_redirect) {
          event.preventDefault();
          window.location.href = res.f_redirect;
        }
      } catch (error) {
        console.error("Error parsing JSON response:", error);
      }
    }
  });
});

function jqueryElementAction(selector, callback) {
  if ($(selector).length <= 0) {
    location.reload();
    return;
  }

  return callback($(selector));
}

// csrfs object
function csrf_data() {
  if ($("#csrf").length <= 0) return location.reload();
  const csrfName = $("#csrf").attr("name").trim();
  const csrfHash = $("#csrf").attr("content").trim();
  return { [csrfName]: csrfHash };
}

function append_csrf(formData) {
  if ($("#csrf").length <= 0) return location.reload();
  const csrfName = $("#csrf").attr("name").trim();
  const csrfHash = $("#csrf").attr("content").trim();
  return formData.append(csrfName, csrfHash);
}

function get_user_id_from_meta() {
  if ($("#meta_user_id").length <= 0) return location.reload();
  const user_id = $("#meta_user_id").attr("content").trim();
  if (user_id && user_id.length > 0) return user_id;
  return null;
}
