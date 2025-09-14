function registerInit(options = {}) {
  const minPasswordLength = options.minPasswordLength ?? 8;
  const userIdLengths = options.userIdLengths ?? [1, 10];
  const captchaSize = options.captchaSize ?? null;
  const loginPageUrl = options.loginPageUrl ?? null;
  const registerPostUrl = options.registerPostUrl;
  const userNameApi = options.userNameApi;
  const tpinDigits = options.tpinDigits ?? 6;
  const registerCaptchaImageUrl = options.registerCaptchaImageUrl ?? null;
  const refer = options.refer ?? null;
  const sponsorLabel = options.sponsorLabel ?? "Sponsor";
  const sponsorIdLabel = options.sponsorIdLabel ?? "Sponsor Id";

  const isProduction = options.isProduction ?? false;

  const regFormSelector = "#register-form";

  function updateCaptcha(imgSelector, src, jq) {
    let eclass = "";
    if (jq) {
      eclass = jq.attr("class");
      jq.removeClass()
        .addClass("fa fa-spinner fa-spin")
        .css("pointer-events", "none");
    }

    var captchaImage = $(imgSelector);

    captchaImage.attr("src", src);

    if (jq) {
      captchaImage.on("load", function () {
        jq.attr("class", eclass);
        jq.css("pointer-events", "auto");
      });
    }
  }

  if (registerCaptchaImageUrl) {
    $("#reg_captcha_reload").on("click", function () {
      const timestamp = new Date().getTime();
      updateCaptcha(
        "#reg_captcha_img",
        `${registerCaptchaImageUrl}?twebsol=${timestamp}}`,
        $(this)
      );
    });
  }

  var reg_validation_rules = {
    sponsor_id: !refer
      ? {
          required: true,
          alpha_num: true,
          minlength: userIdLengths[0],
          maxlength: userIdLengths[1],
        }
      : null,

    full_name: {
      required: true,
      minlength: 2,
      maxlength: 100,
      alpha_num_space: true,
    },

    email: { required: true, maxlength: 200, email: true },

    // phone: { required: true, number: true, minlength: 10, maxlength: 12 },

    country_code: { required: true },

    currency: { required: true },

    password: {
      required: true,
      no_trailing_spaces: true,
      minlength: minPasswordLength,
    },

    cpassword: {
      required: true,
      no_trailing_spaces: true,
      minlength: minPasswordLength,
      equalTo: "._password",
    },

    tpin: {
      required: true,
      no_trailing_spaces: true,
      number: true,
      exactDigits: tpinDigits,
    },

    tnc: { required: true },
  };

  if (registerCaptchaImageUrl) {
    reg_validation_rules.captcha = {
      required: true,
      no_trailing_spaces: true,
      minlength: captchaSize,
    };
  }

  validateForm(regFormSelector, {
    rules: reg_validation_rules,
    messages: {
      tnc: { required: "Terms & Conditions must be accepted!" },
      cpassword: { equalTo: "Password does not match." },
    },
    submitHandler: function (form) {
      const regBtn = $("#reg_btn span");

      const btnContent = regBtn.html();

      const enableButton = () => {
        regBtn.html(btnContent);
        enable_form(regFormSelector);
        refer && disable_input("#sponsor_id");
        $("#reg_captcha_reload").css("pointer-events", "auto");
      };

      const formData = new FormData(form);

      if (refer && refer.user_id) formData.append("sponsor_id", refer.user_id);

      $.ajax({
        url: registerPostUrl,
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function () {
          regBtn.html(spinnerLabel());
          $("#reg_captcha_reload").css("pointer-events", "none");
          disable_form(regFormSelector);
        },
        complete: function () {
          enableButton();
        },
        success: function (res, textStatus, xhr) {
          if (!isProduction) console.log(res);

          if (xhr.status === 201) {
            // clearInputs(regFormSelector);
            // refer && $("#sponsor_id").val(refer.user_id);
            // !refer && $("#sid_alert").hide();

            // sAlert("success", "Account Registered!", res.html, {
            //   allowOutsideClick: false,
            //   showCancelButton: true,
            //   confirmButtonText: "Login",
            //   cancelButtonText: "Ok",
            //   customClass: {
            //     popup: "post_registration_popup",
            //   },
            // }).then((result) => {
            //   if (result.isConfirmed && loginPageUrl) {
            //     window.location.href = loginPageUrl;
            //   }
            // });

            // // new captcha
            // if (registerCaptchaImageUrl && res.captchaBase64)
            //   updateCaptcha(
            //     "#reg_captcha_img",
            //     `data:image/png;base64,${res.captchaBase64}`
            //   );

            if(res.otp_url) {
              location.href = res.otp_url;
            }
          }
        },
        error: function (xhr) {
          if (!isProduction) console.log(xhr);

          var res = xhr.responseJSON || xhr.responseText;

          if (xhr.status === 400 && res.errors) {
            if (res.errors.validationErrors)
              $(regFormSelector)
                .validate()
                .showErrors(res.errors.validationErrors);

            if (res.errors.error) {
              sAlert("error", "", res.errors.error);
            }
          }

          enableButton();
        },
      });

      return false;
    },
  });

  /*
   *------------------------------------------------------------------------------------
   * Sponsor Id Check (If there is no refer)
   *------------------------------------------------------------------------------------
   */
  !refer &&
    $("#sponsor_id").on("change", function () {
      const sid = $(this).val().trim();

      const alert = $("#sid_alert");
      const alertSpan = $("#sid_alert span");

      if (sid.length < userIdLengths[0] || sid.length > userIdLengths[1]) {
        return alert.hide();
      }

      const hasSuccess = (html) => {
        alert.removeClass("alert-danger").addClass("alert-success");
        alertSpan.html(html);
      };

      const hasError = (html) => {
        alert.removeClass("alert-success").addClass("alert-danger");
        alertSpan.html(
          `<span class="fa-solid fa-exclamation-circle me-2"></span>${html}`
        );
      };

      $.ajax({
        url: userNameApi,
        method: "POST",
        data: { user_id: sid, ...csrf_data() },
        beforeSend: function () {
          alertSpan.html(spinnerLabel({ type: "fa" }));

          alert.show();
        },
        complete: function () {
          alert.removeClass("alert-primary");
        },
        success: function (res, textStatus, xhr) {
          if (!isProduction) console.log(res);

          if (xhr.status === 200 && res.status == 3 && res.username) {
            const html = `<i class="fa-solid fa-user-check me-2"></i>${sponsorLabel} : <strong>${res.username}</strong>`;

            hasSuccess(html);
          }
        },
        error: function (xhr) {
          if (!isProduction) console.log(xhr);

          var res = xhr.responseJSON || xhr.responseText;

          if (xhr.status === 400 && res.status) {
            let msg = "";

            if (res.status === 1) {
              msg = `${sponsorIdLabel} is required!`;
            } else {
              msg = `${sponsorIdLabel} is invalid!`;
            }

            hasError(msg);
          }
        },
      });
    });

  /*
   *------------------------------------------------------------------------------------
   * Setting refer name on alert if there is a refer
   *------------------------------------------------------------------------------------
   */
  refer &&
    (function (refer) {
      $("#sid_alert").removeClass("alert-danger").addClass("alert-success");
      $("#sid_alert span").html(
        `<i class="fa-solid fa-user-check me-2"></i>${sponsorLabel} : <strong>${refer.name}</strong>`
      );
      $("#sid_alert").show();
    })(refer);
}
