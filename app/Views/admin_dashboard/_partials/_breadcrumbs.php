<div class="page-title-right">
    <ol class="breadcrumb m-0">
        <li class="breadcrumb-item"><a href="<?= route('admin.home') ?>">Dashboard</a></li>
        <?php if (isset($breadcrumb)): ?>
            <li class="breadcrumb-item active">
                <?= $breadcrumb ?>
            </li>
        <?php endif; ?>
    </ol>
</div>