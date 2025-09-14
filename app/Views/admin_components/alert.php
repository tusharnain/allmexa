<?php

if (!isset($type)) {
    $type = 'primary';
}
if (!isset($noIcon)) {
    if ($type == 'success')
        $icon = 'mdi mdi-check-all';
    else if ($type == 'danger')
        $icon = 'mdi mdi-block-helper';
    else if ($type == 'warning')
        $icon = 'mdi mdi-alert-outline';
    else if ($type == 'info')
        $icon = 'mdi mdi-alert-circle-outline';
    else // else primary
        $icon = 'mdi mdi-bullseye-arrow';
}

?>


<div class="alert alert-<?= $type ?> alert-dismissible fade show mb-3" role="alert" <?= isset($id) ? "id=\"$id\"" : "" ?>>

    <span>
        <?php if (!isset($noIcon)): ?>
            <i class="<?= $icon ?> me-2"></i>
        <?php endif; ?>

        <?= $text ?? '' ?>
    </span>

    <?php if (isset($closeBtn)): ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    <?php endif; ?>
</div>