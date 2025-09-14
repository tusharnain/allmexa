<?php

$profile_picture = \App\Models\UserModel::getAvatar($user);


function is_disable(string $field): bool
{
    static $restrict = null;
    if (!$restrict)
        $restrict = array_flip(\App\Twebsol\Settings::USER_PROFILE_UPDATE_RESTRICTIONS);
    return isset($restrict[$field]);
}

?>


<?= $this->extend('user_dashboard/layout/master') ?>

<?= $this->section('style') ?>
<link href="<?= base_url('twebsol/styles/profile-picture-upload-preview.css') ?>" rel="stylesheet" />
<?= $this->endSection() ?>

<?= $this->section('slot') ?>

<div class="container-fluid">
    <form id="update_user_details_form" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="row">

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>
                            Profile Picture
                        </h5>
                    </div>

                    <div class="card-body text-center">
                        <div class="avatar-upload">
                            <div class="avatar-edit">
                                <input type="file" id="imageUpload" name="profile_picture" accept=".png, .jpg, .jpeg" />
                                <?php if (!is_disable('profile_picture')): ?>
                                    <label for="imageUpload"></label>
                                <?php endif; ?>
                            </div>
                            <div class="avatar-preview">
                                <div id="imagePreview" style="background-image: url(<?= $profile_picture ?>);">
                                </div>
                            </div>
                        </div>


                        <div class="mt-3">
                            <h4>
                                <?= escape($user->full_name) ?>
                            </h4>
                            <h6>
                                <?= escape($user->user_id) ?>
                            </h6>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-8">

                <div class="card">
                    <div class="card-header">
                        <h5>
                            <?= label('user') ?> Details
                        </h5>
                    </div>

                    <div class="card-body pt-2">

                        <?= user_component('input', [
                            'name' => 'full_name',
                            'label' => label('user_name'),
                            'placeholder' => 'Enter full name',
                            'value' => escape($user->full_name ?? ''),
                            'disabled' => is_disable('full_name')
                        ]) ?>

                        <?= user_component('input', [
                            'name' => 'email',
                            'label' => 'Email Address',
                            'placeholder' => 'Enter your email',
                            'value' => escape($user->email ?? ''),
                            'disabled' => is_disable('email')
                        ]) ?>


                    </div>
                </div>



                <?= user_component('button', [
                    'label' => 'Save Changes',
                    'icon' => 'fa-solid fa-floppy-disk',
                    'class' => 'btn-lg btn-block w-100 mb-4',
                    'id' => 'update_btn',
                    'submit' => true,
                    'disabled' => true
                ]) ?>

            </div>



        </div>
    </form>
</div>


