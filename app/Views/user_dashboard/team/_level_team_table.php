<?php

$usersLabel = label('users');

?>

<?php if (count($levels) > 0): ?>
    <div class="table-responsive">
        <table class="table table-bordered text-nowrap">
            <thead>
                <tr>
                    <th>
                        <i class="fa-solid fa-chart-simple"></i>
                        Level
                    </th>
                    <th>
                        <i class="fa-solid fa-user"></i>
                        Total
                        <?= $usersLabel ?>
                    </th>
                    <th>
                        <i class="fa-solid fa-user-check"></i>
                        Active
                        <?= $usersLabel ?>
                    </th>
                    <th>
                        <i class="fa-solid fa-user-times"></i>
                        Inactive
                        <?= $usersLabel ?>
                    </th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($levels as &$level): ?>
                    <tr>
                        <td>
                            Level
                            <?= $level->level ?>
                        </td>
                        <td>
                            <?= $level->totalUsers ?>
                        </td>
                        <td>
                            <?= $level->totalActiveUsers ?>
                        </td>
                        <td>
                            <?= $level->totalInactiveUsers ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr class="fw-bold text-ld border border-primary">
                    <td class="bg-light-primary text-ld">
                        Total
                    </td>
                    <td>
                        <?= $allLevels->totalUsers ?>
                    </td>
                    <td>
                        <?= $allLevels->totalActiveUsers ?>
                    </td>
                    <td>
                        <?= $allLevels->totalInactiveUsers ?>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
<?php else: ?>
    <h6 class="text-center">0 Referrals Made</h6>
<?php endif; ?>