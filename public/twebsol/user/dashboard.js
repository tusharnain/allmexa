// Ui Html file must be loaded first

function convertNumberToString(input) {
  if (typeof input === "number") {
    // If the input is a number, convert it to a string
    return input.toString();
  } else if (typeof input === "string") {
    // If the input is already a string, return it as is
    return input;
  } else {
    console.error(`${input} is not a valid number.`);
    return null; // or any other appropriate value
  }
}

/*
 *------------------------------------------------------------------------------------
 * Setup Sidebar
 *------------------------------------------------------------------------------------
 */
function setupSidebar(sidebar, options = {}) {
  const sidebarParentSelector = "#sidebar-parent";

  const dashboardUrl = options.dashboardUrl ?? "#";
  const logoDirectory = options.logoDirectory ?? "";

  let sidebarHtml = "";

  sidebar.forEach((menu) => {
    let menuLi = "";

    if (menu.submenus) {
      let submenuHtml = "";

      menu.submenus.forEach((submenu) => {
        submenuHtml += `<li><a href="${submenu.url}">${submenu.title}</a></li>`;
      });

      const submenuUl = `<ul class="sidebar-submenu">${submenuHtml}</ul>`;

      const isDownload = submenuHtml.download ?? null;

      menuLi = `<li class="sidebar-list">
      <i class="fa-solid fa-thumb-tack"></i>
            <a class="sidebar-link sidebar-title" href="${menu.url}" ${isDownload ? 'download' : ''}>
              <i class="${menu.icon} side-icon me-2"></i>
              <span>${menu.title}</span>
            </a>
            ${submenuUl}
          </li>`;
    } else {
      menuLi = `
      <li class="sidebar-list">
            <i class="fa-solid fa-thumb-tack"></i>
            <a class="sidebar-link sidebar-title link-nav" href="${menu.url}">
              <i class="${menu.icon} side-icon me-2"></i>
              <span>
              ${menu.title}
              </span>
            </a>
          </li>`;
    }

    sidebarHtml += menuLi;
  });

  $(`<div>
  <div class="logo-wrapper">
    <a href="${dashboardUrl}">
      <img class="img-fluid for-light" src="${logoDirectory}/logo.png" alt="">
      <img class="img-fluid for-dark" src="${logoDirectory}/logo_dark.png" alt="">
    </a>
    <div class="back-btn">
      <i class="fa-solid fa-angle-left"></i>
    </div>
    <div class="toggle-sidebar">
      <i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i>
    </div>
  </div>
  <div class="logo-icon-wrapper">
    <a href="${dashboardUrl}">
      <img class="img-fluid" src="${logoDirectory}/logo-icon.png" alt=""></a>
  </div>

  <nav class="sidebar-main">
    <div class="left-arrow" id="left-arrow">
      <i data-feather="arrow-left"></i>
    </div>

    <div id="sidebar-menu">


      <ul class="sidebar-links" id="simple-bar">


        <li class="back-btn">
          <a href="${dashboardUrl}">
            <img class="img-fluid" src="${logoDirectory}/logo-icon.png" alt=""></a>
          <div class="mobile-back text-end">
            <span>Back</span>
            <i class="fa-solid fa-angle-right ps-2" aria-hidden="true"></i>
          </div>
        </li>


        <li class="pin-title sidebar-main-title">
          <div>
            <h6>Pinned</h6>
          </div>
        </li>


        <li class="sidebar-main-title">
        <div>
          <h6>Menu</h6>
        </div>
      </li>


        ${sidebarHtml}



      </ul>
    </div>
    <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
  </nav>
</div>`).appendTo(sidebarParentSelector);
}

/*
 *------------------------------------------------------------------------------------
 * Setup Navbar
 *------------------------------------------------------------------------------------
 */
