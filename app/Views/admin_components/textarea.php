<div class="form-group mb-3 <?= $groupClass ?? '' ?>"">
    <label class=" form-label">
    <?= $label ?>
    </label>
    <textarea class="form-control <?= $class ?? '' ?>" name="<?= $name ?>" rows="5"
        placeholder="<?= $placeholder ?? '' ?>" <?= isset($id) ? "id=\"$id\"" : '' ?> <?= $bool_attributes ?? '' ?>><?= $value ?? '' ?></textarea>
    <div class="invalid-feedback">
    </div>
</div>