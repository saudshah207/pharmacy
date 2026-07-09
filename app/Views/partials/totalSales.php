<div class="card col-sm col-12">
    <div class="card-body">
        <h2 class="card-title">Total Sales <?php echo isset($cashierPage) && $cashierPage ? 'By You' : ''; ?></h2>
        <p class="card-text fw-semibold">
            <?php echo $totalSales; ?>
        </p>
    </div>
</div>