<div class="alert alert-<?= $type ?? 'primary' ?> <?= isset($dark) ? 'dark' : '' ?> alert-dismissible fade show mb-3"
    role="alert" <?= isset($id) ? "id=\"$id\"" : "" ?> <?= (isset($hidden) and $hidden) ? ' style="display:none;"' : '' ?>>

    <?php if (isset($icon)): ?>
        <i class="<?= $icon ?> me-1"></i>
    <?php endif; ?>

    <span>
        <?= $text ?? '' ?>
    </span>

    <?php if (isset($closeBtn)): ?>
        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
    <?php endif; ?>

</div>