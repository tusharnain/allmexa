<?php

$userIdLabel = label('user_id');
$userNameLabel = label('user_name');


$hasRecords = (isset($records) and is_array($records) and count($records) > 0);


$detailUrl = route('admin.users.kycDetail', '_1');


?>


<?= $this->extend('admin_dashboard/_partials/app') ?>


<?php $this->section('style') ?>
<style>
    .user-avatar {
        object-fit: cover;
        height: 2.4rem;
        width: 2.4rem;
    }

    #adv_filter_loader {
        display: none;
    }
</style>
<?php $this->endSection() ?>


<?= $this->section('slot') ?>


<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card rounded-3">
                <div class="card-body">

                    <form method="GET">
                        <div class="row">

                            <div class="col-md-4">
                                <?= admin_component('input', [
                                    'name' => 'search',
                                    'label' => 'Search',
                                    'placeholder' => "$userIdLabel / $userNameLabel / Email / Phone",
                                    'value' => $search ?? ''
                                ]) ?>
                            </div>

                            <div class="col-md-2">
                                <?= admin_component('select', [
                                    'name' => 'status',
                                    'label' => 'Status',
                                    'options' => [
                                        'All' => 'all',
                                        'Pending' => 'pending',
                                        'Rejected' => 'rejected',
                                        'Approved' => 'approved',
                                    ],
                                    'select' => $status
                                ]) ?>
                            </div>

                            <div class="col-md-2">
                                <?= admin_component('page_length_select', [
                                    'lengths' => $pageLengths ?? [15, 50, 100, 200],
                                    'current_page_length' => $pageLength
                                ]) ?>
                            </div>

                            <div class="col-md-4 pt-md-1 pt-0">
                                <?= admin_component('button', [
                                    'label' => 'Go',
                                    'class' => 'mt-md-4 mt-0',
                                    'submit' => true
                                ]) ?>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>



        <div class="col-12">
            <div class="p-0">
                <div class="table-responsive rounded-3 shadow p-0">
                    <table class="table table-bordered table-hover text-nowrap m-0">
                        <thead>
                            <tr class="table-dark">
                                <th>
                                    #
                                </th>
                                <th>Action</th>
                                <th>
                                    <?= $userIdLabel ?>
                                </th>
                                <th>
                                    <?= $userNameLabel ?>
                                </th>
                                <th>Submit Date</th>
                            </tr>
                        </thead>


                        <tbody id="user_table_body">


                            <?php

                            $i = pager_init_serial_number($pager);


                            if ($hasRecords): ?>
                                <?php foreach ($records as &$record):

                                    $color = match ($record->status) {
                                        'pending' => 'warning',
                                        'rejected' => 'danger',
                                        'approved' => 'success'
                                    };

                                    ?>
                                    <tr class="table-<?= $color ?>">
                                        <td>
                                            <?= ++$i; ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= str_replace('_1', $record->id, $detailUrl) ?>"><button
                                                    class="btn btn-<?= $color ?> btn-sm">
                                                    <i class="mdi mdi-monitor-eye"></i>
                                                </button>
                                            </a>
                                        </td>
                                        <td class="fw-bold">
                                            <?= $record->user_user_id ?>
                                        </td>
                                        <td>
                                            <?= $record->user_full_name ?>
                                        </td>
                                        <td>
                                            <?= $record->created_at ? f_date($record->created_at) : '' ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr class="table-secondary">
                                    <td class="text-center" colspan="20">0 Records Found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if ($hasRecords): ?>
                    <div class="table-pagination float-end mt-3">
                        <?= $pager->links(template: 'admin_dashboard') ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>