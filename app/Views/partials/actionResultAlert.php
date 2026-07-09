<div class="alert <?php echo getAlertCSSClass($actionPerformed) ?> alert-dismissible fade show" role="alert">
    <?php 
        if ($actionPerformed) {
            echo $successMessage;
        } else {
            echo $failureMessage;
        }
    ?>    

    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>