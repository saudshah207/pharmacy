<div class="card col-sm col-12">
    <div class="card-body">
        <h2 class="card-title">Revenue <?php echo isset($cashierContributed) && $cashierContributed ? 'Contributed' : ''; ?></h2>
        <p class="card-text green-color fw-semibold">
            <?php echo 'PKR ' . $revenue; ?>
        </p>
    </div>
</div>