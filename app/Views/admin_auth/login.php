<?= $this->extend('admin_auth/_layout') ?>

<?= $this->section('slot') ?>
<div class="container-fluid p-0">
    <div class="card">
        <div class="card-body">

            <div class="text-center mt-4">
                <div class="mb-3">
                    <a href="<?= route('admin.login') ?>" class="auth-logo">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/c/c2/CPanel_logo.svg" height="30" class="mx-auto"
                            alt="logo">
                    </a>
                </div>
            </div>

            <!--<h4 class="text-muted text-center font-size-18"><b>Admin Login</b></h4>-->

            <div class="p-3">

                <form class="form-horizontal mt-3" method="POST" id="login-form">
                    <?= csrf_field() ?>

                    <?= admin_component('input_with_icon', [
                        'label' => 'Email Address',
                        'placeholder' => 'Enter Email Address',
                        'icon' => 'fas fa-envelope',
                        'name' => 'email'
                    ]) ?>


                    <?= admin_component('input_with_icon', [
                        'label' => 'Password',
                        'placeholder' => 'Enter Password',
                        'icon' => 'far fa-eye',
                        'name' => 'password',
                        'type' => 'password',
                        'groupId' => 'Password-toggle1'
                    ]) ?>

                    <?= admin_component('button', [
                        'label' => 'Log in',
                        'submit' => true,
                        'icon' => 'fas fa-arrow-right',
                        'iconLast' => true,
                        'class' => 'w-100',
                        'id' => 'login_btn'
                    ]) ?>


                </form>
            </div>

        </div>

    </div>

</div>
<?= $this->endSection() ?>


<?php $this->section('script') ?>

<script>

    $(document).ready(function () {

        const loginFormSelector = '#login-form';

        validateForm(loginFormSelector, {
            rules: {

                email: { required: true, maxlength: 200, email: true },

                password: { required: true, no_trailing_spaces: true },

            },
            submitHandler: function (form) {

                const formData = new FormData(form);

                let btnContent = $('#login_btn span').html();

                let lock = false;

                $.ajax({
                    url: "<?= route('admin.loginPost') ?>",
                    method: "POST",
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    beforeSend: function () {

                        $('#login_btn span').html(spinnerLabel());

                        disable_form(loginFormSelector);
                    },
                    complete: function () {
                        if (!lock) {
                            $('#login_btn span').html(btnContent);
                            enable_form(loginFormSelector);
                        }
                    },
                    success: function (res, textStatus, xhr) {

                        <?= !isProduction() ? 'console.log(res);' : '' ?>

                        if (xhr.status === 200) {

                            $('#login_btn').removeClass('btn-primary').addClass('btn-success');

                            $('#login_btn span').html('<i class="fas fa-check me-2"></i>Login Successful!');

                            lock = true;

                            window.location.href = res.redirectTo;
                        }

                    },
                    error: function (xhr) {

                        <?= !isProduction() ? 'console.log(xhr);' : '' ?>

                        var res = xhr.responseJSON || xhr.responseText;

                        if (xhr.status === 400 && res.errors) {

                            if (res.errors.validationErrors)
                                $(loginFormSelector).validate().showErrors(res.errors.validationErrors);

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
<?php $this->endSection() ?>