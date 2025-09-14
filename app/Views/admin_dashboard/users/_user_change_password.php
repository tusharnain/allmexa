<div class="card">
    <div class="card-header bg-info text-white">
        Change Account Password
    </div>
    <div class="card-body pb-0">

        <form id="change_password_form">
            <?= csrf_field() ?>
            <?= admin_component('input', [
                'type' => 'password',
                'name' => 'npassword',
                'label' => 'New Password',
                'class' => '_npassword',
                'placeholder' => 'Enter new password'
            ]) ?>

            <?= admin_component('input', [
                'type' => 'password',
                'name' => 'cnpassword',
                'label' => 'Confirm New Password',
                'placeholder' => 'Enter new password again'
            ]) ?>

            <?= admin_component('button', [
                'label' => 'Update',
                'submit' => true,
                'color' => 'info',
                'icon' => 'mdi mdi-lock-reset',
                'class' => 'float-end',
                'id' => 'change_password_btn'
            ]) ?>
        </form>

    </div>
</div>