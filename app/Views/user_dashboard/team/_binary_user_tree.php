<div>
    <?php
    $wd = 1;
    $i = 1;
    foreach ($tree as &$level):
        ?>

        <div class="row text-center justify-content-center bt-llll">
            <?php

            foreach ($level as &$user):

                $statusClass = $user ? ($user->status ? 'bt-paid-user' : 'bt-free-user') : 'no-user';
                $image = $user ? \App\Models\UserModel::getAvatar($user) : base_url('images/empty-user.jpg');

                ?>

                <div class="bt-w-<?= $wd ?>">
                    <div class="bt-user">

                        <div role="button" <?= $user ? "onclick=\"showBinaryUserDetails('$user->id');\"" : '' ?>>
                            <img src="<?= $image ?>" alt="user-avatar" class="<?= $statusClass ?>">
                        </div>

                        <?php if ($user): ?>

                            <p class="bt-user-id mb-0 single-line-ellipsis text-primary" role="button"
                                onclick="getUserBinaryTree('<?= $user->id ?>', <?= $i++; ?>)">
                                <?= $user ? $user->user_id : 'Empty' ?>
                            </p>

                            <p class="bt-user-name <?= $wd > 2 ? 'single-line-ellipsis' : 'double-line-ellipsis' ?>">
                                <?= $user->full_name ?>
                            </p>

                        <?php else: ?>
                            <p class="bt-user-id mb-0">Empty</p>
                        <?php endif; ?>

                    </div>
                    <span class="bt-line"></span>
                </div>

            <?php endforeach; ?>
        </div>

        <?php
        $wd *= 2;
    endforeach;
    ?>
</div>