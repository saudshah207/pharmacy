<?php 

function getDBConnection($maxAttempts = 5) {
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

    $connection = null;

    try {
        $connection = new mysqli($host, $user, $password, $name, $port);
    } catch (Exception $error) {
        $maxAttempts--;
        
        if ($maxAttempts > 0) {
            sleep(1);
            $connection = getDBConnection($maxAttempts);
        }
    }

    return $connection;
}

function bindAndExecuteStatement($statement, $parameterTypes, $parameters) {
    $statement->bind_param($parameterTypes, ...$parameters);
    $status = $statement->execute();

    return ['status' => $status, 'statement' => $statement];
}