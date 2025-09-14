<button type="<?= isset($submit) ? 'submit' : 'button' ?>"
    class="btn btn-<?= $color ?? 'primary' ?> mb-3 waves-effect waves-light <?= $class ?? '' ?>" <?= isset($id) ? "id=\"$id\"" : "" ?> <?= isset($onClick) ? "onclick=\"$onClick\"" : "" ?>>

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