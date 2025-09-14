<?php
// Registration Confirmation Email with OTP
$company = data('company_name_in_emails');
?>
<p>
    Hi <?= "$fullName" ?>,
    <br>
    <br>
    Welcome to <?= $company ?>! We're excited to have you on board.
    <br>
    <br>
    To complete your registration, please use the OTP (One-Time Password) below to verify your email address:
    <br>
    <br>
    <strong style="font-size: 18px; letter-spacing: 2px;"><?= $otp ?></strong>
    <br>
    <br>
    This OTP is valid for 60 minutes. If you did not initiate this registration, please ignore this email.
    <br>
    <br>
    Best Regards,
    <br>
    <br>
    <?= $company ?> Team
</p>