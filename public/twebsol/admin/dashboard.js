/*
 *------------------------------------------------------------------------------------
 * Sidebar Setup
 *------------------------------------------------------------------------------------
 */
function setupSidebar(sidebar, options = {}) {
  const profilePic = options.profilePic ?? "";
  const adminName = options.adminName ?? "";

  let sidebarHtml = "";

  sidebar.forEach((menu) => {
    if (menu.submenus) {
      let submenus_lis = "";
      menu.submenus.forEach((submenu) => {
        submenus_lis += ` <li><a href="${submenu.url}">
                                      <i class="${submenu.icon}"></i>
                                      <span>
                                          ${submenu.title}
                                      </span>
                                      </a>
                                  </li>`;
      });

      sidebarHtml += `<li>
                                  <a href="${menu.url}" class="has-arrow waves-effect">
                                      <i class="${menu.icon}"></i>
                                      <span>
                                          ${menu.title}
                                      </span>
                                  </a>
                                  <ul class="sub-menu" aria-expanded="false">
                                      ${submenus_lis}
                                  </ul>
                              </li>`;
    } else {
      sidebarHtml += `<li>
                      <a href="${menu.url}" class="waves-effect">
                          <i class="${menu.icon}"></i>
                          <span>
                              ${menu.title}
                          </span>
                      </a>
                  </li>`;
    }
  });

  const finalSidebarHtml = `
      <div data-simplebar class="h-100">
  <div class="user-profile text-center mt-3">
  <div class="">
      <img src="${profilePic}" alt="" class="avatar-md rounded-circle">
  </div>
  <div class="mt-3">
      <h4 class="font-size-16 mb-1">${adminName}</h4>
      <span class="text-muted"><i class="ri-record-circle-line align-middle font-size-14 text-success"></i>
          Online</span>
  </div>
  </div>
  
  <div id="sidebar-menu">
  <ul class="metismenu list-unstyled" id="side-menu">
      ${sidebarHtml}
  </ul>
  </div>
  </div>`;

  $(finalSidebarHtml).appendTo("#sidebar-parent");
}

/*
 *------------------------------------------------------------------------------------
 * Setup Header
 *------------------------------------------------------------------------------------
 */
function setupHeader(options = {}) {
  const profilePic = options.profilePic ?? "";
  const adminName = options.adminName ?? "";
  const dashboardUrl = options.dashboardUrl ?? "";
  const logoDirectory = options.logoDirectory ?? "";

  const headerHtml = `<div class="navbar-header">
      <div class="d-flex">
          <!-- LOGO -->
          <div class="navbar-brand-box">
              <a href="${dashboardUrl}" class="logo logo-dark">
                  <span class="logo-sm">
                      <img src="${logoDirectory}/logo-sm.png" alt="logo-sm" height="22">
                  </span>
                  <span class="logo-lg">
                      <img src="${logoDirectory}/logo-dark.png" alt="logo-dark" height="20">
                  </span>
              </a>
  
              <a href="${dashboardUrl}" class="logo logo-light">
                  <span class="logo-sm">
                      <img src="${logoDirectory}/logo-sm.png" alt="logo-sm-light" height="22">
                  </span>
                  <span class="logo-lg">
                      <img src="${logoDirectory}/logo-light.png" alt="logo-light" height="20">
                  </span>
              </a>
          </div>
  
          <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect"
              id="vertical-menu-btn">
              <i class="ri-menu-2-line align-middle"></i>
          </button>
  
          <!-- App Search-->
          <form class="app-search d-none d-lg-block">
              <div class="position-relative">
                  <input type="text" class="form-control" placeholder="Search...">
                  <span class="ri-search-line"></span>
              </div>
          </form>
  
  
      </div>
  
      <div class="d-flex">
  
  
          <div class="dropdown d-inline-block user-dropdown">
              <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                  data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <img class="rounded-circle header-profile-user"
                      src="${profilePic}" alt="Header Avatar">
                  <span class="d-none d-xl-inline-block ms-1">
                      ${adminName}
                  </span>
                  <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end">
                  <!-- item-->
                  <a class="dropdown-item" href="#">
                      <i class="ri-user-line align-middle me-1"></i>
                      Profile
                  </a>
  
                  <a class="dropdown-item d-block" href="#">
                      <i class="ri-settings-2-line align-middle me-1"></i>
                      Settings
                  </a>
  
                  <button class="dropdown-item text-danger" onclick="$('#logoutForm').submit();">
                      <i class="ri-shut-down-line align-middle me-1 text-danger"></i>
                      Logout
                  </button>
              </div>
          </div>
  
      </div>
  </div>`;

  $(headerHtml).appendTo("#page-topbar");
}

function setupFooter(options = {}) {
  const companyName = options.companyName ?? "";
  const currentYear = options.currentYear ?? "";
  const developer = options.developer ?? "";

  const footerHtml = `<div class="container-fluid">
      <div class="row">
          <div class="col-sm-6">
              ${currentYear}
              ©
              ${companyName}.
          </div>
          <div class="col-sm-6">
              <div class="text-sm-end d-none d-sm-block">
                  Crafted with <i class="mdi mdi-heart text-danger"></i> by
                  ${developer}
              </div>
          </div>
      </div>
  </div>`;

  $(footerHtml).appendTo("#footer-parent");
}

class AdminDashboard {
  static initUserNameFetchAlert(options = {}) {
    const {
      api,
      inputSelector,
      alertSelector,
      userIdLengths,
      isProduction,
      userIdLabel,
    } = options;
    // there should be a span inside the alert

    $(inputSelector).on("change", function () {
      const user_id = $(this).val().trim();
      const alert = $(alertSelector);
      const alertSpan = $(`${alertSelector} span`);
      if (
        user_id.length < userIdLengths[0] ||
        user_id.length > userIdLengths[1]
      )
        return alert.hide();
      const hasSuccess = (html) => {
        alert.removeClass("alert-danger").addClass("alert-success");
        alertSpan.html(html);
      };
      const hasError = (html) => {
        alert.removeClass("alert-success").addClass("alert-danger");
        alertSpan.html(
          `<span class="mdi mdi-alert-circle me-2"></span>${html}`
        );
      };
      $.ajax({
        url: api,
        method: "POST",
        data: { user_id, ...csrf_data() },
        beforeSend: function () {
          alertSpan.html(spinnerLabel({ type: "fa" }));
          alert.show();
        },
        complete: function () {
          alert.removeClass("alert-primary");
        },
        success: function (res, textStatus, xhr) {
          isProduction && console.log(res);
          if (xhr.status === 200 && res.status == 3 && res.username) {
            const html = `<i class="mdi mdi-check-circle-outline me-2"></i>${userIdLabel} : <strong>${res.username}</strong>`;
            hasSuccess(html);
          }
        },
        error: function (xhr) {
          isProduction && console.log(xhr);
          var res = xhr.responseJSON || xhr.responseText;
          if (xhr.status === 400 && res.status) {
            const msg =
              res.status === 1
                ? `${userIdLabel} is required!`
                : `${userIdLabel} is invalid!`;
            hasError(msg);
          }
        },
      });
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
      beforeSend: function () {},
      complete: function () {},
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
}