function setupHeader(options = {}) {
  const profilePicUrl = options.profilePicUrl ?? "";
  const profileUrl = options.profileUrl ?? "";
  const changePasswordUrl = options.changePasswordUrl ?? "";
  const userName = options.userName ?? "";
  const logoDirectory = options.logoDirectory ?? "";
  const dashboardUrl = options.dashboardUrl ?? "#";
  const userAssetDir = options.userAssetDir ?? "";

  const headerHtml = `
  <div class="header-wrapper row m-0">
    <form class="form-inline search-full col" action="#" method="get">
      <div class="form-group w-100">
        <div class="Typeahead Typeahead--twitterUsers">
          <div class="u-posRelative">
            <input class="demo-input Typeahead-input form-control-plaintext w-100" type="text"
              placeholder="Search Cuba .." name="q" title="" autofocus>
            <div class="spinner-border Typeahead-spinner" role="status"><span class="sr-only">Loading...</span></div><i
              class="close-search" data-feather="x"></i>
          </div>
          <div class="Typeahead-menu"></div>
        </div>
      </div>
    </form>
    <div class="header-logo-wrapper col-auto p-0">
      <div class="logo-wrapper"><a href="${dashboardUrl}"><img class="img-fluid"
            src="${logoDirectory}/logo.png" alt=""></a></div>
      <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="align-center"></i></div>
    </div>
    <div class="nav-right col-xxl-7 col-xl-6 col-md-7 col-8 pull-right right-header p-0 ms-auto">
      <ul class="nav-menus">
        <li>
          <div class="mode">
            <svg>
              <use href="${userAssetDir}/svg/icon-sprite.svg#moon"></use>
            </svg>
          </div>
        </li>
        <li class="profile-nav onhover-dropdown pe-0 py-0">
          <div class="media profile-media">
          <img class="pfp_image_img b-r-10 img-30" style="object-fit:cover; height : 30px;"
              src="${profilePicUrl}" alt="user">
            <div class="media-body"><span>${userName}</span>
              <p class="mb-0 font-roboto">Admin <i class="middle fa fa-angle-down"></i></p>
            </div>
          </div>
          <ul class="profile-dropdown onhover-show-div">
            <li><a href="${profileUrl}"><i data-feather="user"></i><span>Profile</span></a></li>
            <li><a href="${changePasswordUrl}"><i data-feather="lock"></i><span>Change Password</span></a></li>
            <li><a onclick="$('#logoutForm').submit();"><i data-feather="log-in"> </i><span>Log out</span></a></li>
          </ul>
        </li>
      </ul>
    </div>
    <script class="result-template" type="text/x-handlebars-template">
      <div class="ProfileCard u-cf">                        
            <div class="ProfileCard-avatar"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay m-0"><path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path><polygon points="12 15 17 21 7 21 12 15"></polygon></svg></div>
            <div class="ProfileCard-details">
            <div class="ProfileCard-realName">{{name}}</div>
            </div>
            </div>
          </script>
    <script class="empty-template"
      type="text/x-handlebars-template"><div class="EmptyMessage">Your search turned up 0 results. This most likely means the backend is down, yikes!</div></script>
  </div>`;

  $(headerHtml).appendTo("#header-parent");
}

/*
 *------------------------------------------------------------------------------------
 * Setup Footer
 *------------------------------------------------------------------------------------
 */
function setupFooter(options = {}) {
  const companyName = options.companyName ?? "";
  const currentYear = options.currentYear ?? "";
  const developer = options.developer ?? "";
  const dashboardUrl = options.dashboardUrl ?? "#";

  const footerHtml = `<div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 footer-copyright text-center">
                            <p class="mb-0">Copyright
                            ${currentYear} © <a href="${dashboardUrl}">${companyName}</a>
                            </p>
                        </div>
                    </div>
                </div>`;
  $(footerHtml).appendTo("#footer-parent");
}

/*
 *------------------------------------------------------------------------------------
 * Direct Referrals Table Setup
 *------------------------------------------------------------------------------------
 */

