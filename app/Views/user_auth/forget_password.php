<?php

$userIdLabel = label('user_id');

?>

<?= $this->extend('user_auth/layout/master') ?>

<?= $this->section('style') ?>
<style>
    #sending-email .email {
        word-wrap: break-word;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('slot') ?>
<div class="col-md-5 mt-5">
    <div class="card">
        <div class="card-body">
            <div class="auth-form">
                <div class="text-center">
                    <h2>Forgot password?</h2>
                </div>
                <div>
                    <form id="fgt-form">

                        <p class="my-3">Enter your <?= $userIdLabel ?> to receive reset password link.</p>

                        <?= user_component('input', [
                            'label' => $userIdLabel,
                            'class' => 'form-control-lg',
                            'icon' => 'fa-solid fa-user',
                            'name' => 'user_id',
                            'placeholder' => "Enter $userIdLabel"
                        ]) ?>

                        <?= user_component('button', [
                            'label' => 'Submit',
                            'class' => 'btn-lg btn-block w-100 mt-3',
                            'icon' => 'fa-solid fa-unlock',
                            'id' => 'fgt_btn',
                            'submit' => true
                        ]) ?>

                        <p class="mt-4 mb-0">
                            Don't have an account?
                            <a class="ms-2" href="<?= route('register') ?>">
                                Create Account
                            </a>
                        </p>

                    </form>

                    <div id="sending-email" style="display:none;" class="text-center">
                        <h4 class="mt-4">
                            <i class="fa-solid fa-envelope me-2"></i>
                            Sending Email
                        </h4>
                        <h6 class="mt-4">
                            Sending Password Reset link to
                        </h6>
                        <h4 class="email text-danger fw-bold mt-2">
                            nain*******@gmail.com
                        </h4>
                        <div class="mt-4">
                            <?= user_component('loaders/loader1') ?>
                        </div>
                    </div>

                    <div id="email-sent" style="display:none;" class="text-center">
                        <h2 class="text-success mt-4">
                            <i class="fa-solid fa-check-circle"></i>
                            Email sent!
                        </h2>
                        <h5 class="mt-4">
                            Reset Password Link has been sent to
                        </h5>
                        <h4 class="email text-danger fw-bold mt-2">
                        </h4>
                    </div>
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
        const api = "<?= current_url() ?>";
        const userIdLengths = <?= json_encode(_setting('user_id_length_validation')) ?>;
        const isPrd = <?= isProduction() ? 'true' : 'false' ?>;

        ForgetAndResetPassword.initForgetPassword({ api, userIdLengths, isProduction: isPrd });
    });
</script>

<?php $this->endSection() ?>