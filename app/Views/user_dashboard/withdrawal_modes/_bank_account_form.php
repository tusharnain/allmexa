<div class="card">
    <div class="card-body">

        <?= user_component('alert', [
            'icon' => 'fa-solid fa-exclamation-circle',
            'text' => isset($bank->updated_at) ? "Bank Details last updated on $bank->updated_at." : '',
            'id' => 'bank_updated_alert',
            'hidden' => !isset($bank->updated_at)
        ]) ?>


        <?= user_component('input', [
            'name' => 'bank_name',
            'label' => 'Bank Name',
            'disabled' => true,
            'value' => $bank->bank
        ]) ?>

        <?= user_component('input', [
            'name' => 'ifsc_code',
            'label' => 'IFSC Code',
            'disabled' => true,
            'value' => $bank->code
        ]) ?>


        <?php if ($bank->branch): ?>
            <?= user_component('input', [
                'name' => 'bank_branch',
                'label' => 'Branch',
                'disabled' => true,
                'value' => $bank->branch
            ]) ?>
        <?php endif; ?>

        <form id="bank_account_form">
            <input type="hidden" name="bank_ifsc" value="<?= $bank->code ?>">

            <?= user_component('input', [
                'name' => 'account_holder_name',
                'label' => 'Account Holder Name',
                'placeholder' => 'Enter account holder name',
                'value' => $bank->accHolderName ?? '',
            ]) ?>

            <?= user_component('input', [
                'name' => 'account_number',
                'label' => 'Account No.',
                'placeholder' => 'Enter account no.',
                'value' => $bank->accountNumber ?? '',
            ]) ?>



            <div class="col-md-4 px-0">
                <?= user_component('input', [
                    'name' => 'tpin',
                    'id' => 'tpin_inp',
                    'label' => label('tpin'),
                    'placeholder' => 'Enter ' . label('tpin'),
                ]) ?>
            </div>



            <div class="text-end">
                <?= user_component('button', [
                    'submit' => true,
                    'label' => 'Save Details',
                    'class' => 'mobile-button',
                    'icon' => 'fa-solid fa-bank',
                    'id' => 'bank_account_btn',
                ]) ?>
            </div>
        </form>

    </div>
</div>