function setupDirectReferralsTable(users, options = {}) {
  const userIdLabel = options.userIdLabel ?? "User Id";
  const userNameLabel = options.userNameLabel ?? "User Name";
  const activeStatusLabel = options.activeStatusLabel ?? "Active";
  const inactiveStatusLabel = options.inactiveStatusLabel ?? "Not Active";
  const tableTitle = options.tableTitle ?? "Direct Referred Users";
  const indexInitNumber = options.indexInitNumber ?? 1;
  const paginationHtml = options.paginationHtml ?? "";
  const hasRecords = options.hasRecords ?? true;
  const defaultAvatarImage = options.defaultAvatarImage ?? "";
  const avatarDirectoryPath = options.avatarDirectoryPath ?? "";

  const pag = hasRecords
    ? `<div class="float-end m-3">${paginationHtml}</div>`
    : "";

  let rows = "";

  let i = indexInitNumber;

  if (hasRecords) {
    users.map((user) => {
      let userStatusLabel =
        user.status == true ? activeStatusLabel : inactiveStatusLabel;

      const color = user.status == true ? "success" : "danger";

      const avatarImageUrl = user.profile_picture
        ? `${avatarDirectoryPath}/${user.profile_picture}`
        : defaultAvatarImage;

      rows += `<tr>
    <td scope="row">
        ${++i}
    </td>
    <td class="text-center">
      <img class="user-avatar-table object-fit-cover"
        src="${avatarImageUrl}" alt="user-avatar" />
    </td>
    <td>
        ${user.user_id}
    </td>
    <td>
         ${user.full_name}
    </td>
    <td>
      ${user.email}
    </td>
    <td class="text-center">
        <span class="badge rounded-pill badge-${color}">
            ${userStatusLabel}
        </span>
    </td>
    <td class="text-end">
      ${user.total_investment}
    </td>
    <td>
        ${user.created_at ? fDate(user.created_at) : ""}
    </td>
    <td>
        ${user.activated_at ? fDate(user.activated_at) : "N/A"}
    </td>
</tr>`;
    });
  } else {
    rows = `<tr>
    <td colspan="20" class="text-center">0 records found</td>
</tr>`;
  }

  rows += `<tr></tr>`;

  const html = `<div class="card">
                <div class="card-header">
                    <h5>${tableTitle}</h5>
                </div>
                <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap">
                        <thead>
                            <tr class="border-bottom-primary">
                                <th scope="col">#</th>
                                <th scope="col">Avatar</th>
                                <th scope="col">
                                    ${userIdLabel}
                                </th>
                                <th scope="col">
                                    ${userNameLabel}
                                </th>
                                <th scope="col">Email</th>
                                <th scope="col">Status</th>
                                <th scope="col">Total Investment</th>
                                <th scope="col">Joining Date/Time</th>
                                <th scope="col">Activation Date/Time</th>
                            </tr>
                        </thead>
                        <tbody>
                          ${rows}
                        </tbody>
                    </table>
                </div>
                <div class="text-end">${pag}</div>
            </div>
            </div>
            `;
  jqueryElementAction("#direct-referrals-table-parent", function (ele) {
    ele.html(html);
  });
}

class Dashboard {
  static dashboardApi;


  /*
   *------------------------------------------------------------------------------------
   * Setup Refer Link Section
   *------------------------------------------------------------------------------------
   */
  static setupReferLink(options) {
    const referLink = options.referLink;
    const referLinkInput = options.referLinkInput;
    const userAssetDir = options.userAssetDir ?? null;

    const html = `<div class="card small-widget">
  <div class="card-body primary ps-4">
      <span class="f-light mb-3">
          <h6 class="mb-3">
              Referral Link
          </h6>
      </span>

      ${referLinkInput}

      <div class="userDashSocialShare">
          <ul class="socialList">
              <li>
                  <a href="${referLink}" target="_blank">
                      <i class="fa-solid fa-square-arrow-up-right"></i>
                  </a>
              </li>
              <li>
                  <a id="cpy_refer_link" href="javascript: void(0);">
                      <i class="fa-solid fa-clipboard"></i>
                  </a>
              </li>
              <li>
                  <a href="https://api.whatsapp.com/send?text=${referLink}" target="_blank">
                      <i class="fa-brands fa-whatsapp"></i>
                  </a>
              </li>
          </ul>
      </div>
  </div>
</div>`;
    $("#refer_link_stack").html(html);
    $("#cpy_refer_link").on("click", function () {
      navigator.clipboard
        .writeText(referLink)
        .then(function () {
          notify({
            title: "Refer Link Copied!",
            message: referLink,
          });
        })
        .catch(function () {
          notify(
            {
              title: "Error Copying Link!",
              message: "Please try copying the link manually.",
            },
            {
              type: "danger",
            }
          );
        });
    });
  }

