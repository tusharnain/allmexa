<?php

if (isset($test)) {
    $full_name = 'George Washington';
    $user_id = '456734';
    $password = 'George@1999';
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        Thank You for Registration |
        <?= data('company_name_in_emails') ?>
    </title>
</head>

<body
    style="font-family: 'Arial', sans-serif; background-color: #ffffff; text-align: center; margin: 0; padding-top : 05px;">

    <div
        style="max-width: 600px; margin: 50px auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); border : 2px solid #3498db;">
        <a href="/" target="_blank">
            <div style="max-width: 150px; margin: 20px auto; overflow: hidden;">
                <img src="<?= base_url('images/logo.png') ?>?v=0.1" alt="Company Logo" style="width: 100%;">
            </div>
        </a>
        <div style="color: #555; font-size: 18px; margin-bottom: 20px;">
            <?= data('company_name') ?>
        </div>


        <h2 style="color: #294da2;">Hey
            <?= escape($full_name) ?> 👋
        </h2>
        <h3 style="color: #3498db;">Thank You for Registration</h3>
        <p style="color: #555; line-height: 1.6;">Your registration was successful. Here are your login credentials:</p>

        <div
            style="padding: 15px; background-color: #e0f3ff; border-radius: 5px; display : flex; justify-content: center;">
            <div style="width : fit-content; text-align : left;">
                <p style="font-size: 16px; margin: 0;"><strong>
                        <?= label('user_id') ?>:
                    </strong>
                    <?= escape($user_id) ?>
                </p>
                <p style="font-size: 16px; margin: 0; margin-top:10px;"><strong>Password:</strong>
                    <?= escape($password) ?>
                </p>
                <?php if (isset($tpin)): ?>
                    <p style="font-size: 16px; margin: 0; margin-top:10px;"><strong>Secure Password:</strong>
                        <?= escape($tpin) ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>

        <p style="color: #777; font-size: 14px; margin-top: 20px;">Note: For security reasons, please keep your login
            credentials confidential.</p>
        <!-- Login URL -->
        <p style="margin-top: 20px; font-size: 14px;">
            Login your account <a href="<?= escape(base_url('login')) ?>" target="_blank"
                style="color: #3498db; text-decoration: underline;">here</a>.
        </p>
    </div>

    <hr style="margin: 20px 0; border: none; border-top: 1px solid #ddd;">

    <p style="color: #777; font-size: 12px;">
        &copy;
        <?= date('Y') ?>
        <?= data('company_name_in_emails') ?>. All rights reserved.
    </p>

</body>

</html>