<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    <?php if (!is_disable('profile_picture')): ?>
        // Image Upload & Preview
        function readURL(input) {

            const previewImage = $('#imagePreview');

            const clearImageInput = () => {
                input.value = '';
                previewImage.css('background-image', '<?= "url($profile_picture)" ?>');
            };

            if (input.files && input.files[0]) {

                const file = input.files[0];

                // var maxSizeInBytes = 1 * 1024 * 1024; // 1 MB
                var maxSizeInBytes = 150 * 1024; // 150 KB

                //validation
                if (!file.type.startsWith('image/')) {
                    clearImageInput();
                    return sAlert('error', 'Upload Error', 'Please upload only images.');
                }

                if (file.size > maxSizeInBytes) {
                    clearImageInput();
                    return sAlert('error', 'Upload Error', 'File size exceeds the limit. Please upload a smaller image.');
                }

                image_upload_error = 0;

                var reader = new FileReader();
                reader.onload = function (e) {
                    previewImage.css('background-image', 'url(' + e.target.result + ')');
                    previewImage.hide();
                    previewImage.fadeIn(650);
                };
                reader.readAsDataURL(file);
            } else {

                clearImageInput();

            }
        }
        $("#imageUpload").change(function () {
            readURL(this);
        });

    <?php endif; ?>


    $(document).ready(function () {
        /*
         *------------------------------------------------------------------------------------
         * Generating Nominee Relations Select Menu
         *------------------------------------------------------------------------------------
         */
        const nomineeRelationMenu = <?= json_encode($nomineeRelations) ?>;
        const userNomineeRel = "<?= escape($ud->nominee_relation ?? '') ?>";

        populateSelect('#nominee_relation_select', nomineeRelationMenu, userNomineeRel);


        /*
         *------------------------------------------------------------------------------------
         * Profile Update
         *------------------------------------------------------------------------------------
         */

        // form selector
        const updateFormSelector = '#update_user_details_form';

        // putting desabled attribute to disabled inputs
        $(updateFormSelector + ' :disabled').each(function () {
            $(this).attr('data-disabled', 'true');
        });

        // user update restriction array
        const restricts = <?= json_encode(\App\Twebsol\Settings::USER_PROFILE_UPDATE_RESTRICTIONS) ?>;

        // restriction find function
        const isDisable = (field) => {
            if (restricts.includes(field))
                return {};
            return false;
        };

        // validating
        validateForm(updateFormSelector, {
            rules: {
                full_name: isDisable('full_name') ?? {
                    required: true,
                    minlength: 2,
                    maxlength: 100,
                    alpha_num_space: true
                },

                email: isDisable('email') ?? {
                    required: true,
                    maxlength: 250,
                    email: true
                },

                phone: isDisable('phone') ?? {
                    required: true,
                    number: true,
                    minlength: 10,
                    maxlength: 12
                },

                // date_of_birth: isDisable('date_of_birth') ?? {
                //     required: false
                // },

                // gender: isDisable('gender') ?? {
                //     required: false,
                //     alpha_num: true
                // },

                // father_name: isDisable('father_name') ?? {
                //     required: false,
                //     minlength: 2,
                //     maxlength: 100,
                //     alpha_num_space: true
                // },

                // mother_name: isDisable('mother_name') ?? {
                //     required: false,
                //     minlength: 2,
                //     maxlength: 100,
                //     alpha_num_space: true
                // },

                // employement_status: isDisable('employement_status') ?? {
                //     required: false,
                //     alpha_num: true
                // },

                // occupation: isDisable('occupation') ?? {
                //     required: false,
                //     alpha_num_space: true,
                //     maxlength: 220
                // },

                // postal_code: isDisable('postal_code') ?? {
                //     required: false,
                //     number: true,
                //     minlength: 4,
                //     maxlength: 20
                // },

                // state: isDisable('state') ?? {
                //     required: false,
                //     maxlength: 220
                // },

                // city: isDisable('city') ?? {
                //     required: false,
                //     maxlength: 220
                // },

                address: isDisable('address') ?? {
                    required: true,
                    maxlength: 1000
                },

                nominee: isDisable('nominee') ?? {
                    required: false,
                    maxlength: 220
                },

                nominee_relation: isDisable('nominee_relation') ?? {
                    required: false
                },

                // nominee_phone: isDisable('nominee_phone') ?? {
                //     required: false,
                //     minlength: 10,
                //     maxlength: 12
                // },

                // nominee_email: isDisable('nominee_email') ?? {
                //     required: false,
                //     maxlength: 250,
                //     email: true
                // },
                // nominee_aadhar: isDisable('nominee_aadhar') ?? {
                //     required: false,
                //     number: true,
                //     exactDigits: 12
                // },
                // nominee_address: isDisable('nominee_address') ?? {
                //     required: false,
                //     maxlength: 1000
                // },
            },
            submitHandler: function (form) {

                const formData = new FormData(form);

                const btnContent = $('#update_btn span').html();

                const enableButton = () => {
                    $('#update_btn span').html(btnContent);
                    enable_form(updateFormSelector);

                    // disabling the already disabled fields
                    $(updateFormSelector + ' [data-disabled="true"]').prop('disabled', true);

                };

                $.ajax({
                    url: "<?= route('user.profile.profileUpdatePost') ?>",
                    method: "POST",
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    beforeSend: function () {

                        $('#update_btn span').html(spinnerLabel());

                        disable_form(updateFormSelector);
                    },
                    complete: function () {
                        enableButton();
                    },
                    success: function (res, textStatus, xhr) {

                        <?= !isProduction() ? 'console.log(res);' : '' ?>

                        res.message && Swal.fire({ icon: 'success', text: res.message }).then(function () {
                            location.reload();
                        });

                    },
                    error: function (xhr) {

                        <?= !isProduction() ? 'console.log(xhr);' : '' ?>

                        var res = xhr.responseJSON || xhr.responseText;

                        if (xhr.status === 400 && res.errors) {

                            if (res.errors.validationErrors) {

                                $(updateFormSelector).validate({
                                    focusInvalid: true
                                }).showErrors(res.errors.validationErrors);

                                if (res.errors.validationErrors.profile_picture)
                                    sAlert('error', 'Upload Error', res.errors.validationErrors.profile_picture);

                                // Manually scroll to the first input with class 'is-invalid'
                                const firstInvalidInput = $(updateFormSelector).find('.is-invalid').first();
                                scrollToElement(firstInvalidInput);
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