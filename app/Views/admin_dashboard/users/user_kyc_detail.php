<?= $this->extend('admin_dashboard/_partials/app') ?>

<?= $this->section('slot') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <?php if ($kyc->status === 'pending'): ?>
                <div class="alert alert-warning">KYC is pending for approval.</div>
            <?php elseif ($kyc->status === 'rejected'): ?>
                <div class="alert alert-danger">KYC is currently rejected and waiting for resubmission.</div>
            <?php else: ?>
                <div class="alert alert-success">KYC is approved and no action needed.</div>
            <?php endif; ?>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered mt-3">
                        <tbody>
                            <tr>
                                <td><?= label('user_id') ?></td>
                                <td class="text-end fw-bold">
                                    <a href="<?= route('admin.users.user', $user->user_id) ?>">
                                        <?= $user->user_id ?? '' ?>
                                        <i class="mdi mdi-link-variant"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td><?= label('user_name') ?></td>
                                <td class="text-end fw-bold">
                                    <a href="<?= route('admin.users.user', $user->user_id) ?>">
                                        <?= $user->full_name ?? '' ?>
                                        <i class="mdi mdi-link-variant"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h4>Uploaded Documents</h4>
                    <div class="row mt-3">
                        <?php
                        $aadharFile = base_url('uploads/kyc/' . $kyc->aadhar);
                        $aadharBackFile = base_url('uploads/kyc/' . $kyc->aadhar_back);
                        $panFile = base_url('uploads/kyc/' . $kyc->pan);
                        ?>
                        <div class="col-md-6 col-lg-4 text-center">
                            <h5>Aadhar Front</h5>
                            <?php if (pathinfo($kyc->aadhar, PATHINFO_EXTENSION) === 'pdf'): ?>
                                <a href="<?= $aadharFile ?>" target="_blank" class="btn btn-primary">View Aadhar Front
                                    PDF</a>
                            <?php else: ?>
                                <a href="<?= $aadharFile ?>" target="_blank">
                                    <img src="<?= $aadharFile ?>" alt="Aadhar Front Image" class="img-thumbnail"
                                        style="max-width: 150px; height: auto;">
                                </a>
                            <?php endif; ?>
                        </div>
                        <?php if ($kyc->aadhar_back): ?>
                            <div class="col-md-6 col-lg-4 text-center">
                                <h5>Aadhar Back</h5>
                                <?php if (pathinfo($kyc->aadhar_back, PATHINFO_EXTENSION) === 'pdf'): ?>
                                    <a href="<?= $aadharBackFile ?>" target="_blank" class="btn btn-primary">View Aadhar Back
                                        PDF</a>
                                <?php else: ?>
                                    <a href="<?= $aadharBackFile ?>" target="_blank">
                                        <img src="<?= $aadharBackFile ?>" alt="Aadhar Back Image" class="img-thumbnail"
                                            style="max-width: 150px; height: auto;">
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <div class="col-md-6 col-lg-4 text-center">
                            <h5>PAN</h5>
                            <?php if (pathinfo($kyc->pan, PATHINFO_EXTENSION) === 'pdf'): ?>
                                <a href="<?= $panFile ?>" target="_blank" class="btn btn-primary">View PAN PDF</a>
                            <?php else: ?>
                                <a href="<?= $panFile ?>" target="_blank">
                                    <img src="<?= $panFile ?>" alt="PAN Image" class="img-thumbnail"
                                        style="max-width: 150px; height: auto;">
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="text-end action-btns mt-3">
                        <button class="btn btn-danger rej-btn">Reject</button>
                        <button class="btn btn-success approve-btn">Approve</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        $('.rej-btn').on('click', function () {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to reject this KYC request.',
                input: 'text',
                inputPlaceholder: 'Enter rejection remarks...',
                showCancelButton: true,
                confirmButtonText: 'Reject',
                cancelButtonText: 'Cancel',
                icon: 'warning',
                inputValidator: function (value) {
                    if (!value) {
                        return 'You need to provide a remark!';
                    }
                }
            }).then(function (result) {
                if (result.isConfirmed) {
                    const remarks = result.value;
                    $.ajax({
                        url: '<?= current_url() ?>',
                        method: 'POST',
                        data: { remarks: remarks, status: 'rejected', '<?= csrf_token() ?>': '<?= csrf_hash() ?>' },
                        success: function (data) {
                            if (data.success) {
                                Swal.fire('Rejected!', 'The KYC request has been rejected.', 'success').then(function () { location.reload(); });
                            } else {
                                Swal.fire('Error!', 'Failed to reject the KYC request.', 'error');
                            }
                        },
                        error: function () { Swal.fire('Error!', 'An error occurred.', 'error'); }
                    });
                }
            });
        });

        $('.approve-btn').on('click', function () {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to approve this KYC request.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Approve',
                cancelButtonText: 'Cancel'
            }).then(function (result) {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= current_url() ?>',
                        method: 'POST',
                        data: { status: 'approved', '<?= csrf_token() ?>': '<?= csrf_hash() ?>' },
                        success: function (data) {
                            if (data.success) {
                                Swal.fire('Approved!', 'The KYC request has been approved.', 'success').then(function () { location.reload(); });
                            } else {
                                Swal.fire('Error!', 'Failed to approve the KYC request.', 'error');
                            }
                        },
                        error: function () { Swal.fire('Error!', 'An error occurred.', 'error'); }
                    });
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>