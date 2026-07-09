<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>Cashier</title>
</head>
<body class="container-xxl row m-0 p-0">
    <?php require __DIR__ . '/partials/sidebar.php' ?>
    
    <main class="col-xl-10 col-12 p-3">
        <?php require __DIR__ . '/../partials/sidebarToggle.php'; ?>

        <h1 class="mb-4">Welcome 
            <?php 
                echo $username . '!'; 
            ?>
        </h1>

        <div class="row mx-0 gap-3">
            <?php 
                $cashierPage = true;

                require __DIR__ . '/../partials/totalSales.php'; 
            ?>

            <?php 
                $cashierContributed = true;

                $revenue = $contributedRevenue;

                require __DIR__ . '/../partials/revenue.php';
            ?>

            <div class="card scroll-if-needed col-12 p-0">
                <?php 
                    $tableHeading = 'Recent Sales By You';

                    $sales = $recentEmployeeSales;

                    require __DIR__ . '/../partials/salesTable.php';
                ?>
            </div>
        </div>
    </main>

    <script src="/assets/js/bootstrap.min.js"></script>
</body>
</html>