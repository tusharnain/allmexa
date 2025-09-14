<?php
$isWalletLocked = (isset($wallet->locked) and $wallet->locked);
?>

<?= $this->extend('user_dashboard/layout/master') ?>


<?= $this->section('style') ?>
<style>
    .wallet-qr {
        width: 100%;
        max-width: 300px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('slot') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-body" id="wallet_update_page">

                    <?= view('user_dashboard/withdrawal_modes/_wallet_address_form', ['wallet' => $wallet]) ?>

                </div>
            </div>

        </div>
    </div>
</div>


<?= $this->endSection() ?>

<?php $this->section('script') ?>
<script>

    const lock = <?= $isWalletLocked ? 'true' : 'false' ?>;

    if (lock) {
        disable_form('#wallet-form');
    } else {
        $(document).ready(function () {
            Dashboard.setupWalletAddressForm({
                formSelector: '#wallet-form',
                tpin_digits: <?= _setting('tpin_digits', 6) ?>,
                url: '<?= current_url() ?>',
                isProduction: <?= isProduction() ? 'true' : 'false' ?>,
            });
        });
    }
</script>
<?php $this->endSection() ?>