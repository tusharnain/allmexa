<button type="<?= isset($submit) ? 'submit' : 'button' ?>" role="button"
    class="btn btn-<?= $color ?? 'primary' ?> mt-3 <?= $class ?? '' ?>" <?= isset($id) ? "id=\"$id\"" : "" ?>
    <?= isset($onClick) ? "onclick=\"$onClick\"" : "" ?> <?= (isset($disabled) && $disabled) ? ' disabled ' : '' ?>>

    <span>
        <?php if (isset($icon)): ?>

            <?= isset($iconLast) ? $label : '' ?>

            <i class="<?= $icon ?> <?= isset($iconLast) ? 'ms-1' : 'me-2' ?>"></i>

            <?= !isset($iconLast) ? $label : '' ?>

        <?php else: ?>

            <?= $label ?>

        <?php endif; ?>
    </span>

</button>