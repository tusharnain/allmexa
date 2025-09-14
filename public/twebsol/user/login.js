function loginInit(options = {}) {
  const userIdLengths = options.userIdLengths ?? [1, 10];
  const captchaSize = options.captchaSize ?? null;
  const dashboardUrl = options.dashboardUrl ?? null;
  const loginPostUrl = options.loginPostUrl;
  const loginCaptchaImageUrl = options.loginCaptchaImageUrl ?? null;
  const isEmailLoginAllowed = options.is_email_login_allowed ?? false;
  const userLabel = options.userLabel ?? "User";

  const isProduction = options.isProduction ?? false;

  const loginFormSelector = "#login-form";

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

  if (loginCaptchaImageUrl) {
    $("#login_captcha_reload").on("click", function () {
      const timestamp = new Date().getTime();
      updateCaptcha(
        "#login_captcha_img",
        `${loginCaptchaImageUrl}?twebsol=${timestamp}}`,
        $(this)
      );
    });
  }

  var login_validation_rules = {};

  if (isEmailLoginAllowed) {
    login_validation_rules.username = {
      required: true,
    };
  } else {
    login_validation_rules.user_id = {
      required: true,
      alpha_num: true,
      minlength: userIdLengths[0],
      maxlength: userIdLengths[1],
    };
  }


  login_validation_rules.password = {
    required: true
  };

  if (loginCaptchaImageUrl) {
    login_validation_rules.captcha = {
      required: true,
      no_trailing_spaces: true,
      minlength: captchaSize,
    };
  }

  validateForm(loginFormSelector, {
    rules: login_validation_rules,
    messages: {},
    submitHandler: function (form) {
      const btnSpan = $("#login_btn span");

      const btnContent = btnSpan.html();
      let stop_btn_enabling = false;

      const enableButton = () => {
        btnSpan.html(btnContent);
        enable_form(loginFormSelector);
      };

      $.ajax({
        url: loginPostUrl,
        method: "POST",
        data: new FormData(form),
        processData: false,
        contentType: false,
        beforeSend: function () {
          btnSpan.html(spinnerLabel({ type: "fa" }));

          disable_form(loginFormSelector);
          $("#login_captcha_reload").css("pointer-events", "none");
        },
        complete: function () {
          if (!stop_btn_enabling) {
            enableButton();
            $("#login_captcha_reload").css("pointer-events", "auto");
          }
        },
        success: function (res, textStatus, xhr) {
          if (!isProduction) console.log(res);

          if (xhr.status === 200) {
            stop_btn_enabling = true;

            btnSpan.html(
              `<i class="fa-solid fa-user-check me-2"></i> Login Sucess! Redirecting...`
            );

            $("#login_btn")
              .removeClass("btn-primary")
              .addClass("btn-success")
              .prop("disabled", false)
              .css("pointer-events", "none");

            if (res.redirectTo) window.location.href = res.redirectTo;
          }
        },
        error: function (xhr) {
          if (!isProduction) console.log(xhr);

          var res = xhr.responseJSON || xhr.responseText;

          if (xhr.status === 400 && res.errors) {
            if (res.errors.validationErrors)
              $(loginFormSelector)
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
}
