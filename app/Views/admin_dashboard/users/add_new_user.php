<?= $this->extend('admin_dashboard/_partials/app') ?>

<?= $this->section('style') ?>
<style>
    #sid_alert {
        display: none;
    }
</style>
<?= $this->endSection() ?>



<?= $this->section('slot') ?>
<div class="container-fluid">
    <div class="row">

        <?php if ($noUsers): ?>
            <div class="col-12">
                <?= admin_component('alert', [
                    'type' => 'info',
                    'text' => $noUsers,
                    'noIcon' => true
                ]) ?>
            </div>
        <?php endif; ?>


        <div class="col-xxl-4 col-xl-5 col-lg-6">

            <div class="card">

                <div class="card-header bg-primary text-white">
                    Register New
                    <?= label('user') ?>
                </div>

                <div class="card-body">

                    <?= admin_component('alert', [
                        'text' => '',
                        'id' => 'sid_alert'
                    ]) ?>


                    <form id="register-form">

                        <?= csrf_field() ?>
                        <input type="hidden" name="country_code" value="in" />

                        <?php if (!$noUsers): ?>
                            <?= admin_component('input_with_icon', [
                                'label' => label('sponsor_id'),
                                'icon' => 'fas fa-user',
                                'name' => 'sponsor_id',
                                'placeholder' => "Enter " . label('sponsor_id'),
                                'id' => 'sponsor_id'
                            ]) ?>
                        <?php endif; ?>

                        <?= admin_component('input_with_icon', [
                            'label' => 'Full Name',
                            'icon' => 'fas fa-user-circle',
                            'name' => 'full_name',
                            'placeholder' => "Enter Full Name"
                        ]) ?>

                        <?= admin_component('input_with_icon', [
                            'label' => 'Email Address',
                            'icon' => 'fas fa-envelope',
                            'name' => 'email',
                            'placeholder' => "name@example.com"
                        ]) ?>

                        <?= admin_component('input_with_icon', [
                            'label' => 'Password',
                            'icon' => 'fas fa-eye',
                            'name' => 'password',
                            'type' => 'password',
                            'placeholder' => 'Password',
                            'groupId' => 'Password-toggle1'
                        ]) ?>

                        <?php if ($email_creds): ?>
                            <?= admin_component('checkbox', [
                                'name' => 'email_login_creds',
                                'label' => 'Email login credential to the ' . label('user', 1)
                            ]) ?>
                        <?php endif; ?>

                        <?= admin_component('button', [
                            'label' => 'Register',
                            'submit' => true,
                            'icon' => 'fas fa-arrow-right',
                            'iconLast' => true,
                            'class' => 'w-100',
                            'id' => 'reg_btn'
                        ]) ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>

<script src="<?= admin_asset('js/show-password.js') ?>"></script>

<script src="<?= base_url('twebsol/plugins/html2canvas/html2canvas.min.js') ?>"></script>

<script src="<?= base_url('twebsol/scripts/tools.js') ?>"></script>

<script>


    const min_password_length = <?= _setting('password_min_length') ?>;
    const userIdLengths = <?= json_encode(_setting('user_id_length_validation')) ?>;
    const regFormSelector = '#register-form';
    var noUsers = <?= $noUsers ? 'true' : 'false' ?>;
    const sponsor_id_label = "<?= label('sponsor_id') ?>";
    const sponsorLabel = "<?= label('sponsor') ?>";
    const userNameApi = "<?= route('api.public.getUserNameFromUserId') ?>";

    $(document).ready(function () {


        validateForm(regFormSelector, {
            rules: {
                sponsor_id: noUsers ? {} : {
                    required: true, alpha_num: true, minlength: userIdLengths[0], maxlength: userIdLengths[1]
                },

                full_name: { required: true, minlength: 2, maxlength: 100, alpha_num_space: true },

                email: { required: true, maxlength: 200, email: true },

                phone: { required: true, number: true, minlength: 10, maxlength: 12 },

                password: { required: true, no_trailing_spaces: true, minlength: min_password_length },

            },
            submitHandler: function (form) {

                const formData = new FormData(form);
                formData.append('action', 'register_post');

                const btnContent = $('#reg_btn span').html();

                const enableButton = () => {
                    $('#reg_btn span').html(btnContent);
                    enable_form(regFormSelector);
                };

                $.ajax({
                    url: "<?= route('admin.users.addNewUser') ?>",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function () {

                        $('#reg_btn span').html(spinnerLabel());

                        disable_form(regFormSelector);
                    },
                    complete: function () {
                        enableButton();
                    },
                    success: function (res, textStatus, xhr) {

                        <?= !isProduction() ? 'console.log(res);' : '' ?>

                        if (xhr.status === 201) {

                            $("#sid_alert").hide();

                            clearInputs(regFormSelector);

                            sAlert('success', 'Registration has completed!', res.html, {
                                allowOutsideClick: false,
                                confirmButtonText: 'Ok',
                                customClass: {
                                    popup: "post_registration_popup"
                                }
                            }).then(() => {
                                noUsers && location.reload();
                            });
                        }

                    },
                    error: function (xhr) {

                        <?= !isProduction() ? 'console.log(xhr);' : '' ?>

                        var res = xhr.responseJSON || xhr.responseText;

                        if (xhr.status === 400 && res.errors) {

                            if (res.errors.validationErrors) {

                                if (noUsers && res.errors.validationErrors.sponsor_id) {
                                    enableButton();
                                    sAlert('warning', 'Uh Ohh..', `The first user now exists, you have to try again with a valid ${sponsor_id_label}!`).then(() => {

                                        location.reload();
                                    });
                                }

                                $(regFormSelector).validate().showErrors(res.errors.validationErrors);
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



    $("#sponsor_id").on("change", function () {

        const sid = $(this).val().trim();

        const alert = $("#sid_alert");
        const alertSpan = $("#sid_alert span");

        if (sid.length < userIdLengths[0] || sid.length > userIdLengths[1]) {
            return alert.hide();
        }

        const hasSuccess = (html) => {
            alert.removeClass("alert-danger").addClass("alert-success");
            alertSpan.html(html);
        };

        const hasError = (html) => {
            alert.removeClass("alert-success").addClass("alert-danger");
            alertSpan.html(`<span class="mdi mdi-alert-circle me-2"></span>${html}`);
        };

        $.ajax({
            url: userNameApi,
            method: "POST",
            data: { user_id: sid, ...csrf_data() },
            beforeSend: function () {
                alertSpan.html(spinnerLabel({ type: "fa" }));

                alert.show();
            },
            complete: function () {
                alert.removeClass("alert-primary");
            },
            success: function (res, textStatus, xhr) {
                <?= !isProduction() ? 'console.log(res);' : '' ?>
                if (xhr.status === 200 && res.status == 3 && res.username) {
                    const html = `<i class="mdi mdi-check-circle-outline me-2"></i>${sponsorLabel} : <strong>${res.username}</strong>`;

                    hasSuccess(html);
                }
            },
            error: function (xhr) {
                <?= !isProduction() ? 'console.log(res);' : '' ?>

                var res = xhr.responseJSON || xhr.responseText;

                if (xhr.status === 400 && res.status) {
                    let msg = "";

                    if (res.status === 1) {
                        msg = "Sponsor Id is required!";
                    } else {
                        msg = "Sponsor Id is invalid!";
                    }

                    hasError(msg);
                }
            },
        });
    });

</script>

<?= $this->endSection('script') ?>