  /*
   *------------------------------------------------------------------------------------
   * Setup Dashboard Widgetts
   *------------------------------------------------------------------------------------
   */
  static setupDashboardWidgets(options = {}) {
    const widgets = options.widgets;
    const dashboardApi = options.api;
    const widgetStack = options.widgetStack;
    const widgetHtml = options.widgetHtml;
    const widgetPlaceholder = options.widgetPlaceholder ?? "...";
    const defaultIcon = options.defaultIcon ?? "fa-solid fa-user";

    function pushWidget(widget) {
      let html = widgetHtml
        .replaceAll("{widget_id}", `wd_${widget.component}`)
        .replaceAll("{widget_label}", widget.label)
        .replaceAll("{widget_number}", widgetPlaceholder)
        .replaceAll("{widget_icon}", widget.icon ?? defaultIcon);

      $(html).appendTo(widgetStack);
    }

    function updateWidget(widget, data) {
      data = convertNumberToString(data);

      const widgetId = `#wd_${widget.component}`;

      $(`${widgetId} .dw_data`).text(data);
    }

    function fetchWidgetData(widget) {
      $.post(
        dashboardApi,
        {
          action: "widget_component",
          component: widget.component,
          ...csrf_data(),
        },
        function (res) {
          if (res.data != null && res.data != undefined) {
            updateWidget(widget, res.data);
          }
        }
      );
    }

    // execution
    widgets.forEach((widget) => {
      pushWidget(widget);
    });

    widgets.forEach((widget) => {
      fetchWidgetData(widget);
    });
  }

  /*
   *------------------------------------------------------------------------------------
   * User Earning Chart Setup
   *------------------------------------------------------------------------------------
   */
  static setupEarningChartWidget(options = {}) {
    if (!Dashboard.dashboardApi)
      throw new error("Dashboard api has not provided!");
    const html = `<div class="card">
  <div class="card-header py-4">
      <h6>Last 7 Days Earnings</h6>
  </div>
  <div class="card-body">
      <div id="earning-chart">
          <h6 class="earning-chart-loading-area text-secondary"></h6>
      </div>
  </div>
</div>`;
    $("#earning_chart_stack")
      .html(html)
      .ready(function () {
        showTextLoader({
          loadingArea: ".earning-chart-loading-area",
          loadingText: "Loading Chart",
        });
        $.post(
          Dashboard.dashboardApi,
          {
            action: "earning_chart",
            ...csrf_data(),
          },
          (res) => {
            $("#earning-chart")
              .children()
              .fadeOut("fast", function () {
                $(this).remove();
              });
            const earningChart = new ApexCharts(
              document.querySelector("#earning-chart"),
              {
                chart: {
                  height: res.earnings_array.some((number) => number > 0)
                    ? 300
                    : 150,
                  type: "area",
                  toolbar: {
                    show: false,
                  },
                },
                dataLabels: {
                  enabled: true,
                  formatter: function (
                    value,
                    { seriesIndex, dataPointIndex, w }
                  ) {
                    return res.earnings_f_array[dataPointIndex];
                  },
                },
                stroke: {
                  curve: "smooth",
                  width: 2,
                },
                series: [
                  {
                    name: "Earning",
                    data: res.days_array.map((date, index) => ({
                      x: new Date(date).getTime(), // Convert date to timestamp
                      y: res.earnings_array[index],
                    })),
                  },
                ],
                xaxis: {
                  type: "category",
                  labels: {
                    formatter: function (value) {
                      const date = new Date(value);
                      const day = ("0" + date.getDate()).slice(-2);
                      const month = date.toLocaleString("default", {
                        month: "short",
                      });
                      return `${day} ${month}`;
                    },
                  },
                },
                tooltip: {
                  x: {
                    formatter: function (value) {
                      const date = new Date(value);
                      const day = ("0" + date.getDate()).slice(-2);
                      const month = date.toLocaleString("default", {
                        month: "short",
                      });
                      const year = date.getFullYear();
                      return `${day} ${month}, ${year}`;
                    },
                  },
                  y: {
                    formatter: function (
                      value,
                      { seriesIndex, dataPointIndex, w }
                    ) {
                      return res.earnings_f_array[dataPointIndex];
                    },
                  },
                },
                colors: ['#FF2B00'],
              }
            );
            earningChart.render();
          }
        );
      });
  }

