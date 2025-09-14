<div class="form-group mb-3 <?= $groupClass ?? '' ?>">
    <label class="form-label">
        <?= $label ?? '' ?>
    </label>

    <input class="form-control <?= $class ?? '' ?>" type="<?= $type ?? 'text' ?>" name="<?= $name ?>"
        placeholder="<?= $placeholder ?? '' ?>" <?= isset($value) ? "value=\"$value\"" : '' ?> <?= isset($id) ? "id=\"$id\"" : '' ?> <?= $attr ?? '' ?> <?= (isset($disabled) && $disabled) ? ' disabled ' : '' ?>
        <?= $bool_attributes ?? '' ?>>
    <div class="invalid-feedback">
    </div>

</div>