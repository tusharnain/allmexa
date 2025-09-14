<div class="form-check mb-3 <?= $groupClass ?? '' ?>">
    <input class="form-check-input" type="checkbox" name="<?= $name ?>" id="<?= "check_$name" ?>" <?= (isset($checked) and $checked) ? 'checked' : '' ?>>
    <label class="form-check-label" for="<?= "check_$name" ?>">
        <?= $label ?>
    </label>
</div>