//Twebsol
// Jquery Required

function clearInputs(container) {
  $(container)
    .find(":input")
    .each(function () {
      var type = this.type;
      var tag = this.tagName.toLowerCase();
      if (
        type == "text" ||
        type == "password" ||
        type == "number" ||
        tag == "textarea"
      ) {
        this.value = "";
      } else if (type == "checkbox" || type == "radio") {
        this.checked = false;
      } else if (tag == "select") {
        $(this).val("");
      } else if ($(this).find(":input").length > 0) {
        clearNestedInputs(this);
      }
    });
}

function __copy_text(event, text, switchIcon) {
  var originalClass;
  navigator.clipboard.writeText(text).then(
    function () {
      if (switchIcon) {
        // Recursively find the <i> tag within the clicked element
        var findIcon = function ($element) {
          if ($element.is("i")) {
            // Save the original class
            originalClass = $element.attr("class");

            // Change the class to the checked-class attribute
            $element.attr("class", $element.attr("checked-class"));

            // Set a timeout to revert the class after 2 seconds
            setTimeout(function () {
              $element.attr("class", originalClass);
            }, 2000);
          } else {
            // Continue searching in child elements
            $element.children().each(function () {
              findIcon($(this));
            });
          }
        };

        // Start searching from the clicked element
        findIcon($(event.target));
      }
    },
    function () {
      // Handle errors if needed
      console.error("Unable to copy to clipboard.");
    }
  );
}

function copyTextFromElement(
  event,
  elementSelector,
  type = "val",
  switchIcon = false
) {
  let text = "";

  if (type == "text") text = $(elementSelector).text();
  else text = $(elementSelector).val();

  __copy_text(event, text, switchIcon);
}

function copyText(event, text, switchIcon = false) {
  __copy_text(event, text, switchIcon);
}

function disable_input(selector) {
  $(selector).prop("disabled", true);
}
function enable_input(selector) {
  $(selector).prop("disabled", false);
}

function disable_form(selector) {
  container = $(selector);
  container.find("input, select, textarea, button").prop("disabled", true);
  container.children().each(function () {
    disable_form($(this));
  });
}
function enable_form(selector) {
  container = $(selector);
  container.find("input, select, textarea, button").prop("disabled", false);
  container.children().each(function () {
    enable_form($(this));
  });
}

function spinnerLabel(options = {}) {
  const label = options.label ?? "Processing";
  return `<i class="fas fa-spinner fa-spin ${
    label.length == 0 ? "" : "me-2"
  }"></i> ${label}`;
}

function scrollToElement(target) {
  const $target = typeof target === "string" ? $(target) : target;

  if ($target.length > 0) {
    const offset = $target.offset().top - 100;
    $("html, body").animate(
      {
        scrollTop: offset,
      },
      50
    );
  }
}

function redirect(url) {
  window.location.href = url;
}

// NProgress
const npro = {
  start: () => {
    if (NProgress) NProgress.start();
  },
  end: () => {
    if (NProgress) NProgress.done();
  },
};

// Transparent Bg Spin
const transSpin = {
  selector: "#transparent-bg-spin",
  start: () => {
    if ($(transSpin.selector).length > 0) $(transSpin.selector).fadeIn("fast");
  },
  end: () => {
    if ($(transSpin.selector).length > 0) $(transSpin.selector).fadeOut("fast");
  },
};

function populateSelect(
  selectSelector,
  options,
  selectedValue = "",
  useKeyValueOrder = false
) {
  const selectElement = $(selectSelector);

  if (Array.isArray(options)) {
    // Handle array input
    options.forEach(function (value) {
      const option = $("<option>", {
        value: value,
        text: value,
      });

      if (value === selectedValue) {
        option.prop("selected", true);
      }

      selectElement.append(option);
    });
  } else if (typeof options === "object") {
    // Handle object input
    Object.entries(options).forEach(function ([key, value]) {
      const optionText = useKeyValueOrder ? value : key;
      const optionValue = useKeyValueOrder ? key : value;

      const option = $("<option>", {
        value: optionValue,
        text: optionText,
      });

      if (value === selectedValue) {
        option.prop("selected", true);
      }

      selectElement.append(option);
    });
  }
}

