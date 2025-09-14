<div class="mt-4 p-5 bg-danger text-white rounded">
    <h1>You have not made any referrals.</h1>
    <p>You can see you referral downline, when you refer other <?= label('users', 1) ?></p>
    <hr>
    <p>You can make referrals using the button below.</p>

    <a href="<?= route('referral', user('user_id')) ?>" target="_blank">
        <?= user_component('button', [
            'class' => 'btn-lg',
            'label' => 'Refer',
            'icon' => 'fa-solid fa-user-plus'
        ]) ?>
    </a>
</div>