  /*
   *------------------------------------------------------------------------------------
   * Fetch User Name from User Id and set to dom
   *------------------------------------------------------------------------------------
   */
  static fetchAndSetUsernameToDom(options = {}) {
    const api = options.api ?? "";
    const user_id = options.user_id ?? "";
    const nameInputSelector = options.nameInputSelector ?? "#user_name_f";

    const nameSelector = $(nameInputSelector);

    if (user_id == "" && options.error) {
      options.error(1);
      return;
    }

    $.ajax({
      url: api,
      method: "POST",
      data: { user_id, ...csrf_data() },
      beforeSend: function () { },
      complete: function () { },
      success: function (res, textStatus, xhr) {
        if (options.isProduction) {
          console.log(res);
        }

        if (options.success) {
          options.success(res.username);
        } else {
          nameSelector
            .val(res.username)
            .removeClass("is-invalid text-danger")
            .addClass("is-valid text-success");
        }
      },
      error: function (xhr) {
        if (options.isProduction) {
          console.log(xhr);
        }

        var res = xhr.responseJSON || xhr.responseText;
        const statusCode = xhr.status;

        if (options.error) {
          options.error(res.status);
        } else {
          const errorStatus = res.status;
          if (errorStatus == 1)
            nameSelector
              .val("")
              .removeClass("is-invalid is-valid text-success text-danger");
          else if (errorStatus == 2)
            nameSelector
              .val("Invalid User Id")
              .addClass("is-invalid text-danger");
        }
      },
    });
  }

  /*
   *------------------------------------------------------------------------------------
   * Dashboard HomePage setupProfile
   *------------------------------------------------------------------------------------
   */
  static setupProfile() {

    $.post(
      Dashboard.dashboardApi,
      {
        action: "profile",
        ...csrf_data(),
      },
      function (res) {
        if (res.user != null && res.user != undefined) {
          const user = res.user;
          const html = `<div class="card custom-card pb-0 pt-2">
          <div class="text-center mt-3">
            <img class="profile-card-img rounded-circle ${user.status == 1
              ? "active_user_avatar_border"
              : "inactive_user_avatar_border"
            }" src="${user.pfp}" alt="user profile pic /">
          </div>
      
          <div class="text-center profile-details">
              <h5 class="my-2">${user.full_name}</h5>
              <h6 class="my-2">${user.user_id}</h6>
          </div>

          <div class="table-responsive my-3 p-3">
            <table class="table table-bordered text-nowrap">
              <tbody>
                <tr><td>Phone No.</td> <td class="text-end">${user.phone
            }</td></tr>
                <tr><td>Email</td> <td class="text-end">${user.email}</td></tr>
                <tr><td>Current Login</td> <td class="text-end">${user.currentLogin
            }</td></tr>
                <tr><td>Last Login</td> <td class="text-end">${user.lastLogin
            }</td></tr>
                <tr><td>Joining Date</td> <td class="text-end">${user.joiningDate
            }</td></tr>
                <tr><td>Activation Date</td> <td class="text-end">${user.activationDate
            }</td></tr>
              </tbody>
            </table>
          </div>
      </div>`;

          $("#profile_stack").fadeOut("fast", function () {
            $("#profile_stack").hide().html(html).slideDown();
          });
        }
      }
    );

  }

