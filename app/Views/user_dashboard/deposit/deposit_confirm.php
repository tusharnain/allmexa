<?php

$modeDataDir = 'user_dashboard/deposit/mode_data';

?>

<?= $this->extend('user_dashboard/layout/master') ?>

<?php $this->section('style') ?>
<style>
    .preview-image img {
        max-width: 100%;
        width: 200px;
        height: 200px;
        object-fit: cover;
        margin-bottom: 10px;
    }
</style>
<?php $this->endSection() ?>

<?= $this->section('slot') ?>

<div class="container-fluid">

    <div class="row">


        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5>
                        <?= $mode->title ?> [Deposit]
                    </h5>
                </div>
                <div class="card-body">

                    <?= view("$modeDataDir/$mode->name", ['data' => &$mode->data]) ?>

                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">

                    <?= user_component('input', [
                        'name' => 'amount',
                        'label' => 'Deposit Amount',
                        'value' => $amount,
                        'disabled' => true
                    ]) ?>

                    <form id="deposit_form">

                        <?= user_component('input', [
                            'name' => 'utr',
                            'label' => 'UTR Number',
                            'placeholder' => 'Enter UTR Number (a.k.a Transaction Number)'
                        ]) ?>

                        <?= (isset($mode->data->receipt_upload) and $mode->data->receipt_upload) ? user_component('input', [
                            'type' => 'file',
                            'name' => 'receipt_file',
                            'label' => 'Receipt File',
                            'id' => 'receipt_file'
                        ]) : '' ?>
                        <div class="preview-image" id="receipt_file_preview"></div>


                        <?= (isset($mode->data->allow_remarks) and $mode->data->allow_remarks) ? user_component('textarea', [
                            'label' => 'Remarks',
                            'name' => 'remarks',
                            'placeholder' => 'Enter Remarks (Optional, Max 250 Characters)',
                            'rows' => 3
                        ]) : '' ?>

                        <div class="row">
                            <div class="col-lg-5">
                                <?= user_component('input', [
                                    'name' => 'tpin',
                                    'label' => label('tpin'),
                                    'placeholder' => "Enter " . label('tpin')
                                ]) ?>
                            </div>
                        </div>

                        <div class="text-end">
                            <?= user_component('button', [
                                'label' => 'Submit Deposit Request',
                                'class' => 'mobile-button',
                                'icon' => 'fa-solid fa-check-circle',
                                'id' => 'dep_btn',
                                'submit' => true,
                            ]) ?>
                        </div>

                    </form>

                </div>
            </div>
        </div>

    </div>

</div>

<?= $this->endSection() ?>

<?php $this->section('script') ?>
<script>

    $(document).ready(function () {

        previewImageOnUpload('#receipt_file', '#receipt_file_preview')

        const formSelector = '#deposit_form';
        const tpin_digits = <?= _setting('tpin_digits', 6) ?>;
        const mode = "<?= $mode->name ?>";


        $.validator.addMethod("mimes", function (value, element, allowedTypes) {
            // receinve array of mimes like ['image/png', 'image/jpeg']
            if (element.files && element.files.length > 0) {
                var fileType = element.files[0].type;
                return allowedTypes.includes(fileType);
            }
            return true;
        }, "Invalid file type");

        $.validator.addMethod("max_size", function (value, element, maxSize) {
            // receive size in KB, just int
            if (element.files && element.files.length > 0) {
                var fileSize = element.files[0].size; // in bytes
                var maxSizeBytes = maxSize * 1024; // convert to bytes

                if (fileSize > maxSizeBytes) {
                    $.validator.messages.max_size = "File size exceeds the limit. Maximum size allowed: {0} KB.";
                    return false;
                }
            }
            return true; // if no file is selected, consider it valid
        }, "File size exceeds the limit");


        $.validator.addMethod("extension", function (value, element, allowedExtensions) {
            //recieve array of extensions like ['png','jpeg']
            if (element.files && element.files.length > 0) {
                var fileName = element.files[0].name;
                var fileExtension = fileName.split('.').pop().toLowerCase();
                return allowedExtensions.includes(fileExtension);
            }
            return true; // if no file is selected, consider it valid
        }, "Invalid file extension");

        // validating
        validateForm(formSelector, {
            rules: {
                utr: { required: true, alpha_num: true, minlength: 5, maxlength: 100 },
                remarks: { required: false, minlength: 1, maxlength: 250 },
                receipt_file: { required: true, extension: ['png', 'jpg', 'jpeg'], mimes: ["image/jpeg", "image/png"], max_size: 250 },
                tpin: { required: true, no_trailing_spaces: true, number: true, exactDigits: tpin_digits }
            },
            messages: {
                receipt_file: {
                    extension: 'Only valid PNG and JPEG files are allowed',
                    mimes: 'Only valid PNG and JPEG files are allowed'
                }
            },
            submitHandler: function (form) {

                sConfirm(function () {

                    const formData = new FormData(form);
                    formData.append('mode', mode);
                    append_csrf(formData);
                    const btnContent = $('#dep_btn span').html();

                    const enableButton = () => {
                        $('#dep_btn span').html(btnContent);
                        enable_form(formSelector);
                    };

                    $.ajax({
                        url: "<?= current_url() ?>",
                        method: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        beforeSend: function () {

                            $('#dep_btn span').html(spinnerLabel({ label: 'Processing Deposit' }));
                            disable_form(formSelector);

                            //loading
                            sProcessingPopup('Processing Deposit...', 'Do not close this window!');
                        },
                        complete: function () {
                            enableButton();
                        },
                        success: function (res, textStatus, xhr) {

                            <?= !isProduction() ? 'console.log(res);' : '' ?>

                            if (xhr.status === 200) {
                                clearInputs(formSelector);
                            }

                        },
                        error: function (xhr) {
                            <?= !isProduction() ? 'console.log(xhr);' : '' ?>
                            var res = xhr.responseJSON || xhr.responseText;
                            if (xhr.status === 400 && res.errors) {
                                if (res.errors.validationErrors) {
                                    $(formSelector).validate({ focusInvalid: true }).showErrors(res.errors.validationErrors);
                                    Swal.close();
                                }
                                if (res.errors.error) {
                                    sAlert('error', '', res.errors.error);
                                }
                            }
                        }
                    });
                },
                    { text: 'Are you sure you want to make deposit?' });
                return false;
            }
        });

    });
</script>
<?php $this->endSection() ?>