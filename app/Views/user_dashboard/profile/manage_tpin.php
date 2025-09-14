<?php
$tpinLabel = label('tpin');
$tpinLabelShort = label('tpin', 1);
?>

<?= $this->extend('user_dashboard/layout/master') ?>


<?= $this->section('slot') ?>

<div class="container-fluid">

    <?= user_component('app/no_tpin_alert', ['hasTpin' => $hasTpin]) ?>

    <div id="last_tpin_alert">
        <?php if ($lastTpinChangedAlert): ?>
            <?= $lastTpinChangedAlert ?>
        <?php endif; ?>
    </div>


    <div class="card">
        <div class="card-header py-3">
            <h5>
                <span id="create_label">
                    <?= $hasTpin ? 'Update' : 'Create' ?>
                </span>
                <?= $tpinLabel ?>
            </h5>
        </div>
        <div class="card-body pt-3">

            <form id="change_tpin_form">

                <?= csrf_field() ?>

                <div class="row">

                    <div class="col-xl-4 col-lg-6" id="ctpin_field_col" <?= !$hasTpin ? 'style="display:none;"' : '' ?>>
                        <?= user_component('input', [
                            'label' => "Current $tpinLabel",
                            'icon' => 'fa-solid fa-eye',
                            'name' => 'ctpin',
                            'type' => 'password',
                            'placeholder' => "Enter current $tpinLabelShort",
                            'groupId' => 'Password-toggle1',
                            'id' => 'ctpin_field'
                        ]) ?>
                    </div>


                    <div class="col-xl-4 col-lg-6">
                        <?= user_component('input', [
                            'label' => "New $tpinLabel",
                            'class' => '_ntpin',
                            'icon' => 'fa-solid fa-eye',
                            'name' => 'ntpin',
                            'type' => 'password',
                            'placeholder' => "Enter new $tpinLabelShort",
                            'groupId' => 'Password-toggle2'
                        ]) ?>
                    </div>

                    <div class="col-xl-4 col-lg-6">
                        <?= user_component('input', [
                            'label' => "Confirm $tpinLabel",
                            'icon' => 'fa-solid fa-eye',
                            'name' => 'cntpin',
                            'type' => 'password',
                            'placeholder' => "Enter new $tpinLabelShort again",
                            'groupId' => 'Password-toggle3'
                        ]) ?>
                    </div>
                </div>


                <div class="row">
                    <div class="col-xl-4 col-lg-6">
                        <?= user_component('input', [
                            'label' => 'Confirm Account Password',
                            'icon' => 'fa-solid fa-eye',
                            'name' => 'password',
                            'type' => 'password',
                            'placeholder' => 'Enter Account Password',
                            'groupId' => 'Password-toggle4'
                        ]) ?>
                    </div>
                </div>

                <div class="text-end">
                    <?= user_component('button', [
                        'label' => 'Change Password',
                        'class' => 'btn-lg mobile-button',
                        'icon' => 'fa-solid fa-lock',
                        'id' => 'change_tpin_btn',
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
        const formSelector = '#change_tpin_form';
        const tpin_digits = <?= _setting('tpin_digits', 6) ?>;
        var hasTpin = <?= $hasTpin ? 'true' : 'false' ?>;

        const ctpin_rules = { required: true, no_trailing_spaces: true, number: true, exactDigits: tpin_digits };

        // validating
        validateForm(formSelector, {
            rules: {
                ctpin: hasTpin ? ctpin_rules : {},
                ntpin: { required: true, no_trailing_spaces: true, number: true, exactDigits: tpin_digits },
                cntpin: { required: true, no_trailing_spaces: true, number: true, exactDigits: tpin_digits, equalTo: "._ntpin", },
                password: { required: true, no_trailing_spaces: true }
            },
            submitHandler: function (form) {

                const formData = new FormData(form);

                const btnContent = $('#change_tpin_btn').html();

                const enableButton = () => {
                    $('#change_tpin_btn').html(btnContent);
                    enable_form(formSelector);
                };

                $.ajax({
                    url: "<?= route('user.profile.manageTpin') ?>",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function () {

                        $('#change_tpin_btn').html(spinnerLabel({ label: 'Changing Password' }));

                        disable_form(formSelector);
                    },
                    complete: function () {
                        enableButton();
                    },
                    success: function (res, textStatus, xhr) {

                        <?= !isProduction() ? 'console.log(res);' : '' ?>

                        if (xhr.status === 200) {

                            clearInputs(formSelector);


                            if (!hasTpin) {
                                if ($('#ctpin_field_col').length > 0 && $('#ctpin_field').length > 0) {
                                    $('#ctpin_field_col').show();
                                    const ctpn_inp = $('#ctpin_field');

                                    $(formSelector).validate().settings.rules[ctpn_inp.attr('name')] = ctpin_rules;
                                    hasTpin = true;
                                } else {
                                    location.reload();
                                }
                            }

                            if ($('#create_label').length > 0) $('#create_label').text('Update');

                            if ($('#np_tpin_alert').length > 0) $('#np_tpin_alert').remove();

                            if (res.message && res.title)
                                sAlert('success', res.title, res.message);

                            if (res.lastTpinAlert) {
                                $('#last_tpin_alert').html(res.lastTpinAlert);
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