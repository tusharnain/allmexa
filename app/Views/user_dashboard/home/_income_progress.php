<style>
    .small-progressbar::after {
        display: none;
    }

    .progress {
        border-radius: 10rem;
    }

    .small-progressbar .progress-label {
        right: -42px;
    }
</style>

<div>

    <div class="mb-5">
        <h4>
            Invested : <?= f_amount($investment) ?>
        </h4>
    </div>


    <div class="progress sm-progress-bar overflow-visible mt-4" style="height: 20px;">
        <div class="progress-bar-animated small-progressbar bg-primary rounded-pill progress-bar-striped"
            role="progressbar" style="width: <?= $receivedPercentage ?>%;" aria-valuenow="<?= $receivedPercentage ?>"
            aria-valuemin="0" aria-valuemax="100">
            <!-- <span class="txt-primary fs-6 progress-label">
                <?= f_amount($received) ?>
            </span> -->
        </div>
    </div>

    <div class="text-end mt-3">
        <?= f_amount($received) . '/' . f_amount(_c($max), isUser: true) ?>
        (<?= $multiplier ?>X)
    </div>


</div>