  static setupP2pTransferForm(options = {}) {
    const userIdLabel = options.userIdLabel;
    const userNameApi = options.userNameApi;
    const transferPostUrl = options.transferPostUrl;
    var currBal = options.currBal;

    const formSelector = options.formSelector;
    const userIdLengths = options.userIdLengths;
    const addDeductAmountRange = options.addDeductAmountRange;
    const tpin_digits = options.tpin_digits;

    const isProduction = options.isProduction;

    /*
     *------------------------------------------------------------------------------------
     * User Name Fetch
     *------------------------------------------------------------------------------------
     */
    $("#user_id_f").on("change", function () {
      const user_id = $(this).val();
      Dashboard.fetchAndSetUsernameToDom({ user_id, api: userNameApi });
    });

    /*
     *------------------------------------------------------------------------------------
     * P2P Transfer form
     *------------------------------------------------------------------------------------
     */
    // validating
    validateForm(formSelector, {
      rules: {
        user_id: {
          required: true,
          alpha_num: true,
          minlength: userIdLengths[0],
          maxlength: userIdLengths[1],
        },
        amount: {
          required: true,
          number: true,
          min: addDeductAmountRange[0],
          max: addDeductAmountRange[1],
        },
        remarks: { required: false, minlength: 1, maxlength: 250 },
        tpin: {
          required: true,
          no_trailing_spaces: true,
          number: true,
          exactDigits: tpin_digits,
        },
      },
      submitHandler: function (form) {
        if (get_user_id_from_meta() === $("#user_id_f").val().trim()) {
          sAlert(
            "error",
            "",
            `You've entered your own ${userIdLabel} mistakenly.`
          );
          return;
        }

        if ($("#amount_f").val().trim() > currBal) {
          sAlert("error", "", `You do not have required balance to transfer.`);
          return;
        }

        sConfirm(
          function () {
            const formData = new FormData(form);
            formData.append("action", "make_amount_transfer");
            const btnContent = $("#p2p_transfer_button span").html();

            const enableButton = () => {
              $("#p2p_transfer_button span").html(btnContent);
              enable_form(formSelector);
              $(formSelector + " .data_disabled").prop("disabled", true);
            };

            $.ajax({
              url: transferPostUrl,
              method: "POST",
              data: formData,
              processData: false,
              contentType: false,
              beforeSend: function () {
                $("#p2p_transfer_button span").html(
                  spinnerLabel({ label: "Processing P2P Transfer" })
                );
                disable_form(formSelector);

                //loading
                sProcessingPopup(
                  "Processing P2P Transfer...",
                  "Do not close this window!"
                );
              },
              complete: function () {
                enableButton();
              },
              success: function (res, textStatus, xhr) {
                if (!isProduction) console.log(res);

                if (xhr.status === 200) {
                  if (res.title && res.message)
                    sAlert("success", res.title, res.message);

                  jqueryElementAction("#wallet_balance", (el) => {
                    el.text(res.fWalletBalance);
                  });

                  if (res.walletBalance) currBal = res.walletBalance;

                  clearInputs(formSelector);

                  $("#user_name_f").removeClass("is-valid is-invalid");
                }
              },
              error: function (xhr) {
                if (!isProduction) console.log(xhr);

                var res = xhr.responseJSON || xhr.responseText;

                if (xhr.status === 400 && res.errors) {
                  if (res.errors.validationErrors) {
                    $(formSelector)
                      .validate({ focusInvalid: true })
                      .showErrors(res.errors.validationErrors);
                    Swal.close();
                  }

                  if (res.errors.error) {
                    sAlert(
                      "error",
                      res.errors.errorTitle ?? "",
                      res.errors.error
                    );
                  }
                }
              },
            });
          },
          { text: "Are you sure you want to topup?" }
        );

        return false;
      },
    });
  }

