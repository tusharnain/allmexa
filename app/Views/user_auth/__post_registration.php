<?php

// Files starting with __(double underscore) are just rendered html snippets
?>

<div>
    <?php if (!!false): ?>
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
    <?php endif; ?>
    <h4>Login Credentials</h4>


    <div class="text-start">
        <?= user_component('input_copy_text', [
            'label' => label('user_id'),
            'class' => 'form-control-lg',
            'name' => 'post_register_user_id',
            'bool_attributes' => 'readonly',
            'value' => $user_id,
        ]) ?>

        <?= user_component('input_copy_text', [
            'label' => 'Password',
            'class' => 'form-control-lg',
            'name' => 'post_register_tpin',
            'bool_attributes' => 'readonly',
            'value' => $password,
        ]) ?>

        <?= user_component('input_copy_text', [
            'label' => 'TPIN',
            'class' => 'form-control-lg',
            'name' => 'post_register_tpin',
            'bool_attributes' => 'readonly',
            'value' => $tpin,
        ]) ?>
    </div>

    <?php if (_setting('email_login_credentials_after_registration')): ?>
        <h4 class="mt-3">
            Your Login credentials has also been sent to
            <strong class="text-success">
                <?= $email ?>
            </strong>.
        </h4>
    <?php endif; ?>


    <?php if (!!false): ?>
        <?= user_component('button', [
            'label' => 'Download Text File',
            'icon' => 'fa-solid fa-download',
            'color' => 'dark mt-2',
            'onClick' => "downloadTextFile('$textContent', '$textFileName');"
        ]) ?>

        <?= user_component('button', [
            'label' => 'Save Screenshot',
            'icon' => 'fa-regular fa-file-image',
            'color' => 'info mt-2',
            'onClick' => "downloadImage('.post_registration_popup', '$imageFileName');"
        ]) ?>
    <?php endif; ?>
</div>