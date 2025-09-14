class ForgetAndResetPassword {
  static initForgetPassword(options = {}) {
    const { api, userIdLengths, isProduction } = options;
    const formSelector = "#fgt-form";

    const sendResetPasswordEmail = (user) => {
      const formEl = $("#fgt-form");
      const sendingEmailEl = $("#sending-email");
      const emailSentEl = $("#email-sent");
      $.ajax({
        url: api,
        method: "POST",
        data: {
          action: "reset_password_email",
          user_id: user.user_id,
          ...csrf_data(),
        },
        beforeSend: function () {
          formEl.hide();
          sendingEmailEl.find(".email").text(user.email);
          sendingEmailEl.show();
        },
        complete: function () {},
        success: function (res, textStatus, xhr) {
          isProduction && console.log(res);
          if (res.success) {
            const email = res.email;
            formEl.hide();
            sendingEmailEl.hide();
            emailSentEl.find(".email").text(email);
            emailSentEl.show();
          }
        },
        error: function (xhr) {
          isProduction && console.log(xhr);
          var res = xhr.responseJSON || xhr.responseText;
          if (xhr.status === 400 && res.errors) {
            if (res.errors.error) {
              sAlert("error", "", res.errors.error);
            }
          }
        },
      });
    };

    validateForm(formSelector, {
      rules: {
        user_id: {
          required: true,
          alpha_num: true,
          minlength: userIdLengths[0],
          maxlength: userIdLengths[1],
        },
      },
      submitHandler: function (form) {
        const formData = new FormData(form);
        const userId = formData.get("user_id");
        formData.append("action", "get_email");
        append_csrf(formData);
        const btnContent = $("#fgt_btn span").html();

        const enableButton = () => {
          $("#fgt_btn span").html(btnContent);
          enable_form(formSelector);
        };

        $.ajax({
          url: api,
          method: "POST",
          data: formData,
          processData: false,
          contentType: false,
          beforeSend: function () {
            $("#fgt_btn span").html(spinnerLabel({ label: "Processing" }));
            disable_form(formSelector);
          },
          complete: function () {
            enableButton();
          },
          success: function (res, textStatus, xhr) {
            isProduction && console.log(res);
            if (xhr.status === 200 && res.success && res.user) {
              sendResetPasswordEmail(res.user);
            }
          },
          error: function (xhr) {
            isProduction && console.log(xhr);
            var res = xhr.responseJSON || xhr.responseText;
            if (xhr.status === 400 && res.errors) {
              if (res.errors.error) {
                sAlert("error", "", res.errors.error);
              }
            }
          },
        });
        return false;
      },
    });
  }

  static initResetPassword(options = {}) {
    const {
      api,
      passwordMinLength,
      token,
      companyName,
      loginUrl,
      forgetPasswordUrl,
      validToken,
      isProduction,
    } = options;

    const formSelector = "#rstp-form";
    const mainCardEl = $("#main-card");

    const rstSuccessHtml = `<div class="text-center">
        <h4 class="text-success">
            <i class="fa-solid fa-check-circle"></i> Password Changed!
        </h4>
        <h6 class="mt-3">
            Your ${companyName} account password has been changed successfully!
        </h6>
        <a href="${loginUrl}">
            <button class="btn btn-primary mt-4">Login your account</button>
        </a>
    </div>`;

    const invalidTokenHtml = `<div class="invalid-token text-center">
    <h4 class="text-danger">
        <i class="fa-solid fa-exclamation-circle mt-2"></i> Invalid or Expired Token!
    </h4>
    <div class="text-start mt-4">
        <h6 class="mt-2">
            The Password Reset Link is either invalid or has been expired.
        </h6>
        <h6 class="mt-2">
            Click on below button, to initiate the reset password process.
        </h6>
        <div class="text-end">
            <a href="${forgetPasswordUrl}">
                <button class="btn btn-danger mt-4">Forgot Password ?</button>
            </a>
        </div>
    </div>
    </div>`;

    if (validToken) {
      validateForm(formSelector, {
        rules: {
          password: {
            required: true,
            no_trailing_spaces: true,
            minlength: passwordMinLength,
          },
          cpassword: {
            required: true,
            no_trailing_spaces: true,
            minlength: passwordMinLength,
            equalTo: "._password",
          },
        },
        submitHandler: function (form) {
          const formData = new FormData(form);
          formData.append("token", token);
          append_csrf(formData);
          const btnContent = $("#rstp_btn span").html();

          const enableButton = () => {
            $("#rstp_btn span").html(btnContent);
            enable_form(formSelector);
          };

          $.ajax({
            url: api,
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
              $("#rstp_btn span").html(spinnerLabel({ label: "Processing" }));
              disable_form(formSelector);
            },
            complete: function () {
              enableButton();
            },
            success: function (res, textStatus, xhr) {
              isProduction && console.log(res);
              if (xhr.status === 200 && res.success) {
                mainCardEl.html(rstSuccessHtml);
              }
            },
            error: function (xhr) {
              isProduction && console.log(xhr);
              var res = xhr.responseJSON || xhr.responseText;
              if (xhr.status === 400) {
                if (res.expired) {
                  mainCardEl.html(invalidTokenHtml);
                }
                if (res.errors) {
                  if (res.errors.validationErrors) {
                    $(formSelector)
                      .validate({ focusInvalid: true })
                      .showErrors(res.errors.validationErrors);
                    Swal.close();
                  }
                  if (res.errors.error) {
                    sAlert("error", "", res.errors.error);
                  }
                }
              }
            },
          });
          return false;
        },
      });
    } else {
      mainCardEl.html(invalidTokenHtml);
    }
  }
}
