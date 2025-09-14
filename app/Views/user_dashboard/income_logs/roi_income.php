<?php

$planLabel = label('plan');
?>


<?= $this->extend('user_dashboard/layout/master') ?>

<?= $this->section('slot') ?>

<div class="container-fluid">
    <div class="row">
        <?php if (count($rois) > 0): ?>
            <?php foreach ($rois as &$roi):

                $color = $roi->status === 'active' ? 'success' : 'danger';

                ?>
                <div class="col-md-6 col-lg-6 col-xl-4 col-xxl-3">
                    <div class="card">
                        <div class="card-header py-3 d-flex justify-content-between">
                            <div>
                                <h5>#<?= $roi->track_id ?></h5>
                                <?= $roi->salary_index ? 'Salary' : f_amount(_c($roi->topup_amount), isUser: true) ?>
                                </h6>
                            </div>

                            <h5><i class="fa-solid fa-circle mt-1 text-<?= $color ?>"></i></h5>
                        </div>
                        <div class="card-body">
                            <table class="table border-">
                                <tbody>
                                    <tr>
                                        <td>ROI Amount</td>
                                        <td class="text-end">
                                            <span class="fw-bold"><?= f_amount(_c($roi->roi_amount), isUser: true) ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>ROI Type</td>
                                        <td class="text-end">
                                            <span class="fw-bold text-uppercase"><?= $roi->roi_type ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>ROI Status</td>
                                        <td class="text-end">
                                            <span class="fw-bold text-uppercase text-<?= $color ?>"><?= $roi->status ?></span>
                                        </td>
                                    </tr>
                                    <tr></tr>
                                </tbody>
                            </table>

                            <a href="?roi=<?= $roi->track_id ?>">
                                <button class="btn btn-primary mt-4 float-end">
                                    Income Logs
                                    <i class="fa-solid fa-arrow-right ms-2"></i>
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <?= user_component('alert', [
                'icon' => 'fa-solid fa-exclamation-circle',
                'type' => 'warning',
                'text' => "You have not subscribed to any ROI $planLabel."
            ]) ?>
        <?php endif; ?>
    </div>
</div>


<?= $this->endSection() ?>