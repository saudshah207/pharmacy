<?php $stretchSixColumnsBeforeSmallBreakpoint = isset($stretchSixColumnsBeforeSmallBreakpoint) && $stretchSixColumnsBeforeSmallBreakpoint; ?>

<div class="card <?php if ($stretchSixColumnsBeforeSmallBreakpoint) echo 'col-sm'; ?> col-12">
    <div class="card-body">
        <h2 class="card-title">Most Popular Medicine</h2>
        <p class="card-text fw-semibold d-flex flex-wrap column-gap-4 row-gap-1">
            <?php echo $mostSoldMedicine; ?>
            <span class="green-color">
                <?php echo $mostSalesCount . ' units sold.' ?>
            </span>
        </p>
    </div>
</div>