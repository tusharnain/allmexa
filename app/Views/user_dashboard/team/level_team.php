<?= $this->extend('user_dashboard/layout/master') ?>


<?= $this->section('slot') ?>
<div class="container-fluid">
    <?php if ($hasDirectUser) : ?>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Level Team Details</h5>
                    </div>
                    <div class="card-body" id="lt-container">
                        <h6 class="text-center" id="lt-loader">
                            Loading Table <i class="fa-solid fa-spinner fa-spin ms-1"></i>
                        </h6>
                    </div>
                </div>
            </div>
        </div>
    <?php else : ?>
        <?= view('user_dashboard/team/_no_referrals_alert') ?>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>

<?php if ($hasDirectUser) : ?>
    <?php $this->section('script') ?>
    <script>
        $(document).ready(function() {
            $.post("<?= current_url() ?>", {
                ...csrf_data()
            }, function(res) {
                if (res.html) {
                    $('#lt-loader').fadeOut('fast', function() {
                        $('#lt-container').hide().html(res.html).slideDown('fast');
                    });
                }
            });
        });
    </script>
    <?php $this->endSection() ?>
<?php endif; ?>