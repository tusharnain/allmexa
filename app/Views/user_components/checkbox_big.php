<div class="form-group mb-3 <?= $groupClass ?? '' ?>">
    <label class="custom-control custom-checkbox-md">
        <input type="checkbox" class="custom-control-input" name="<?= $name ?>">
        <span class="custom-control-label h5">
            <?= $label ?? '' ?>
        </span>
        <div class="invalid-feedback"></div>
    </label>
</div>