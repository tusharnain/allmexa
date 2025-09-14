<?php

use CodeIgniter\Pager\PagerRenderer;

/**
 * @var PagerRenderer $pager
 */
$pager->setSurroundCount(2);
?>

<nav aria-label="Page Navigation">
    <ul class="pagination pagination-primary">
        <li class="page-item <?= $pager->hasPreviousPage() ? '' : 'disabled' ?>">
            <a href="<?= $pager->getFirst() ?>" class="page-link">
                &laquo;
            </a>
        </li>
        <li class="page-item <?= $pager->hasPreviousPage() ? '' : 'disabled' ?>">
            <a href="<?= $pager->getPreviousPage() ?>" class="page-link">
                &lsaquo;
            </a>
        </li>
        <?php foreach ($pager->links() as $link): ?>
            <li class="page-item  <?= $link['active'] ? 'active' : '' ?>">
                <a href="<?= $link['uri'] ?>" class="page-link">
                    <?= $link['title'] ?>
                </a>
            </li>
        <?php endforeach ?>
        <li class="page-item <?= $pager->hasNextPage() ? '' : 'disabled' ?>">
            <a href="<?= $pager->getNextPage() ?>" class="page-link">
                &rsaquo;
            </a>
        </li>
        <li class="page-item <?= $pager->hasNextPage() ? '' : 'disabled' ?>">
            <a href="<?= $pager->getLast() ?>" class="page-link">
                &raquo;
            </a>
        </li>
    </ul>
</nav>