  static setupBankDetailsForm(options = {}) {
    const ifscSearchForm = options.ifscSearchForm;
    const bankFormSelector = options.bankFormSelector;
    const tpin_digits = options.tpin_digits;
    const url = options.url;
    const isProduction = options.isProduction ?? true;

    const bankFormValidationOptions = {
      rules: {
        account_holder_name: {
          required: true,
          alpha_num_space: true,
          minlength: 1,
          maxlength: 150,
        },
        account_number: {
          required: true,
          digits: true,
          minlength: 9,
          maxlength: 25,
        },
        tpin: {
          required: true,
          no_trailing_spaces: true,
          number: true,
          exactDigits: tpin_digits,
        },
      },
      submitHandler: function (form) {
        let blocked = false;

        sConfirm(
          function () {
            const formData = new FormData(form);
            formData.append("action", "bank_account");
            append_csrf(formData);
            const btnContent = $("#bank_account_btn span").html();

            const enableButton = () => {
              $("#bank_account_btn span").html(btnContent);

              !blocked && enable_form(bankFormSelector);
            };

            $.ajax({
              url: url,
              method: "POST",
              data: formData,
              processData: false,
              contentType: false,
              beforeSend: function () {
                $("#bank_account_btn span").html(
                  spinnerLabel({ label: "Processing Bank Details" })
                );
                disable_form(bankFormSelector);
                //loading
                sProcessingPopup(
                  "Processing Bank Details...",
                  "Do not close this window!"
                );
              },
              complete: function () {
                enableButton();
              },
              success: function (res, textStatus, xhr) {
                if (!isProduction) console.log(res);

                if (xhr.status === 200) {
                  if (res.title && res.message)
                    sAlert("success", res.title, res.message);

                  if (res.lock) {
                    $("#ifsc_search_container").slideUp("fast", function () {
                      $(this).remove();
                    });
                    blocked = true;
                    disable_form(bankFormSelector);
                  }

                  if (res.updated_message) {
                    $("#bank_updated_alert").fadeIn("fast", function () {
                      $("#bank_updated_alert span").html(res.updated_message);
                    });
                  }

                  $("#tpin_inp").val("");
                }
              },
              error: function (xhr) {
                if (!isProduction) console.log(xhr);

                var res = xhr.responseJSON || xhr.responseText;

                if (xhr.status === 400 && res.errors) {
                  if (res.errors.validationErrors) {
                    $(bankFormSelector)
                      .validate({ focusInvalid: true })
                      .showErrors(res.errors.validationErrors);
                    Swal.close();
                  }
                  if (res.errors.error) {
                    sAlert("error", "", res.errors.error);
                  }
                }
              },
            });
          },
          { text: "Are you sure you want to save bank details?" }
        );

        return false;
      },
    };

    $(ifscSearchForm).on("submit", function (event) {
      event.preventDefault();

      const ifscRegex = /^[A-Za-z]{4}0[A-Za-z0-9]{6}$/;

      const formData = new FormData(event.target);
      append_csrf(formData);
      formData.append("action", "search_ifsc");

      const ifscField = "#ifsc_f";

      if ($(ifscField).length <= 0) location.reload();

      const ifsc = $(ifscField).val().trim();

      let _err = null;

      if (ifsc == "") {
        return makeFieldInvalid(ifscField, "IFSC Code is required.");
      } else if (!ifscRegex.test(ifsc)) {
        return makeFieldInvalid(
          ifscField,
          "IFSC Code is either invalid or does not exist."
        );
      } else {
        makeFieldValid(ifscField);
      }

      const btnContent = $("#ifsc_search_btn span").html();

      const enableButton = () => {
        $("#ifsc_search_btn span").html(btnContent);
        enable_form(ifscSearchForm);
      };

      $.ajax({
        url: url,
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function () {
          $("#ifsc_search_btn span").html(spinnerLabel({ label: "Searching" }));
          disable_form(ifscSearchForm);
          disable_form(bankFormSelector);
          $("#bank_account_form_container").fadeOut("fast");
        },
        complete: function () {
          enableButton();
          enable_form(bankFormSelector);
        },
        success: function (res) {
          if (!isProduction) console.log(res);

          if (res.view) {
            $("#bank_account_form_container").fadeOut("fast", function () {
              $(this)
                .html(res.view)
                .slideDown(function () {
                  validateForm(bankFormSelector, bankFormValidationOptions);
                  scrollToBottom();
                });
            });
          }
        },
        error: function (xhr) {
          if (!isProduction) console.log(xhr);

          var res = xhr.responseJSON || xhr.responseText;

          if (xhr.status === 400 && res.errors) {
            if (res.errors.error) {
              sAlert("error", res.errors.errorTitle ?? "", res.errors.error);
            }
          }
        },
      });
    });

    validateForm(bankFormSelector, bankFormValidationOptions);
  }

