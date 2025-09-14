<?php
$profile_picture = \App\Models\UserModel::getAvatar($user);
?>

<?= $this->extend('admin_dashboard/_partials/app') ?>

<?= $this->section('style') ?>
<link href="<?= base_url('twebsol/styles/profile-picture-upload-preview.css') ?>" rel="stylesheet" />
<style>
    .profile_picture_img {
        height: 150px;
        object-fit: cover;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('slot') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-5">
            <?= view('admin_dashboard/users/_user_actions', ['profile_picture' => $profile_picture]) ?>



            <?= view('admin_dashboard/users/_user_change_password') ?>

            <?= view('admin_dashboard/users/_user_change_tpin') ?>


        </div>


        <div class="col-lg-7 order-1 order-lg-2">

            <!-- User Details -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <?= label('user') ?> Details
                </div>
                <div class="card-body pb-2">

                    <h5 class="font-size-14 mb-3">Account Details</h5>

                    <form id="update_user_details_form" enctype="multipart/form-data" novalidate>
                        <?= csrf_field() ?>

                        <div class="avatar-upload mb-4">
                            <div class="avatar-edit">
                                <input type="file" id="imageUpload" name="profile_picture" accept=".png, .jpg, .jpeg" />
                                <label for="imageUpload">
                                    <i class="fas fa-edit h5 mt-2 ms-2 text-secondary"></i>
                                </label>
                            </div>
                            <div class="avatar-preview">
                                <div id="imagePreview" style="background-image: url(<?= $profile_picture ?>);">
                                </div>
                            </div>
                        </div>



                        <?= admin_component('input_row', [
                            'name' => 'full_name',
                            'label' => 'Full Name',
                            'placeholder' => 'Enter full name',
                            'value' => escape($user->full_name ?? '')
                        ]) ?>

                        <?= admin_component('input_row', [
                            'name' => 'email',
                            'label' => 'Email Address',
                            'placeholder' => 'Enter your email',
                            'value' => escape($user->email ?? '')
                        ]) ?>

                        <?= admin_component('input_row', [
                            'name' => 'phone',
                            'label' => 'Phone Number',
                            'placeholder' => 'Enter your Phone No.',
                            'value' => escape($user->phone ?? '')
                        ]) ?>


                        <?= admin_component('button', [
                            'label' => 'Save Changes',
                            'icon' => 'fa-solid fa-floppy-disk',
                            'class' => 'float-end w-100 btn-lg mt-3',
                            'id' => 'update_btn',
                            'submit' => true
                        ]) ?>

                    </form>
                </div>
            </div>

            <?php if (!$user->status): ?>
                <button class="btn btn-dark mb-3" id="activateBtn">
                    
                </button>
            <?php endif; ?>



        </div>

    </div>


</div>

<?= $this->endSection() ?>


<?= $this->section('script') ?>

<script>
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


    function postErrorHandler(xhr, formSelector) {
        <?= !isProduction() ? 'console.log(xhr);' : '' ?>
        var res = xhr.responseJSON || xhr.responseText;
        if (xhr.status === 400 && res.errors) {
            if (res.errors.validationErrors) {
                $(formSelector).validate({
                    focusInvalid: true
                }).showErrors(res.errors.validationErrors);
                // Manually scroll to the first input with class 'is-invalid'
                const firstInvalidInput = $(formSelector).find('.is-invalid').first();
                scrollToElement(firstInvalidInput);
            }
            if (res.errors.error) {
                sAlert('error', '', res.errors.error);
            }
        }
    }




    $(document).ready(function () {


        /*
         *------------------------------------------------------------------------------------
         * Generating Nominee Relations Select Menu
         *------------------------------------------------------------------------------------
         */
        const nomineeRelationMenu = <?= json_encode($nomineeRelations) ?>;
        const userNomineeRel = "<?= escape($userDetails->nominee_relation ?? '') ?>";

        populateSelect('#nominee_relation_select', nomineeRelationMenu, userNomineeRel);



        /*
         *------------------------------------------------------------------------------------
         * User Profile Update
         *------------------------------------------------------------------------------------
         */
        const updateFormSelector = '#update_user_details_form';
        validateForm(updateFormSelector, {
            rules: {

                full_name: {
                    required: true,
                    alpha_num_space: true,
                    minlength: 2,
                    maxlength: 100
                },

                email: {
                    required: true,
                    maxlength: 250,
                    email: true
                },

                phone: {
                    required: true,
                    number: true,
                    minlength: 10,
                    maxlength: 12
                },

                date_of_birth: {
                    required: false
                },

                gender: {
                    required: false,
                    alpha_num: true
                },

                father_name: {
                    required: false,
                    alpha_num_space: true,
                    minlength: 2,
                    maxlength: 100
                },
                mother_name: {
                    required: false,
                    alpha_num_space: true,
                    minlength: 2,
                    maxlength: 100
                },
                employement_status: {
                    required: false,
                    alpha_num: true
                },
                occupation: {
                    required: false,
                    alpha_num_space: true,
                    maxlength: 220
                },

                postal_code: {
                    required: false,
                    number: true,
                    minlength: 4,
                    maxlength: 20
                },
                state: {
                    required: false,
                    maxlength: 220
                },
                city: {
                    required: false,
                    maxlength: 220
                },
                address: {
                    required: false,
                    maxlength: 1000
                },

                nominee: {
                    required: false,
                    maxlength: 220
                },
                nominee_relation: {
                    required: false
                },
                nominee_phone: {
                    required: false,
                    minlength: 10,
                    maxlength: 12
                },
                nominee_email: {
                    required: false,
                    maxlength: 250,
                    email: true
                },
                nominee_address: {
                    required: false,
                    maxlength: 1000
                },
            },
            submitHandler: function (form) {

                const formData = new FormData(form);
                formData.append('action', 'profile');

                const btnContent = $('#update_btn span').html();

                const enableButton = () => {
                    $('#update_btn span').html(btnContent);
                    enable_form(updateFormSelector);
                };

                $.ajax({
                    url: "<?= route('admin.users.user', $user->user_id) ?>",
                    method: "POST",
                    data: formData,
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

                        if (xhr.status === 200) {

                            toast.success('User Details Updated!');

                            if (res.profile_picture_url) {
                                $('.profile_picture_img').attr('src', res.profile_picture_url);
                                $('#imageUpload').val('');
                            }
                        }

                    },
                    error: function (xhr) {
                        postErrorHandler(xhr, updateFormSelector);
                    }
                });

                return false;
            }
        });





        /*
         *------------------------------------------------------------------------------------
         * User Password Change Form
         *------------------------------------------------------------------------------------
         */
        const changePasswordFormSelector = '#change_password_form';
        const minPasswordLength = <?= _setting('password_min_length') ?>;

        // validating
        validateForm(changePasswordFormSelector, {
            rules: {
                npassword: {
                    required: true,
                    no_trailing_spaces: true,
                    minlength: minPasswordLength
                },
                cnpassword: {
                    required: true,
                    no_trailing_spaces: true,
                    minlength: minPasswordLength,
                    equalTo: "._npassword",
                },
            },
            submitHandler: function (form) {

                const formData = new FormData(form);
                formData.append('action', 'password');

                const btnContent = $('#change_password_btn span').html();

                const enableButton = () => {
                    $('#change_password_btn span').html(btnContent);
                    enable_form(changePasswordFormSelector);
                };

                $.ajax({
                    url: "<?= route('admin.users.user', $user->user_id) ?>",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function () {

                        $('#change_password_btn span').html(spinnerLabel({
                            label: 'Changing Password'
                        }));

                        disable_form(changePasswordFormSelector);
                    },
                    complete: function () {
                        enableButton();
                    },
                    success: function (res, textStatus, xhr) {

                        <?= !isProduction() ? 'console.log(res);' : '' ?>

                        if (xhr.status === 200) {

                            clearInputs(changePasswordFormSelector);

                            toast.success('Password Updated!');
                        }

                    },
                    error: function (xhr) {
                        postErrorHandler(xhr, changePasswordFormSelector);
                    }
                });

                return false;
            }
        });







        /*
         *------------------------------------------------------------------------------------
         * User Tpin Change
         *------------------------------------------------------------------------------------
         */
        const changeTpinFormSelector = '#change_tpin_form';
        const tpin_digits = <?= _setting('tpin_digits', 6) ?>;
        const tpinLabel = "<?= label('tpin') ?>";

        // validating
        validateForm(changeTpinFormSelector, {
            rules: {
                ntpin: {
                    required: true,
                    no_trailing_spaces: true,
                    number: true,
                    exactDigits: tpin_digits
                },
                cntpin: {
                    required: true,
                    no_trailing_spaces: true,
                    number: true,
                    exactDigits: tpin_digits,
                    equalTo: "._ntpin",
                }
            },
            submitHandler: function (form) {

                const formData = new FormData(form);
                formData.append('action', 'tpin');
                const btnContent = $('#change_tpin_btn span').html();

                const enableButton = () => {
                    $('#change_tpin_btn span').html(btnContent);
                    enable_form(changeTpinFormSelector);
                };

                $.ajax({
                    url: "<?= route('admin.users.user', $user->user_id) ?>",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function () {

                        $('#change_tpin_btn span').html(spinnerLabel({
                            label: 'Changing Password'
                        }));

                        disable_form(changeTpinFormSelector);
                    },
                    complete: function () {
                        enableButton();
                    },
                    success: function (res, textStatus, xhr) {

                        <?= !isProduction() ? 'console.log(res);' : '' ?>

                        if (xhr.status === 200) {

                            clearInputs(changeTpinFormSelector);

                            toast.success(tpinLabel + ' Updated!');
                        }

                    },
                    error: function (xhr) {
                        postErrorHandler(xhr, changeTpinFormSelector);
                    }
                });

                return false;
            }
        });


        $('#activateBtn').on('click', function () {
            const userId = $(this).data('user-id');
            const url = "<?= route('admin.users.user', $user->user_id) ?>";
            const csrfName = 'twebsol_token';
            const csrfHash = $('#csrf').attr('content');

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to activate this user?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, activate!',
                cancelButtonText: 'Cancel',
                showLoaderOnConfirm: true,
                allowOutsideClick: () => !Swal.isLoading(),
                allowEscapeKey: false,
                preConfirm: () => {
                    return $.post(url, {
                        action: 'activate',
                        [csrfName]: csrfHash
                    }).then(response => {
                        // Optional: update token if server returns new one
                        if (response.csrfHash) {
                            $('#csrf').attr('content', response.csrfHash);
                        }
                        return response;
                    }).catch(error => {
                        Swal.showValidationMessage(
                            `Request failed: ${error.responseText}`
                        );
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                        'Activated!',
                        'User has been successfully activated.',
                        'success'
                    );
                    $('#activateBtn').remove();
                }
            });
        });



    });
</script>
<?= $this->endSection() ?>