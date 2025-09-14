<div class="form-group mb-3 <?= $groupClass ?? '' ?>">

    <div class="checkbox p-0">
        <input id="chk_<?= $name ?>" type="checkbox" name="<?= $name ?>">
        <label class="text-muted" for="chk_<?= $name ?>">
            <?= $label ?? '' ?>
        </label>
        <div class="invalid-feedback"></div>
    </div>

</div>