<div class="modal fade" id="<?= $id ?>" <?= isset($static) ? 'data-bs-backdrop="static" data-bs-keyboard="false"' : '' ?>>
    <div class="modal-dialog modal-<?= $size ?? 'md' ?> <?= isset($center) ? 'modal-dialog-centered' : '' ?>"
        role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="<?= $id ?>_title">
                    <?= $title ?? '' ?>
                </h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body" id="<?= $id ?>_body"></div>
            <?php if (!isset($noCloseButton)): ?>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="button" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>