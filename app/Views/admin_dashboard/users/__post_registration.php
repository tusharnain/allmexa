<?php

// Files starting with __(double underscore) are just rendered html snippets
?>

<div>
    <h4>
        Thank You
        <strong class="text-success">
            <?= $full_name ?>
        </strong>
        for your registration on
        <strong class="text-primary">
            <?= data('company_name') ?>
        </strong>
    </h4>
    <h4 class="mb-4">Below are the login credentials</h4>


    <div class="text-start">
        <?= admin_component('input_copy_text', [
            'label' => label('user_id'),
            'class' => 'form-control-lg',
            'icon' => 'mdi mdi-account-circle',
            'name' => 'post_register_user_id',
            'bool_attributes' => 'readonly',
            'value' => $user_id,
        ]) ?>

        <?= admin_component('input_copy_text', [
            'label' => 'Password',
            'class' => 'form-control-lg',
            'icon' => 'zmdi zmdi-lock',
            'name' => 'post_register_password',
            'bool_attributes' => 'readonly',
            'value' => $password,
        ]) ?>
    </div>

    <?php if (_setting('email_login_credentials_after_registration')): ?>
        <h4 class="mt-4">
            The Login credentials has also been sent to
            <strong class="text-success">
                <?= $email ?>
            </strong>.
        </h4>
    <?php endif; ?>


    <?php if (!!false): ?>
        <?= admin_component('button', [
            'label' => 'Download Text File',
            'icon' => 'fa fa-download',
            'color' => 'dark mt-2',
            'onClick' => "downloadTextFile('$textContent', '$textFileName');"
        ]) ?>

        <?= admin_component('button', [
            'label' => 'Save Screenshot',
            'icon' => 'mdi mdi-image-filter-center-focus',
            'color' => 'info mt-2',
            'onClick' => "downloadImage('.post_registration_popup', '$imageFileName');"
        ]) ?>
    <?php endif; ?>
</div>