  static setupWalletAddressForm(options = {}) {
    const formSelector = options.formSelector;
    const tpin_digits = options.tpin_digits;
    const url = options.url;
    const isProduction = options.isProduction ?? true;

    const validatorOptions = {
      rules: {
        trc20_address: {
          required: true,
          alpha_num: true,
          maxlength: 64,
        },
        bep20_address: {
          required: true,
          alpha_num: true,
          maxlength: 64,
        },
        tpin: {
          required: true,
          no_trailing_spaces: true,
          number: true,
          exactDigits: tpin_digits,
        },
      },
      submitHandler: function (form) {
        let blocked = false;

        sConfirm(
          function () {
            const formData = new FormData(form);
            formData.append("action", "wallet_address");
            append_csrf(formData);
            const btnContent = $("#wallet_btn span").html();

            const enableButton = () => {
              $("#wallet_btn span").html(btnContent);

              !blocked && enable_form(formSelector);
            };

            $.ajax({
              url: url,
              method: "POST",
              data: formData,
              processData: false,
              contentType: false,
              beforeSend: function () {
                $("#wallet_btn span").html(
                  spinnerLabel({ label: "Processing Wallet Details..." })
                );
                disable_form(formSelector);
                sProcessingPopup(
                  "Processing Wallet Details...",
                  "Do not close this window!"
                );
              },
              complete: function () {
                enableButton();
              },
              success: function (res, textStatus, xhr) {
                if (!isProduction) console.log(res);

                if (xhr.status === 200) {
                  if (res.title && res.message)
                    sAlert("success", res.title, res.message);

                  if (res.updated_form_view) {
                    $("#wallet_update_page").html(res.updated_form_view);
                    setTimeout(function () {
                      disable_form("#wallet-form");
                      initImageLazyLoading();
                    }, 0);
                  }
                }
              },
              error: function (xhr) {
                if (!isProduction) console.log(xhr);

                var res = xhr.responseJSON || xhr.responseText;

                if (xhr.status === 400 && res.errors) {
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
              },
            });
          },
          { text: "Are you sure you want to save wallet address?" }
        );

        return false;
      },
    };
    validateForm(formSelector, validatorOptions);
  }

  static setupLevelDownline(options = {}) {
    const url = options.apiUrl ?? "";
    const usersLabel = options.usersLabel ?? "Users";
    const userLabel = options.userLabel ?? "User";
    const activeStatusLabel = options.activeStatusLabel ?? "Active";
    const inactiveStatusLabel = options.inactiveStatusLabel ?? "Inactive";
    const isProduction = options.isProduction ?? false;

    const selectLoaderSelector = "#ult-loader";

    $.post(
      url,
      {
        ...csrf_data(),
        action: "level_select_view",
      },
      function (res) {
        !isProduction && console.log(res);
        if (res.html) {
          $("#level-select-container").html(res.html);
        }
      }
    );

    // returning onSelectChange function
    return function (level) {
      const levelSelectSelector = "#level_select";

      $("#lv-table").DataTable().destroy();

      $("#lv-table").DataTable({
        ajax: {
          type: "POST",
          url: apiUrl,
          data: {
            action: "update_level",
            level: Number(level),
            ...csrf_data(),
          },
          beforeSend: () => {
            $(levelSelectSelector).prop("disabled", true);
            $(selectLoaderSelector).fadeIn("fast");
          },
        },
        initComplete: function (settings, json) {
          $(selectLoaderSelector).fadeOut("fast");

          !isProduction && console.log(json);

          const level = json.level;

          $(levelSelectSelector).prop("disabled", false);
          const dataSize = json.data.length;

          if (dataSize >= 1) {
            let _userLabel = dataSize == 1 ? userLabel : usersLabel;
            const message = `${dataSize} ${_userLabel} found on level ${level}`;
            notify({
              title: `${usersLabel} Fetched!`,
              message,
            });

            $("#level_detail").text(
              `Level ${level} (Total ${dataSize} ${_userLabel}) - [Investment - ${json.flevelInvestment}]`
            );

            $("#level-card").slideDown({
              duration: 500,
            });
          } else {
            $("#level-card").slideDown({
              duration: 500,
            });
            notify({
              title: "No users found on this level",
            });
          }
        },
        error: (err) => {
          $(levelSelectSelector).prop("disabled", false);
          $(selectLoaderSelector).fadeOut("fast");
          location.reload();
        },
        columns: [
          {
            data: null,
            render: function (data, type, row, meta) {
              return meta.row + 1;
            },
          },
          {
            data: "user_id",
          },
          {
            data: "full_name",
          },
          {
            data: "sponsor_user_id",
            render: function (data, type, row, meta) {
              const sponsor_name = row.sponsor_full_name;
              const sponsor_id = row.sponsor_user_id;
              return `${sponsor_name} (${sponsor_id})`;
            },
          },
          {
            data: "email",
          },
          {
            data: "status",
            render: function (data, type, row, meta) {
              const color = row.status == true ? "success" : "danger";
              let userStatusLabel =
                row.status == true ? activeStatusLabel : inactiveStatusLabel;
              return `<span class="badge rounded-pill badge-${color}">${userStatusLabel}</span>`;
            },
          },
          {
            data: "total_investment",
            // render: function (data, type, row, meta) {
            //   return `<div class="text-end">${data}</div>`;
            // },
          },
          {
            data: "f_created_at",
            render: function (data, type, row, meta) {
              return `<span style="display:none;">${row.created_at}</span>${data}`;
            },
          },
          {
            data: "f_activated_at",
            render: function (data, type, row, meta) {
              return `<span style="display:none;">${row.activated_at ?? ""
                }</span><span class="text-secondary">${data ?? "N/A"}</span>`;
            },
          },
        ],
      });
    };
  }
}
