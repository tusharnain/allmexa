<?php

$userIdLabel = label('user_id');
$userLabel = label('user');
$sponsorLabel = label('sponsor');

?>

<?= $this->extend('user_dashboard/layout/master') ?>

<?= $this->section('style'); ?>
<link rel="stylesheet" href="<?= user_asset('css/vendors/datatables.css') ?>">
<style>
    .dataTables_filter {
        width: 50%;
        float: right;
        text-align: right;
    }
    /* Table base with border */
/* Table base */
#lv-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.95rem;
    background-color: #1a1a1a; /* dark background */
    color: #ffffff; /* white text */
}

/* Table header */
#lv-table th {
    background-color: #1a1a1a; /* dark header */
    color: #ffffff;
    font-weight: 600;
    text-align: center;
    padding: 0.75rem;
    border: 1px solid #444444; /* subtle grey border */
}

/* Table body cells */
#lv-table td {
    text-align: center;
    vertical-align: middle;
    padding: 0.65rem;
    border: 1px solid #444444; /* subtle grey border */
}

/* Striped rows (optional) */
#lv-table tbody tr:nth-child(odd) {
    background-color: #262626; /* slightly lighter dark */
}

/* Hover effect */
#lv-table tbody tr:hover {
    background-color: #333333; /* dark hover */
}

@media (max-width: 768px) {
    .dataTables_filter {
        width: 100% !important;
        float: none !important;
        text-align: left !important;
        margin: 0 0 1rem 0; /* bottom gap */
    }

    /* Optional: make search input full width */
    .dataTables_filter input {
        width: 100%;
        box-sizing: border-box;
    }
}
</style>
<?= $this->endSection(); ?>


<?= $this->section('slot') ?>
<div class="container-fluid">

    <?php if ($hasDirectUser): ?>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-5 col-md-6 col-12">
                                <div id="level-select-container">
                                    <h4 class="loading-area"></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card" id="level-card" style="display: none;">
                    <div class="card-header">
                        <h5 id="level_detail"></h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive text-nowrap">
                            <table class="display table-bordered" id="lv-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><?= $userIdLabel ?></th>
                                        <th>Full Name</th>
                                        <th><?= $sponsorLabel ?></th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Total Investment</th>
                                        <th>Joining Date</th>
                                        <th>Activation Date</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <?= view('user_dashboard/team/_no_referrals_alert') ?>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>

<?php if ($hasDirectUser): ?>
    <?php $this->section('script') ?>
    <script src="<?= user_asset('js/datatable/datatables/jquery.dataTables.min.js') ?>"></script>
    <script>
        const apiUrl = "<?= current_url() ?>";

        var onSelectChange = null;

        $(document).ready(function () {

            showTextLoader();

            onSelectChange = Dashboard.setupLevelDownline({
                api: apiUrl,
                userLabel: "<?= label('user') ?>",
                usersLabel: "<?= label('users') ?>",
                activeStatusLabel: "<?= label('user_status_active') ?>",
                inactiveStatusLabel: "<?= label('user_status_inactive') ?>",
                isProduction: <?= isProduction() ? 'true' : 'false' ?>,
            });
        });


        function updateLevel(e) {
            e.preventDefault();
            const level = e.target.value;
            onSelectChange && onSelectChange(level);
        }
    </script>
    <?php $this->endSection() ?>
<?php endif; ?>