<style>
    .btn-custom {
        font-family: Roboto, sans-serif;
        font-weight: 400;
        font-size: 18px;
        color: #374e94;
        background-color: #ffffff00;
        padding: 5px 20px;
        border: solid #374e94 2px;
        box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;
        border-radius: 9px;
        transition: 146ms;
        transform: translateY(0);
        display: flex;
        flex-direction: row;
        align-items: center;
        cursor: pointer;
        text-transform: uppercase;

    }

    .btn-custom:hover {
        transition: 146ms;
        transform: translateY(-0px);
        background-color: #374e94;
        color: #ffffff;
        border: solid 2px #374e94;

    }
</style>
<!-- ====================================
        ——— CONTENT WRAPPER
        ===================================== -->
<div class="content-wrapper">
    <div class="content pt-5"><!-- For Components documentaion -->



        <div class="card mb-3">
            <div class="row no-gutters">


                <div class="card-header w-100" style="background-color : #374e94;">
                    <h2 class="text-white">Welcome Letter</h2>

                </div>

                <div class="card-body pt-4">

                    <div class="text-center pb-5">
                        <img width="250" src="<?= public_path('logo.png') ?>"></img>
                    </div>

                    <div class="pt-5">

                        <h5>Dear <strong>
                                <?= $user['name'] ?>
                            </strong></h5>

                        <h4 class="mt-2"><strong>Welcome to
                                <?= $_s['app_title'] ?>
                            </strong></h4>


                        <div class="mt-2">

                            <h5 class="pt-5 mb-3"
                                style="display : inline-block; padding-bottom : 5px; border-bottom : 2px solid black;">
                                Your
                                details are given below :
                            </h5>


                            <h5>User Id : <strong>
                                    <?= $user['user_id'] ?>
                                </strong></h5>

                            <h5 class="mt-1">Name : <strong>
                                    <?= $user['name'] ?>
                                </strong></h5>

                            <h5 class="mt-1">Date of Joining : <strong>
                                    <?= fdate($user['created_at']) ?>
                                </strong></h5>

                        </div>

                        <div class="mt-2">

                            <h5 class="pt-5 mb-3"
                                style="display : inline-block; padding-bottom : 5px; border-bottom : 2px solid black;">
                                Your Sponser's
                                details are here :
                            </h5>


                            <!--<h5>Sponser's User Id : <strong>-->
                            <!--        <?= $user['sponser_id'] ?>-->
                            <!--    </strong></h5>-->

                            <!--<h5 class="mt-1">Sponser's Name : <strong>-->
                            <!--        <?= $sName ?>-->
                            <!--    </strong></h5>-->


                        </div>


                        <p class="mt-5 pt-5 pb-5 text-dark h5" style="line-height : 1.7;">
                            We extend our heartful gratitude to you for joining
                            <?= $_s['app_title'] ?> and value your association with
                            us. We assure you that joining with us shall lead u to the path of success and prosperity
                            fulfilling your hidden needs and dreams.
                            <?= $_s['app_title'] ?> is the concept where everyone feels
                            responsible for sharing the benefit of this set up. With just five (or many more) joining,
                            one
                            starts sharing the benefits.Your recommendation to your near and dear one will open the gate
                            of infinite earning to all.
                        </p>

                        <h5 class="mt-5">We would once again congratulate you on your brilliant choice of joining
                            <?= $_s['app_title'] ?>.
                        </h5>

                    </div>




                </div>

                <div class="card-header w-100">
                    <button class="btn-custom float-right" onclick="callPrint()">Print</button>
                </div>

            </div>
        </div>

        <script>
            function callPrint() {
                w = window.open("<?= user_url('account/welcome-letter?query=print&status=ok&token=' . md5(mt_rand())) ?>", "", "location=0,status=1,scrollbars=1,width=800,height=750");
                w.moveTo(0, 0);
            }
        </script>
    </div>

</div>