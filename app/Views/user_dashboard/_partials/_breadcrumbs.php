<div>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= route('user.home') ?>">Dashboard</a></li>
        <?php if (isset($breadcrumb)): ?>
            <li class="breadcrumb-item active" aria-current="page">
                <?= $breadcrumb ?>
            </li>
        <?php endif; ?>
    </ol>
</div>