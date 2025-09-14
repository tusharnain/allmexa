<?php
// Reset Password Link Email 
$company = data('company_name_in_emails');
?>
<p>
    Hi <?= "$user->full_name ($user->user_id)" ?>,
    <br>
    <br>
    Someone (hopefully you) requested a password reset for your <?= $company ?> account.
    Click on the link below to reset your password.
    <br>
    <br>
    <a href="<?= $url ?>" target="_blank"><?= $url ?></a>
    <br>
    <br>
    This link will expire in 30 minutes. If you did not request a reset, you can
    safely ignore this email.
    <br>
    <br>
    Best Regards,
    <br>
    <br>
    <?= $company ?> Team
</p>