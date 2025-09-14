<?php
$serverTime = date("F d, Y H:i:s");
?>

<?php
$userName = user('full_name');
$referLink = route('referral', user('user_id'));
$currentHour = date('G');
$dashboardGreet = false;
$userAssetDir = user_asset();

if (!($dashgrt = prefixed_cookie('dashgrt')) or !$dashgrt) {
    $dashboardGreet = true;
    load_helper_if_not_function('cookie', 'set_cookie');
    set_cookie('dashgrt', '1');
}

?>

<?= $this->extend('user_dashboard/layout/master') ?>

<?php $this->section('style') ?>
<style>
    .widget-1-i {
        font-size: 24px !important;
    }

    .ref_inp_group {
        width: 92%;
    }

    img.user_pfp {
        width: 45px;
        height: 45px;
        object-fit: cover;
    }

    img.profile-card-img {
        padding: 0 !important;
        width: 90px;
        height: 90px !important;
        object-fit: cover;
    }


    /* Gold Gradient Background */
    .shining-card {
        background: linear-gradient(90deg, #d4af37 0%, #f5d76e 50%, #d4af37 100%);
        color: #000;
        /* Black text for contrast */
        border-radius: 12px;
        box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.3);
        border: none;
    }

    /* Optional: Add same background to all widget cards */
    .card {
        background: linear-gradient(90deg, #d4af37 0%, #f5d76e 50%, #d4af37 100%);
        color: #000;
    }

    /* Optional: Make icons/text stand out */
    .card h4,
    .card span,
    .card i {
        color: #000;
    }
</style>
<?php $this->endSection() ?>


<?= $this->section('slot') ?>




<!-- Container-fluid starts-->
<div class="container-fluid">

    <div class="col-12">
        <div class="card">
            <div class="card-body" id="income_progress">
                <h6 class="text-center" id="ip-loader">
                    Loading Data <i class="fa-solid fa-spinner fa-spin ms-1"></i>
                </h6>
            </div>
        </div>
    </div>



    <div class="col-12" id="refer_link_stack"></div>

    <div class="col-12">
        <div class="row  px-0" id="widgets_stack"></div>
    </div>

    <div class="col-12" id="earning_chart_stack"></div>

    <div class="row">
        <div class="col-md-6">
            <?= view('user_dashboard/home/daily_deposit_bonanza.php') ?>
        </div>

        <div class="col-md-6">
            <?= view('user_dashboard/home/booster_club_income.php') ?>
        </div>

    </div>
    <!-- hidden html code for widget, etc -->
    <div style="display: none;">
        <div id="widget_html_container">
            <!-- <div id="{widget_id}" class="col-md-6 col-xl-4 col-xxl-3">
                <div class="card widget-1">
                    <div class="card-body">
                        <div class="widget-content">
                            <div class="widget-round primary">
                                <div class="bg-round">
                                    <div class="svg-fill">
                                        <i class="widget-1-i {widget_icon}"></i>
                                    </div>
                                    <svg class="half-circle svg-fill">
                                        <use href="<?= user_asset('svg/icon-sprite.svg#halfcircle') ?>">
                                        </use>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h4 class="dw_data">
                                    {widget_number}
                                </h4>
                                <span class="dw_label f-light">
                                    {widget_label}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
            <div id="{widget_id}" class="col-md-6 col-xl-4 col-xxl-3">
                <div class="card shining-card">
                    <div class="card-body">
                        <i class="widget-1-i {widget_icon} me-2"></i>
                        <!-- <img src="<?= base_url('coinex/images/coins/01.png') ?>"
                            class="img-fluid avatar avatar-50 avatar-rounded" alt="img60"> -->

                        <span class="fs-5 me-2 dw_label">{widget_label}</span>
                        <svg width="36" height="35" viewBox="0 0 36 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M3.86124 21.6224L11.2734 16.8577C11.6095 16.6417 12.041 16.6447 12.3718 16.8655L18.9661 21.2663C19.2968 21.4871 19.7283 21.4901 20.0644 21.2741L27.875 16.2534"
                                stroke="#BFBFBF" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path
                                d="M26.7847 13.3246L31.6677 14.0197L30.4485 18.7565L26.7847 13.3246ZM30.2822 19.4024C30.2823 19.4023 30.2823 19.4021 30.2824 19.402L30.2822 19.4024ZM31.9991 14.0669L31.9995 14.0669L32.0418 13.7699L31.9995 14.0669C31.9994 14.0669 31.9993 14.0669 31.9991 14.0669Z"
                                fill="#BFBFBF" stroke="#BFBFBF"></path>
                        </svg>
                        <div class="pt-3">
                            <h4 class="counter dw_data" style="visibility: visible;" style="visibility: visible;">
                                {widget_number}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="refer_link_input_container">
            <?= user_component('input', ['name' => 'referral_url', 'groupClass' => 'ref_inp_group', 'class' => 'bg-ld bg-ld-disabled', 'disabled' => true, 'value' => $referLink]) ?>
        </div>
    </div>
    <!-- hidden html code for widget, etc -->
</div>

<?= $this->endSection() ?>


<?= $this->section('script') ?>
<script src="<?= user_asset('js/chart/apex-chart/apex-chart.js') ?>"></script>
<script>


    const currencySymbol = 'fa-solid fa-coins';

    $(document).ready(function () {

        try {

            const hasDirectUser = <?= $hasDirectUser ? 'true' : 'false' ?>;
            const levelTeamApi = "<?= route('user.team.levelTeam') ?>";

            Dashboard.dashboardApi = '<?= route('user.home.dashboardPost') ?>';

            const userName = '<?= $userName ?>';
            const currentHour = <?= $currentHour; ?>;
            const referLink = '<?= $referLink ?>';
            const savedData = <?= json_encode($savedData ?? []) ?>;
            const widget_html = $('#widget_html_container').html();
            const userAssetDir = "<?= $userAssetDir ?>";


            // refer link setup
            Dashboard.setupReferLink({
                referLink,
                userAssetDir,
                referLinkInput: $('#refer_link_input_container').html(),
            });


            const widgetOptions = {
                api: Dashboard.dashboardApi,
                widgetStack: '#widgets_stack',
                widgetHtml: widget_html,
                widgetPlaceholder: '<i class="fa-solid fa-spinner fa-spin"></i>'
            };


            savedData.data && savedData.data.forEach((data, index) => {
                const widgetId = '_sdata_' + index;
                let html = widget_html
                    .replaceAll('{widget_id}', widgetId)
                    .replaceAll('{widget_number}', data.value)
                    .replaceAll('{widget_label}', data.title)
                    .replaceAll('{widget_icon}', data.icon ?? savedData.defaultIcon);
                $(html).appendTo('#widgets_stack');

                // now wrapping the widget with link
                if (data.url) {
                    const anchor = `<a href="${data.url}" class="without-anchor-effect"></a>`;
                    $(`#${widgetId} .card`).wrap(anchor);
                }
            });

            Dashboard.setupDashboardWidgets({
                ...widgetOptions,
                defaultIcon: 'fa-solid fa-users',
                widgets: [{
                    component: 'direct_team',
                    label: 'Direct Team',
                },
                {
                    component: 'direct_active_team',
                    label: 'Direct Active Team',
                },
                {
                    component: 'total_team_count',
                    label: 'Total Team',
                }, {
                    component: 'total_active_team_count',
                    label: 'Total Active Team'
                },
                {
                    component: 'direct_team_investment',
                    label: 'Direct Team Business',
                    icon: currencySymbol
                },
                {
                    component: 'total_team_investment',
                    label: 'Total Team Business',
                    icon: currencySymbol
                }

                ],
            });



            // setup accordian
            Dashboard.setupProfile();

            // earning chart
            Dashboard.setupEarningChartWidget();


            // earning chart
            Dashboard.setupEarningChartWidget();


            $.post(Dashboard.dashboardApi, {
                action: 'income_progress',
                ...csrf_data()
            }, function (res) {
                console.log(res);
                if (res.html) {
                    $('#ip-loader').fadeOut('fast', function () {
                        $('#income_progress').hide().html(res.html).slideDown('fast');
                    });
                }
            });


            // level team table setup
            if (hasDirectUser) {
                $.post(levelTeamApi, {
                    ...csrf_data()
                }, function (res) {
                    if (res.html) {
                        $('#lt-loader').fadeOut('fast', function () {
                            $('#lt-container').hide().html(res.html).slideDown('fast');
                        });
                    }
                });
            }


        } catch (error) {
            console.log(error);
            sAlertServerError1();
        }
    });
</script>

<?= $this->endSection() ?>