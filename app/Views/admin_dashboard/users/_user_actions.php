<div>
    <div class="card p-3">
        <div class="d-flex align-items-center">
            <div class="image">
                <img src="<?= $profile_picture ?>" class="rounded profile_picture_img" width="155">
            </div>

            <div class="ms-3 w-100">

                <h4 class="mb-0 mt-0">
                    <?= $user->full_name ?>
                </h4>
                <span>
                    <?= $user->user_id ?>
                </span>

                <div class="mt-2">


                    <?= admin_component('button', [
                        'label' => 'Topup',
                        'class' => 'btn-sm mb-0 btn-info'
                    ]) ?>



                    <?= admin_component('button', [
                        'label' => 'Login',
                        'icon' => 'mdi mdi-login',
                        'class' => 'btn-sm',
                        'onClick' => "$('#login_user_account').submit();"
                    ]) ?>


                </div>
            </div>
        </div>


        <div class="accordion my-3" id="sponsorAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingSponsor">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSponsor" aria-expanded="false" aria-controls="collapseSponsor">
                        Other Details
                    </button>
                </h2>
                <div id="collapseSponsor" class="accordion-collapse collapse" aria-labelledby="headingSponsor" data-bs-parent="#sponsorAccordion">
                    <div class="accordion-body p-0">
                        <table class="table table-bordered mb-0">
                            <tbody>
                                <?php if ($sponsor): ?>
                                    <tr>
                                        <td><?= label('sponsor_id') ?></td>
                                        <td class="text-end fw-bold">
                                            <a href="<?= route('admin.users.user', $sponsor->user_id) ?>">
                                                <?= $sponsor->user_id ?? '' ?>
                                                <i class="mdi mdi-link-variant"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?= label('sponsor_name') ?></td>
                                        <td class="text-end fw-bold">
                                            <?= $sponsor->full_name ?? '' ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>



        <?= admin_component('button', [
            'label' => 'Login Account',
            'icon' => 'mdi mdi-login',
            'class' => 'w-100 btn-danger btn-lg',
            'onClick' => "$('#login_user_account').submit();"
        ]) ?>

    </div>


    <form id="login_user_account" style="display:hidden" method="POST"
        action="<?= route('admin.users.user', $user->user_id) ?>" target="_blank">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="login">
    </form>
</div>