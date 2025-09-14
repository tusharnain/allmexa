<div class="d-flex align-items-center">
    <?= user_component('select', [
        'name' => 'level',
        'label' => 'Select Level',
        'options' => $selectArray,
        'empty_option' => 'Select Level',
        'disable_empty_option' => true,
        'select_empty_option' => true,
        'id' => 'level_select',
        'other_attr' => 'onchange="updateLevel(event);"'
    ]) ?>
    <div id="ult-loader" style="display: none;">
        <h4 class="ms-2 mt-2">
            <i class="fa-solid fa-spinner fa-spin"></i>
        </h4>
    </div>
</div>