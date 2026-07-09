<?php 

function getAlertCSSClass($actionPerformed) {
    $alertCSSClass = 'alert-success';

    if (!$actionPerformed) $alertCSSClass = 'alert-danger';

    return $alertCSSClass;
}