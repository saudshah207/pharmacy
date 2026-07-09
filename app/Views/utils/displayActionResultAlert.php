<?php

function displayActionResultAlert($actionPerformed, $successMessage, $failureMessage) {
    $attemptedToPerformAction = isset($actionPerformed);

    if ($attemptedToPerformAction) {
        require __DIR__ . '/getAlertCSSClass.php';
        require __DIR__ . '/../partials/actionResultAlert.php';
    }
}