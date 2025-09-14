<?= $this->extend('user_dashboard/layout/master') ?>


<?= $this->section('style') ?>
<link rel="stylesheet" href="<?= base_url('twebsol/styles/binary-tree.css') ?>">
<?= $this->endSection() ?>


<?= $this->section('slot') ?>

<div class="card">
    <div class="card-header bg-primary text-white py-0 pb-2">

        <?= user_component('button', [
            'label' => 'Back',
            'icon' => 'fa-solid fa-circle-arrow-left',
            'color' => 'default',
            'id' => "go_back_user_btn",
            'onClick' => "goBackAUser();",
            'disabled' => true
        ]) ?>

    </div>
    <div class="card-body">

        <div id="btree-body">
            <div class="text-center mt-2">
                <h4>
                    <i class="fa-solid fa-spinner fa-spin me-2"></i>
                    Loading Tree...
                </h4>
            </div>
        </div>

    </div>


    <?= user_component('empty_modal', [
        'id' => 'binary_user_details_modal',
    ]) ?>


</div>



<?= $this->endSection() ?>



<?php $this->section('script') ?>
<script>

    var btreeApi = '<?= route('user.team.binaryTreePost'); ?>';
    const _traceback = ['<?= user('id') ?>'];

    const toggleBackBtn = (toggle = true) => {
        $('#go_back_user_btn').prop('disabled', _traceback.length <= 1 ? true : toggle);
    };


    $(document).ready(function () {
        getUserBinaryTree('<?= user('id') ?>');
    });


    function ajaxErrorHandler(xhr) {
        <?= !isProduction() ? 'console.log(xhr);' : '' ?>

        var res = xhr.responseJSON || xhr.responseText;

        if (xhr.status === 400 && res.errors) {

            if (res.errors.error) {
                sAlert('error', '', res.errors.error);
            }
        }
    }

    function goBackAUser() {
        if (_traceback.length > 1) {
            _traceback.pop();
            const user_id = _traceback[_traceback.length - 1];
            getUserBinaryTree(user_id, null);
        }

        if (_traceback.length <= 1)
            toggleBackBtn(true);

        console.log(_traceback);
    }

    function showBinaryUserDetails(userId) {
        userId = userId.trim();
        $.ajax({
            type: "POST",
            url: btreeApi,
            data: {
                action: 'get_user_binary_details',
                user_id: userId,
                ...csrf_data()
            },
            beforeSend: function () {

            },
            complete: function () {
                  
            },
            success: function (res, textStatus, xhr) {
                <?= !isProduction() ? 'console.log(res);' : '' ?>
                if (xhr.status === 200) {
                    if (res.view && $('#binary_user_details_modal_body').length > 0) {
                        $('#binary_user_details_modal_body').html(res.view);
                        showModal('#binary_user_details_modal');
                    }
                }
            },
            error: ajaxErrorHandler
        });
    }

    function getUserBinaryTree(userId, sr) {
        userId = userId.trim();
        $.ajax({
            type: "POST",
            url: btreeApi,
            data: {
                action: 'get_user_binary_tree_view',
                user_id: userId,
                ...csrf_data()
            },
            beforeSend: function () {

                toggleBackBtn(true);
            },
            complete: function () {
                  
                toggleBackBtn(false);
            },
            success: function (res, textStatus, xhr) {
                <?= !isProduction() ? 'console.log(res);' : '' ?>
                if (xhr.status === 200) {
                    if (res.view && $('#btree-body').length > 0) {
                        $('#btree-body').html(res.view);
                        if (sr && sr > 1) {
                            _traceback.push(userId);
                            toggleBackBtn(false);
                            console.log(_traceback);
                        }
                    }
                }
            },
            error: ajaxErrorHandler
        });
    }
</script>
<?php $this->endSection() ?>