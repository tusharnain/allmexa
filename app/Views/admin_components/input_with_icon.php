<div class="form-group mb-3">
    <label class="form-label">
        <?= $label ?? '' ?>
    </label>
    <div class="input-group" <?= isset($groupId) ? "id=\"$groupId\"" : '' ?>>
        <a class="input-group-text bg-white text-muted">
            <i class="<?= $icon ?>"></i>
        </a>
        <input class="form-control <?= $class ?? '' ?>" type="<?= $type ?? 'text' ?>" name="<?= $name ?>"
            placeholder="<?= $placeholder ?? '' ?>" <?= isset($value) ? "value=\"$value\"" : '' ?> <?= isset($id) ? "id=\"$id\"" : '' ?> <?= $bool_attributes ?? '' ?>>
        <div class="invalid-feedback">
        </div>
    </div>
</div>