function makeFieldInvalid(selector, message) {
  if ($(selector).length <= 0) return;

  $(selector).addClass("is-invalid text-danger border border-danger");

  const msgContainer = $(selector).closest("div").find(".invalid-feedback");
  if (msgContainer.length > 0) {
    msgContainer.html(message);
  }
}

function makeFieldValid(selector) {
  if ($(selector).length <= 0) return;
  $(selector).removeClass("is-invalid text-danger border border-danger");
}

function scrollToBottom(behavior = "smooth") {
  // Get the height of the entire document
  var documentHeight = Math.max(
    document.body.scrollHeight,
    document.documentElement.scrollHeight
  );

  // Set the target scroll position to the bottom of the page
  var targetScroll = documentHeight - window.innerHeight;

  // Use smooth scroll for a smoother effect
  window.scrollTo({
    top: targetScroll,
    left: 0,
    behavior: behavior,
  });
}

function fDate(dateTime) {
  var options = {
    day: "numeric",
    month: "short",
    year: "numeric",
    hour: "numeric",
    minute: "numeric",
    hour12: true,
  };

  var date = new Date(dateTime);

  return new Intl.DateTimeFormat("en-US", options).format(date);
}

function light_encode(data, uriEncode) {
  const encoded = btoa(data);
  return uriEncode ? encodeURIComponent(encoded) : encoded;
}

function randomString(stringLength) {
  const characters =
    "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_-+=<>?";

  let randomString = "";

  for (let i = 0; i < stringLength; i++) {
    const randomIndex = Math.floor(Math.random() * characters.length);
    randomString += characters.charAt(randomIndex);
  }

  return randomString;
}

function previewImageOnUpload(inputSelector, previewSelector, options = {}) {
  $(inputSelector).on("change", function () {
    if (this.files && this.files[0]) {
      // Check if the selected file is a valid image type
      var allowedMimes = options.mimes || [
        "image/jpeg",
        "image/jpg",
        "image/png",
      ];
      if (allowedMimes.includes(this.files[0].type)) {
        var reader = new FileReader();

        reader.onload = function (e) {
          var imgTag = '<img src="' + e.target.result + '" alt="Image Preview"';

          // Check if options parameter is provided and has a class property
          if (options.class) {
            imgTag += ' class="' + options.class + '"';
          }

          imgTag += ">";

          $(previewSelector).html(imgTag);
        };

        reader.readAsDataURL(this.files[0]);
      } else {
        // Clear the preview if the file type is not allowed
        $(previewSelector).empty();
      }
    } else {
      // Clear the preview if no file is selected
      $(previewSelector).empty();
    }
  });
}

function showTextLoader(options = {}) {
  const loadingArea = options.loadingArea ?? ".loading-area";
  const loadingText = options.loadingText ?? "Please Wait";
  let loadingIcon = options.loadingIcon ?? null;
  const showDefaultIcon =
    options.showDefaultIcon === undefined ? true : options.showDefaultIcon;
  if (showDefaultIcon && !loadingIcon) {
    loadingIcon = "fa-solid fa-spinner fa-spin";
  }
  var loadingTextHtml = `${
    loadingIcon ? `<i class="${loadingIcon} me-1"></i>` : ""
  } ${loadingText} <span class="ld-dots"></span>`;
  var dots = "";
  $(loadingArea).html(loadingTextHtml);
  var interval = setInterval(function () {
    $(`${loadingArea} .ld-dots`).html(dots);
    dots += ".";
    if (dots.length > 3) {
      dots = "";
    }
  }, 500);
}

function initImageLazyLoading() {
  function loadImages(img) {
    var url = img.data("src");
    img.attr("src", url);
    img.on("load", function () {
      $(this).siblings(".lazy_i").remove();
      $(this).removeClass("shrink-hide");
    });
  }

  $(".lazy-image-container").each(function () {
    const container = $(this);
    const img = container.children("img").first();
    if (!img) return;
    img.addClass("shrink-hide");
    const loadingLabel = img.attr("data-loading-label") ?? "";
    container.append(`<span class="lazy_i d-flex align-items-center">
                      ${loadingLabel}<i class="fs-5 ms-2 fa-solid fa-spinner fa-spin"></i>
                  </span>`);
    const observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          loadImages($(entry.target));
          observer.unobserve(entry.target);
        }
      });
    });
    observer.observe(img[0]);
  });
}
$(document).ready(initImageLazyLoading);
