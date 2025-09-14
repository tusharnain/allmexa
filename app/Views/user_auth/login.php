<?php
$userIdLabel = label('user_id');

?>
<?= $this->extend('user_auth/layout/master') ?>



<?= $this->section('slot') ?>
<div class="col-md-6 mt-5">
    <div class="card">
        <div class="card-body">
            <div class="auth-form">
                <h2 class="text-center mb-4">Sign In</h2>
                <form id="login-form">
                    <?= csrf_field() ?>


                    <?= _setting('allow_user_login_with_email', false) ?
                        user_component('input', [
                            'label' => "$userIdLabel / Email",
                            'class' => 'form-control-lg',
                            'icon' => 'fa-solid fa-user',
                            'name' => 'username',
                            'placeholder' => "Enter $userIdLabel or Email Address"
                        ]) :
                        user_component('input', [
                            'label' => $userIdLabel,
                            'class' => 'form-control-lg',
                            'icon' => 'fa-solid fa-user',
                            'name' => 'user_id',
                            'placeholder' => "Enter $userIdLabel"
                        ])
                        ?>

                    <?= user_component('input', [
                        'label' => 'Password',
                        'class' => 'form-control-lg _password',
                        'icon' => 'fa-solid fa-eye',
                        'name' => 'password',
                        'type' => 'password',
                        'placeholder' => 'Enter Password',
                        'groupId' => 'Password-toggle1'
                    ]) ?>

                    <?php if (_setting('login_captcha')): ?>
                        <div class="row mt-4">
                            <div class="col-md-5">
                                <?= user_component('input', [
                                    'class' => 'form-control-lg',
                                    'name' => 'captcha',
                                    'placeholder' => 'Captcha Code',
                                ]) ?>
                            </div>
                            <div class="col-7">
                                <div class="p-0 d-flex">

                                    <img src="<?= route('loginCaptchaImage') . "?twebsol=" . time() ?>" alt="login-captcha"
                                        id="login_captcha_img">

                                    <div class="h3 ms-2 text-primary w-100 my-auto">
                                        <i class="fa-solid fa-redo" id="login_captcha_reload" role="button"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="form-group mb-0">

                        <?= user_component('checkbox', [
                            'name' => 'rememberMe',
                            'label' => 'Remember Me',
                        ]) ?>


                        <a class="link" href="<?= route('forgetPassword') ?>">
                            Forgot password?
                        </a>
                    </div>

                    <?= user_component('button', [
                        'label' => 'Sign in',
                        'class' => 'btn-lg btn-block w-100 mt-4',
                        'icon' => 'fa-solid fa-user',
                        'id' => 'login_btn',
                        'submit' => true
                    ]) ?>

                    <p class="mt-4 mb-0 text-center">
                        Don't have account?
                        <a class="ms-2" href="<?= route('register') ?>">
                            Create Account
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>


<?php $this->section('script') ?>

<script src="<?= base_url('twebsol/user/login.js') ?>"></script>

<script>

    <?php if ($postRegHtml): ?>
        $(document).ready(function () {
            const html = `<?= $postRegHtml ?>`;
            sAlert("success", "Account Registered!", html, {
                allowOutsideClick: false,
                showCancelButton: true,
                confirmButtonText: "Login",
                cancelButtonText: "Ok",
                customClass: {
                    popup: "post_registration_popup",
                },
            }).then((result) => {
            });
        });
    <?php endif; ?>


    loginInit({
        userIdLengths: <?= json_encode(_setting('user_id_length_validation')) ?>,
        captchaSize: <?= $captchaSize ?>,
        loginPostUrl: "<?= route('loginPost') ?>",
        loginCaptchaImageUrl: <?= _setting('login_captcha') ? '"' . route('loginCaptchaImage') . '"' : 'null' ?>,
        userLabel: "<?= label('user') ?>",
        isProduction: <?= isProduction() ? 'true' : 'false' ?>,
        is_email_login_allowed: <?= _setting('allow_user_login_with_email', false) ? 'true' : 'false' ?>,
    });

</script>

<?php $this->endSection() ?>