<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>Login</title>

    <style>
        .password-display-toggle {
            bottom: 0.075rem;
            right: 0.075rem;

            background-color: #ffffff;
            border: none;
        }
    </style>
</head>
<body class="container d-flex justify-content-center align-items-center">
    <?php require __DIR__ . '/partials/header.php' ?>    

    <main class="d-flex flex-column justify-content-center align-items-center gap-3">
        <h1>Login</h1>

        <?php
            require __DIR__ . '/utils/displayActionResultAlert.php';

            /* Only called when loginSuccessful is false (hence only specifying $failureMessage), 
            otherwise we redirect to dashboard */
            displayActionResultAlert($loginSuccessful, '', 'No account exists for provided credentials.');
        ?>

        <form method="post" action="/" class="d-flex flex-column gap-1">
            <label for="username">
                Username
            </label>
            <input type="text" name="username" id="username">

            <label class="d-flex flex-wrap gap-2 position-relative" for="password">
                Password
                <input class="w-100" type="password" name="password" id="password">

                <button type="button" class="d-flex align-items-center justify-content-center position-absolute password-display-toggle">
                    <img src="/assets/icons/visibility.svg" alt="toggle password display icon">
                </button>
            </label>

            <input class="btn btn-primary mt-3" type="submit" class="mt-3">
        </form>
    </main>

    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/togglePasswordDisplay.js"></script>
</body>
</html>