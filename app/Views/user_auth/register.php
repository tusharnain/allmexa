<?= $this->extend('user_auth/layout/master') ?>


<?= $this->section('slot') ?>
<div class="col-md-8 mt-5">
    <div class="card">
        <div class="card-body">
            <div class="auth-form">
                <h2 class="text-center mb-4">Sign Up</h2>
                <form id="register-form" method="POST">

                    <?= csrf_field() ?>

                    <?= user_component('alert', [
                        'text' => '',
                        'id' => 'sid_alert'
                    ]) ?>

                    <div class="row">

                        <div class="col-12">
                            <?= user_component('input', [
                                'label' => label('sponsor_id'),
                                'icon' => 'fa-solid fa-circle-user',
                                'name' => 'sponsor_id',
                                'id' => 'sponsor_id',
                                'placeholder' => "Enter " . label('sponsor_id'),
                                'value' => $refer->user_id ?? '',
                                'disabled' => !is_null($refer)
                            ]) ?>
                        </div>

                        <div class="col-lg-6">
                            <?= user_component('input', [
                                'label' => 'Full Name',
                                'icon' => 'fa-solid fa-user',
                                'name' => 'full_name',
                                'placeholder' => "Enter Full Name"
                            ]) ?>
                        </div>


                        <div class="col-lg-6">
                            <?= user_component('select', [
                                'label' => 'Country',
                                'icon' => 'fa-solid fa-globe',
                                'name' => 'country_code',
                                'select' => 'in',
                                'class' => 'select2',
                                'options' => array_flip(\App\Libraries\CountryLib::COUNTRIES)
                            ]) ?>
                        </div>


                        <div class="col-12">
                            <?= user_component('input', [
                                'label' => 'Email Address',
                                'icon' => 'fa-solid fa-envelope',
                                'name' => 'email',
                                'placeholder' => "name@example.com"
                            ]) ?>
                        </div>




                        <div class="col-lg-6">
                            <?= user_component('input', [
                                'label' => 'Password',
                                'class' => 'form-control-lg _password',
                                'icon' => 'fa-solid fa-eye',
                                'name' => 'password',
                                'type' => 'password',
                                'placeholder' => 'Enter Password',
                                'groupId' => 'Password-toggle1'
                            ]) ?>
                        </div>

                        <div class="col-lg-6">
                            <?= user_component('input', [
                                'label' => 'Confirm Password',
                                'icon' => 'fa-solid fa-eye',
                                'name' => 'cpassword',
                                'type' => 'password',
                                'placeholder' => 'Confirm password',
                                'groupId' => 'Password-toggle2'
                            ]) ?>
                        </div>

                        <div class="col-12">
                            <?= user_component('input', [
                                'label' => "Create " . label('tpin'),
                                'icon' => 'fa-solid fa-eye',
                                'name' => 'tpin',
                                'type' => 'password',
                                'placeholder' => 'Create ' . label('tpin'),
                                'groupId' => 'Password-toggle3'
                            ]) ?>

                        </div>


                        <?php if (_setting('registration_captcha')): ?>
                            <div class="col-12 row mt-4 mb-3 mb-md-0">
                                <div class="col-md-6">
                                    <?= user_component('input', [

                                        'name' => 'captcha',
                                        'placeholder' => 'Enter Captcha Code',
                                    ]) ?>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-0 d-flex">

                                        <img src="<?= route('registerCaptchaImage') . "?twebsol=" . time() ?>"
                                            alt="registration-captcha" id="reg_captcha_img">

                                        <div class="h3 text-start text-primary w-100 my-auto ms-2 ms-lg-1" role="button">
                                            <i class="fa-solid fa-redo" id="reg_captcha_reload"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>


                        <div class="col-01">
                            <?= user_component('app/tnc_checkbox', [
                                'title' => 'Agree to the Terms & Conditions',
                                'link' => '#',
                                'linkOn' => 'Terms & Conditions'
                            ]) ?>
                        </div>

                    </div>

                    <?= user_component('button', [
                        'label' => 'Register',
                        'class' => 'btn-lg btn-block w-100',
                        'icon' => 'fa-solid fa-right-to-bracket',
                        'id' => 'reg_btn',
                        'submit' => true
                    ]) ?>


                    <p class="mt-4 mb-0">
                        Already have an account?
                        <a class="ms-2" href="<?= route('login') ?>">Sign in</a>
                    </p>

                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>


<?php $this->section('script') ?>

<script src="<?= base_url('twebsol/user/registration.js') ?>?v=0.5"></script>

<script src="<?= base_url('twebsol/plugins/html2canvas/html2canvas.min.js') ?>"></script>

<script src="<?= base_url('twebsol/scripts/tools.js') ?>"></script>


<script>
    $(document).ready(function () {



        registerInit({
            minPasswordLength: <?= _setting('password_min_length') ?>,
            tpinDigits: <?= _setting('tpin_digits', 6) ?>,
            userIdLengths: <?= json_encode(_setting('user_id_length_validation')) ?>,
            captchaSize: <?= $captchaSize ?>,
            loginPageUrl: "<?= route('login') ?>",
            registerPostUrl: "<?= route('registerPost') ?>",
            userNameApi: "<?= route('api.public.getUserNameFromUserId') ?>",
            registerCaptchaImageUrl: <?= _setting('registration_captcha') ? '"' . route('registerCaptchaImage') . '"' : 'null' ?>,
            sponsorLabel: "<?= label('sponsor') ?>",
            sponsorIdLabel: "<?= label('sponsor_id') ?>",
            isProduction: <?= isProduction() ? 'true' : 'false' ?>,
            refer: <?= $refer ? json_encode($refer) : 'null' ?>,
        });
    });
</script>

<?php $this->endSection() ?>