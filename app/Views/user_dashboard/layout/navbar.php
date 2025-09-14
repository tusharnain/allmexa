<!--Nav Start-->
<nav class="nav navbar navbar-expand-lg navbar-light iq-navbar border-bottom">
    <div class="container-fluid navbar-inner">
        <a href="../../dashboard/index.html" class="navbar-brand">
        </a>
        <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
            <i class="icon">
                <svg width="20px" height="20px" viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z" />
                </svg>
            </i>
        </div>
        <?php if (isset($dashboard_title)): ?>
            <h4 class="title">
                <?= $dashboard_title ?? 'Dashboard' ?>
            </h4>
        <?php endif; ?>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon">
                <span class="navbar-toggler-bar bar1 mt-2"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
            </span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto navbar-list mb-2 mb-lg-0 align-items-center">

                <li class="nav-item dropdown">
                    <a class="nav-link py-0 d-flex align-items-center" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?= \App\Models\UserModel::getAvatar(user()) ?>" alt="User-Profile"
                            class="pfp_image_img img-fluid avatar avatar-50 avatar-rounded">
                        <div class="caption ms-3 ">
                            <h6 class="mb-0 caption-title"><?= user('full_name') ?></h6>
                            <p class="mb-0 caption-sub-title">Member</p>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li class="border-0"><a class="dropdown-item"
                                href="<?= route('user.profile.profileUpdate') ?>">Profile</a></li>
                        <li class="border-0"><a class="dropdown-item"
                                href="<?= route('user.profile.changePassword') ?>">Change Password</a>
                        </li>
                        <li class="border-0">
                            <hr class="m-0 dropdown-divider">
                        </li>
                        <li class="border-0"><a class="dropdown-item" href="javascript:;"
                                onclick="$('#logoutForm').submit();">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav> <!--Nav End-->