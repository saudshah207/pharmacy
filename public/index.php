<?php
    require __DIR__ . '/../config/database.php';

    $dbConnection = getDBConnection();

    require __DIR__ . "/../app/Controller/utils.php";

    require __DIR__ . '/../routes/router.php';

    $router = new Router();

    require __DIR__ . '/../routes/web.php';

    $uri = $_SERVER['REQUEST_URI'];
    $method = $_SERVER['REQUEST_METHOD'];

    $router->resolve($uri, $method);
?>