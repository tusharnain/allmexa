<?= $this->extend('user_dashboard/layout/master') ?>




<?= $this->section('slot') ?>

<div class="container-fluid">

    <div id="last_password_alert">
        <?php if ($lastPasswordChangedAlert): ?>
            <?= $lastPasswordChangedAlert ?>
        <?php endif; ?>
    </div>

    <div class="card">
        <div class="card-header py-3">
            <h5>
                Change Password
            </h5>
        </div>
        <div class="card-body pt-3">

            <form id="change_password_form">
                <?= csrf_field() ?>

                <div class="row">

                    <div class="col-lg-4">
                        <?= user_component('input', [
                            'label' => 'Current Password',
                            'icon' => 'fa-solid fa-eye',
                            'name' => 'cpassword',
                            'type' => 'password',
                            'placeholder' => 'Enter current password',
                            'groupId' => 'Password-toggle1'
                        ]) ?>
                    </div>

                    <div class="col-lg-4">
                        <?= user_component('input', [
                            'label' => 'New Password',
                            'class' => '_npassword',
                            'icon' => 'fa-solid fa-eye',
                            'name' => 'npassword',
                            'type' => 'password',
                            'placeholder' => 'Enter new password',
                            'groupId' => 'Password-toggle2'
                        ]) ?>
                    </div>

                    <div class="col-lg-4">
                        <?= user_component('input', [
                            'label' => 'Confirm New Password',
                            'icon' => 'fa-solid fa-eye',
                            'name' => 'cnpassword',
                            'type' => 'password',
                            'placeholder' => 'Enter new password again',
                            'groupId' => 'Password-toggle3'
                        ]) ?>
                    </div>
                </div>

                <div class="text-end">
                    <?= user_component('button', [
                        'label' => 'Change Password',
                        'class' => 'btn-lg mobile-button',
                        'icon' => 'fa-solid fa-lock',
                        'id' => 'change_password_btn',
                        'submit' => true
                    ]) ?>
                </div>

            </form>

        </div>
    </div>


</div>


<?= $this->endSection() ?>




<?= $this->section('script') ?>

<script src="<?= base_url('twebsol/scripts/show-passwords.js') ?>"></script>

<script>
    $(document).ready(function () {

        // form selector
        const formSelector = '#change_password_form';

        const minPasswordLength = <?= _setting('password_min_length') ?>;

        // validating
        validateForm(formSelector, {
            rules: {
                cpassword: { required: true, no_trailing_spaces: true, minlength: minPasswordLength },
                npassword: { required: true, no_trailing_spaces: true, minlength: minPasswordLength },
                cnpassword: { required: true, no_trailing_spaces: true, minlength: minPasswordLength, equalTo: "._npassword", },
            },
            submitHandler: function (form) {

                const formData = new FormData(form);

                const btnContent = $('#change_password_btn').html();

                const enableButton = () => {
                    $('#change_password_btn').html(btnContent);
                    enable_form(formSelector);
                };

                $.ajax({
                    url: "<?= route('user.profile.changePassword') ?>",
                    method: "POST",
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    beforeSend: function () {

                        $('#change_password_btn').html(spinnerLabel({ label: 'Changing Password' }));

                        disable_form(formSelector);
                    },
                    complete: function () {
                        enableButton();
                    },
                    success: function (res, textStatus, xhr) {

                        <?= !isProduction() ? 'console.log(res);' : '' ?>

                        if (xhr.status === 200) {

                            clearInputs(formSelector);

                            if (res.message && res.title)
                                sAlert('success', res.title, res.message);

                            if (res.lastPasswordAlert) {
                                $('#last_password_alert').html(res.lastPasswordAlert);
                            }
                        }

                    },
                    error: function (xhr) {

                        <?= !isProduction() ? 'console.log(xhr);' : '' ?>

                        var res = xhr.responseJSON || xhr.responseText;

                        if (xhr.status === 400 && res.errors) {

                            if (res.errors.validationErrors) {

                                $(formSelector).validate({ focusInvalid: true }).showErrors(res.errors.validationErrors);

                            }

                            if (res.errors.error) {
                                sAlert('error', '', res.errors.error);
                            }
                        }
                    }
                });

                return false;
            }
        });
    });

</script>
<?= $this->endSection() ?>