<?php 

function getDBConnection() {
    $env = parse_ini_file("../.env", false, INI_SCANNER_RAW);

    return new mysqli($env['DB_HOST'], $env['DB_USER'], $env['DB_PASSWORD'], 'pharmacy', $env['DB_PORT']);
}

function bindAndExecuteStatement($statement, $parameterTypes, $parameters) {
    $statement->bind_param($parameterTypes, ...$parameters);
    $status = $statement->execute();

    return ['status' => $status, 'statement' => $statement];
}