<button type="button" role="button" class="btn btn-<?= $color ?? 'primary' ?> <?= $class ?? '' ?>" <?= isset($onClick) ? "onclick=\"$onClick\"" : "" ?>>

    <i class="<?= $icon ?>"></i>

</button>