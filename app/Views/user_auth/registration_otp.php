<?= $this->extend('user_auth/layout/master') ?>

<?= $this->section('slot') ?>
<div class="col-md-5 mt-5">
    <div class="card">
        <div class="card-body">
            <div class="auth-form">
                <div class="text-center">
                    <h2>Confirm Email</h2>
                </div>
                <div class="mt-4" id="main-card">

                    <?php if (session()->has('__error')): ?>
                        <div class="alert alert-danger">
                            <?= session('__error') ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" id="rstp-form">
                        <?= csrf_field() ?>
                        <input type="hidden" name="payload" value="<?= $url_payload ?>">
                        <?= user_component('input', [
                            'label' => 'OTP (One Time Password)',
                            'class' => 'form-control-lg',
                            'icon' => 'fa-solid fa-key',
                            'name' => 'otp',
                            'type' => 'number',
                            'placeholder' => 'Enter OTP',
                            'required' => true,
                            'value' => inputGet('input') ?? ''
                        ]) ?>

                        <?= user_component('button', [
                            'label' => 'Submit',
                            'class' => 'btn-lg btn-block w-100 mt-3',
                            'icon' => 'fa-solid fa-unlock',
                            'id' => 'rstp_btn',
                            'submit' => true
                        ]) ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>