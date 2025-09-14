<?= $this->extend('user_dashboard/layout/master') ?>




<?= $this->section('slot') ?>

<div class="container-fluid">

    <?php if ($errors = session()->get('errors')): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $field => $error): ?>
                <p>
                    <strong><?= $field ?> : </strong>
                    <?= $error ?>
                </p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>


    <?php if ($kyc?->status === 'rejected'): ?>
        <div class="alert alert-danger">
            <strong>Kyc Rejected :</strong>
            <?= $kyc->reject_remark ?>
            <br>
            Please resubmit!
        </div>
    <?php endif; ?>



    <div class="card">
        <div class="card-header py-3">
            <h5>
                KYC Form
            </h5>
        </div>
        <div class="card-body pt-3">

            <?php if ($kyc?->status === 'pending'): ?>

                <div class="alert alert-warning">
                    Your KYC has been submitted and is awaiting approval.
                </div>

            <?php elseif ($kyc?->status === 'approved'): ?>

                <div class="alert alert-success">
                    Your KYC has been approved! No further actions required!
                </div>

                <div class="mt-4">
                    <h4>Uploaded Documents</h4>

                    <h5 class="mt-3">
                        <ul>
                            <li>
                                <a class="fs-4" href="<?= base_url('uploads/kyc/' . $kyc->aadhar) ?>" target="_blank">
                                    1. Aadhar
                                </a>
                            </li>
                            <li>
                                <a class="fs-4" href="<?= base_url('uploads/kyc/' . $kyc->pan) ?>" target="_blank">
                                    2. PAN
                                </a>
                            </li>
                        </ul>
                    </h5>
                </div>

            <?php else: ?>

                <form method="POST" action="<?= current_url() ?>" id="kyc_form" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="row">

                        <div class="col-lg-4">
                            <?= user_component('input', [
                                'type' => 'file',
                                'label' => 'Aadhar Front',
                                'name' => 'aadhar',
                                'bool_attributes' => 'required'
                            ]) ?>
                        </div>
                        
                        <div class="col-lg-4">
                            <?= user_component('input', [
                                'type' => 'file',
                                'label' => 'Aadhar Back',
                                'name' => 'aadhar_back',
                                'bool_attributes' => 'required'
                            ]) ?>
                        </div>

                        <div class="col-lg-4">
                            <?= user_component('input', [
                                'type' => 'file',
                                'label' => 'PAN',
                                'name' => 'pan',
                                'bool_attributes' => 'required'
                            ]) ?>
                        </div>

                        <div class="text-end">
                            <?= user_component('button', [
                                'label' => 'Submit',
                                'class' => 'btn-lg mobile-button',
                                'icon' => 'fa-solid fa-check',
                                'id' => 'kyc_submit_btn',
                                'submit' => true
                            ]) ?>
                        </div>

                    </div>
                </form>

            <?php endif; ?>


        </div>
    </div>


</div>


<?= $this->endSection() ?>