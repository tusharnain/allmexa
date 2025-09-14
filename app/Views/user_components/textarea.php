<div class="form-group mb-3 <?= $groupClass ?? '' ?>">

    <?php if (isset ($label)): ?>
        <label class=" form-label">
            <?= $label ?>
        </label>
    <?php endif; ?>


    <textarea class="form-control <?= $class ?? '' ?>" name="<?= $name ?>" rows="<?= $rows ?? '5' ?>"
        placeholder="<?= $placeholder ?? '' ?>" <?= isset ($id) ? "id=\"$id\"" : '' ?> <?= $bool_attributes ?? '' ?>><?= $value ?? '' ?></textarea>

    <div class="invalid-feedback">
    </div>

</div>