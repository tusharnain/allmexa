// This extended file needs to be imported after jquery validator plugin js

$.validator.addMethod(
  "minlength",
  function (value, element, param) {
    value = $.trim(value);
    return this.optional(element) || value.length >= param;
  },
  $.validator.format("Please enter at least {0} characters.")
);

// Override the default maxlength method
$.validator.addMethod(
  "maxlength",
  function (value, element, param) {
    value = $.trim(value);
    return this.optional(element) || value.length <= param;
  },
  $.validator.format("Please enter no more than {0} characters.")
);

$.validator.addMethod(
  "email",
  function (value, element) {
    return (
      this.optional(element) ||
      /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(value.trim())
    );
  },
  "Please enter a valid email address."
);


$.validator.addMethod(
  "alpha_num",
  function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value.trim());
  },
  "Please enter valid input."
);

$.validator.addMethod(
  "alpha_num_space",
  function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9\s]+$/.test(value);
  },
  "Please enter valid input."
);

$.validator.addMethod(
  "no_trailing_spaces",
  function (value, element) {
    return this.optional(element) || /^\S(?:.*\S)?$/.test(value);
  },
  "This field should not start or end with spaces."
);

$.validator.addMethod(
  "mustBeEmpty",
  function (value, element) {
    return this.optional(element) || $.trim(value) === "";
  },
  "This field must be empty."
);

$.validator.addMethod(
  "exactLength",
  function (value, element, param) {
    return this.optional(element) || value.length === param;
  },
  $.validator.format("Please enter exactly {0} characters.")
);

$.validator.addMethod(
  "exactDigits",
  function (value, element, params) {
    return (
      this.optional(element) ||
      (/^\d+$/.test(value) && $.trim(value).length === parseInt(params))
    );
  },
  $.validator.format("Please enter exactly {0} digits.")
);

$.validator.addMethod(
  "filesize",
  function (value, element, param) {
    return this.optional(element) || element.files[0].size <= param;
  },
  "File size must be less than {0}"
);
$.validator.addMethod(
  "regex",
  function (value, element, pattern) {
    if (this.optional(element)) {
      return true; // Optional field, validation is passed
    }
    // Test the value against the regex pattern
    return pattern.test(value);
  },
  "Please enter a valid value."
);

function validateForm(formSelector, options = {}) {
  // Rules and messages are required in options variable

  $(formSelector).validate({
    errorElement: "span",
    errorPlacement: function (error, element) {
      var errorContainer = element.closest("div").find(".invalid-feedback");
      errorContainer.empty();

      error.appendTo(errorContainer);
    },
    highlight: function (element) {
      $(element).addClass("is-invalid text-danger border border-danger");
    },
    unhighlight: function (element) {
      $(element).removeClass("is-invalid text-danger border border-danger");
    },
    ...options,
  });
}

$.validator.addMethod(
  "multipleOf",
  function (value, element, param) {
    return this.optional(element) || value % param === 0;
  },
  "Please enter a multiple of {0}."
);
