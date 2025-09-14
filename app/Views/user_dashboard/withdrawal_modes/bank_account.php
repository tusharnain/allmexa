<?php
$isBankLocked = (isset ($bank->locked) and $bank->locked);
?>

<?= $this->extend('user_dashboard/layout/master') ?>


<?= $this->section('slot') ?>

<div class="container-fluid">
    
    <?php if(!$bank): ?>
        <div class="alert alert-danger">
            Bank A/c is not updated yet!
        </div>
    <?php endif ?>

    <div class="row">
        <div class="col-lg-8 col-xl-7">

            <?php if (!$isBankLocked): ?>
                <div class="card" id="ifsc_search_container">
                    <div class="card-body">
                        <form id="ifsc_search_form">
                            <?= user_component('input', [
                                'name' => 'ifsc_code',
                                'label' => 'Bank IFSC Code',
                                'placeholder' => 'Enter Bank IFSC Code',
                                'id' => 'ifsc_f',
                            ]) ?>

                            <div class="text-end">
                                <?= user_component('button', [
                                    'submit' => true,
                                    'label' => 'Search',
                                    'icon' => 'fa-solid fa-magnifying-glass',
                                    'id' => 'ifsc_search_btn'
                                ]) ?>
                            </div>
                        </form>

                    </div>
                </div>
            <?php endif; ?>

            <div id="bank_account_form_container">
                <?= $bank ? view('user_dashboard/withdrawal_modes/_bank_account_form', ['bank' => &$bank]) : '' ?>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>


<?php $this->section('script') ?>
<script>


    $(document).ready(function () {

        const lock = <?= $isBankLocked ? 'true' : 'false' ?>;

        if (lock) {
            disable_form('#bank_account_form');
        } else {
            Dashboard.setupBankDetailsForm({
                ifscSearchForm: '#ifsc_search_form',
                bankFormSelector: '#bank_account_form',
                tpin_digits: <?= _setting('tpin_digits', 6) ?>,
                url: '<?= current_url() ?>',
                isProduction: <?= isProduction() ? 'true' : 'false' ?>,
            });
        }
    });

</script>
<?php $this->endSection() ?>