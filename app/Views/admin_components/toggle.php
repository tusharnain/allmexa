<div class="form-check form-switch mb-3" dir="ltr">
    <input type="checkbox" class="form-check-input" role="button" name="<?= $name ?>" id="<?= $name ?>_toggle"
        <?= (isset($checked) and $checked) ? ' checked' : '' ?>>
    <label class="form-check-label" for="<?= $name ?>_toggle">
        <?= $label ?? '' ?>
    </label>
</div>