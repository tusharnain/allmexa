<div class="form-group mb-3 <?= $groupClass ?? '' ?>">
    <?php if (isset($label)): ?>
        <label class="form-label">
            <?= $label ?>
        </label>
    <?php endif; ?>

    <div class="input-group" <?= isset($groupId) ? "id=\"$groupId\"" : '' ?>>

        <?php if (isset($icon)): ?>
            <a class="input-group-text bg-primary text-white">
                <i class="<?= $icon ?>"></i>
            </a>
        <?php endif; ?>

        <input class="form-control <?= $class ?? '' ?>" type="<?= $type ?? 'text' ?>" name="<?= $name ?>" <?= isset($id) ? " id=\"$id\" " : '' ?> placeholder="<?= $placeholder ?? '' ?>" <?= isset($value) ? "value=\"$value\"" : '' ?>
        <?= (isset($disabled) && $disabled) ? ' disabled ' : '' ?> <?= $bool_attributes ?? '' ?> <?= isset($required) && $required ? 'required' : '' ?>>
        <div class="invalid-feedback">
        </div>
    </div>
</div>