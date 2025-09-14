<?= $this->extend('user_auth/layout/master') ?>

<?= $this->section('slot') ?>
<div class="col-md-5 mt-5">
    <div class="card">
        <div class="card-body">
            <div class="auth-form">
                <div class="text-center">
                    <h2>Reset Password</h2>
                </div>
                <div class="mt-4" id="main-card">
                    <?php if ($validToken): ?>
                        <form id="rstp-form">

                            <?= user_component('input', [
                                'label' => 'New Password',
                                'class' => 'form-control-lg _password',
                                'icon' => 'fa-solid fa-eye',
                                'name' => 'password',
                                'type' => 'password',
                                'placeholder' => 'Enter New Password',
                                'groupId' => 'Password-toggle1'
                            ]) ?>

                            <?= user_component('input', [
                                'label' => 'Confirm New Password',
                                'class' => 'form-control-lg',
                                'icon' => 'fa-solid fa-eye',
                                'name' => 'cpassword',
                                'type' => 'password',
                                'placeholder' => 'Enter New Password',
                                'groupId' => 'Password-toggle2'
                            ]) ?>

                            <?= user_component('button', [
                                'label' => 'Reset Password',
                                'class' => 'btn-lg btn-block w-100 mt-3',
                                'icon' => 'fa-solid fa-unlock',
                                'id' => 'rstp_btn',
                                'submit' => true
                            ]) ?>
                        </form>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>


<?php $this->section('script') ?>

<script src="<?= base_url('twebsol/user/forget-and-reset-password.js') ?>"></script>

<script>
    $(document).ready(function () {
        const token = "<?= $token ?>";
        const api = "<?= current_url() ?>";
        const passwordMinLength = <?= _setting('password_min_length') ?>;
        const isProduction = <?= isProduction() ? 'true' : 'false' ?>;
        const companyName = "<?= data('company_name') ?>";
        const loginUrl = "<?= route('login') ?>";
        const forgetPasswordUrl = "<?= route('forgetPassword') ?>";
        const validToken = <?= $validToken ? 'true' : 'false' ?>;

        ForgetAndResetPassword.initResetPassword({ api, token, passwordMinLength, isProduction, companyName, loginUrl, forgetPasswordUrl, validToken });

    });
</script>
<?php $this->endSection() ?>