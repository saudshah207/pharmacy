<?php 

function getDBConnection() {
    if (file_exists(__DIR__ . "/../.env")) {
        $env = parse_ini_file("../.env", false, INI_SCANNER_RAW);

        $host = $env['DB_HOST'];
        $user = $env['DB_USER'];
        $password = $env['DB_PASSWORD'];
        $name = $env['DB_NAME'];
        $port = $env['DB_PORT'];
    } else {
        $host = getenv('DB_HOST');
        $user = getenv('DB_USER');
        $password = getenv('DB_PASSWORD');
        $name = getenv('DB_NAME');
        $port = getenv('DB_PORT');
    }

    return new mysqli($host, $user, $password, $name, $port);
}

function bindAndExecuteStatement($statement, $parameterTypes, $parameters) {
    $statement->bind_param($parameterTypes, ...$parameters);
    $status = $statement->execute();

    return ['status' => $status, 'statement' => $statement];
}