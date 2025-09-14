<?= $this->extend('user_dashboard/layout/master') ?>


<?= $this->section('slot') ?>
<style>
    .chart-container {
        width: 100%;
        height: 600px;
        /* Adjust as needed */
    }

    .node-button-div div {
        background-color: #202022 !important;
        color: white !important;
    }
</style>
<div class="container-fluid">

    <div class="d-flex justify-content-between">
        <button class="btn btn-primary mb-2" onclick="chart.fit()">Fit to the screen</button>
        <form method="GET" class="d-flex">
            <input class="form-control form-control-sm" placeholder="Enter User Id" type="text" name="search_user_id"
                value="<?= inputGet('search_user_id') ?>" required />
            <button class="btn btn-sm btn-primary"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <?php if ($errorMessage): ?>
        <div class="alert alert-danger mt-3">
            <?= $errorMessage ?>
        </div>
    <?php endif; ?>

    <div class="chart-container"></div>

    <!-- Bootstrap Modal -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">User Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="userModalContent">
                        <!-- Loader will appear here first -->
                        <div class="d-flex justify-content-center">
                            <i class="fas fa-spinner fa-spin" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>


<?php $this->section('script') ?>

<script src="https://d3js.org/d3.v7.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/d3-org-chart@3.0.1"></script>
<script src="https://cdn.jsdelivr.net/npm/d3-flextree@2.1.2/build/d3-flextree.js"></script>


<?php if ($user): ?>
    <script>
        var chart = null;

        // Initial root data from PHP
        var chartData = <?= json_encode([$userObject]) ?>;
        var loadedNodes = new Set();

        $(document).ready(function () {
            // Initialize the chart with initial data

            chart = new d3.OrgChart()
                .nodeHeight((d) => 75)
                .nodeWidth((d) => 220 + 2)
                .childrenMargin((d) => 50)
                .compactMarginBetween((d) => 35)
                .compactMarginPair((d) => 30)
                .neighbourMargin((a, b) => 20)
                .initialZoom(2)
                .compact(false)
                .linkUpdate(function (d, i, arr) {
                    d3.select(this)
                        .attr('stroke', '#FF971D')
                        .attr('stroke-width', 1);
                })
                // .layout('left')  // Ensure left-
                .nodeContent(function (d, i, arr, state) {
                    const color = '#202022';

                    const statusLabel = d.data.status ?
                        `<div class="badge badge-sm bg-success">Active</div>` :
                        `<div class="badge badge-sm bg-danger">InActive</div>`;

                    return `
                    <div style='width:${d.width}px;height:${d.height}px;padding-left:1px;padding-right:1px'>
                        <div style="font-family: 'Inter', sans-serif;background-color:${color}; display: flex; align-items: center; margin-left:-1px;width:${d.width - 2}px;border-radius:10px;border: 1px solid #FF971D; padding-bottom: 10px;">
                  
           
                                <img src="${d.data.image}" style="width:40px;height:40px;border-radius:50%;margin-left:10px;margin-top:10px;" onclick="showUserModal(event, ${d.data.id});">
                                </img>
                                <div style="margin-left: 10px;">
                                    <div style="font-size:15px;color:#ffffff;margin-top:10px">${d.data.name}</div>
                                    <div style="color:#a29fa9;margin-top:3px;font-size:10px;">
                                        ${d.data.user_id}
                                        ${statusLabel}
                                    </div>
                                    <div style="color:#a29fa9;margin-top:3px;font-size:10px;">
                                        Direct : ${d.data.children_count}
                                    </div>
                                </div>
                     

                        </div>
                    </div>
                `;
                })
                .container('.chart-container')
                .data(chartData)
                .render();

            // On node click, load children dynamically
            chart.onNodeClick((d) => {

                if (loadedNodes.has(d.data.id)) return;

                loadedNodes.add(d.data.id);

                const existingChildren = chartData.filter(item => item.parentId === d.data.id);

                if (existingChildren.length > 0)
                    return;

                // Fetch children via AJAX
                $.ajax({
                    url: '<?= current_url() ?>?parent_id=' + d.data.id,
                    method: 'GET',
                    dataType: 'json',
                    success: function (newData) {
                        if (newData && newData.length > 0) {
                            chartData.push(...newData);
                            chart.data(chartData).render();
                        }
                    },
                    error: function (error) {
                        console.error('Error fetching child nodes:', error);
                    }
                });
            });
            window.chart = chart;
        });

        function showUserModal(event, userIdPk) {
            event.stopPropagation();
            // Show the modal immediately with the loader
            var $modal = $('#userModal');
            var $modalBody = $modal.find('.modal-body');

            // Set loader each time modal opens
            $modalBody.html(`
            <div class="d-flex justify-content-center py-4">
                <i class="fas fa-spinner fa-spin" style="font-size: 3rem;"></i>
            </div>
        `);

            // Show modal using Bootstrap 5 via jQuery
            $modal.modal('show');

            // Fetch user data (expects HTML string inside `html` key of response)
            $.ajax({
                url: '<?= current_url() ?>?modal_user_id=' + userIdPk,
                method: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.html) {
                        $modalBody.html(response.html);
                    } else {
                        $modalBody.html('<p class="text-danger text-center">No HTML content found.</p>');
                    }
                },
                error: function () {
                    $modalBody.html('<p class="text-danger text-center">Failed to load user data.</p>');
                }
            });
        }
    </script>
<?php endif; ?>

<?php $this->endSection() ?>