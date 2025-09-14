<?= $this->extend('admin_dashboard/_partials/app') ?>

<?= $this->section('style') ?>

<style>
    #sid_alert {
        display: none;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('slot') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">User Rewards</h3>
                </div>
                <div class="card-body">
                    <table id="rewardTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                        <thead class="table-dark">
                            <tr>
                                <th>User ID</th>
                                <th>User Name</th>
                                <th>Reward Name</th>
                                <th>Date/Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($userRewards as $reward): ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($reward->user_user_id)) : ?>
                                            <a href="<?= route('admin.users.user', $reward->user_user_id) ?>"
                                               target="_blank" class="text-decoration-none">
                                                <?= $reward->user_user_id; ?>
                                            </a>
                                        <?php else : ?>
                                            N/A
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $reward->user_full_name ?? 'Unknown'; ?></td>
                                    <td><?= $reward->reward_name; ?></td>
                                    <td data-order="<?= strtotime($reward->created_at); ?>">
                                        <?= $reward->formatted_created_at; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
$(document).ready(function() {
    $('#rewardTable').DataTable({
        "responsive": true,
        "autoWidth": false,
        "pageLength": 10,
        "order": [[3, 'desc']], // Default sort by ID column
        "language": {
            "emptyTable": "No rewards available"
        },
        "lengthMenu": [5, 10, 25, 50, 100], // Page length options
        "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' + 
               '<"row"<"col-sm-12"tr>>' +
               '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
    });
});
</script>

<?= $this->endSection('script') ?>
