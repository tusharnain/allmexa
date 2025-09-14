<div class="row mb-3 <?= $groupClass ?? '' ?>">
    <label for="example-text-input" class="col-sm-2 col-form-label">
        <?= $label ?>
    </label>
    <div class="col-sm-10">
        <input class="form-control <?= $class ?? '' ?>" type="<?= $type ?? 'text' ?>" name="<?= $name ?>"
            placeholder="<?= $placeholder ?? '' ?>" <?= isset($value) ? "value=\"$value\"" : '' ?> <?= isset($id) ? "id=\"$id\"" : '' ?> <?= $bool_attributes ?? '' ?>>
        <div class="invalid-feedback">
        </div>
    </div>
</div>