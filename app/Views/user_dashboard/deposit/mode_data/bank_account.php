<?= user_component('input_copy_text', [
    'name' => 'acc_number',
    'label' => 'Account Number',
    'bool_attributes' => 'disabled',
    'value' => escape($data->account_number)
]) ?>

<?= user_component('input_copy_text', [
    'name' => 'ifsc',
    'label' => 'IFSC Code',
    'bool_attributes' => 'disabled',
    'value' => escape($data->ifsc)
]) ?>

<?= user_component('input_copy_text', [
    'name' => 'bank',
    'label' => 'Bank',
    'bool_attributes' => 'disabled',
    'value' => escape($data->bank_name)
]) ?>

<?= user_component('input_copy_text', [
    'name' => 'branch',
    'label' => 'Branch',
    'bool_attributes' => 'disabled',
    'value' => escape($data->bank_branch)
]) ?>