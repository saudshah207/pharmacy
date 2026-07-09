<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>Sales</title>
</head>
<body class="container-xxl d-flex flex-column">
    <div class="dashboard flex-grow-1 row">
        <?php require __DIR__ . '/partials/sidebar.php'; ?>
        
        <main class="flex-grow-1 col-lg-10 col-12 p-3">
            <?php require __DIR__ . '/../partials/sidebarToggle.php'; ?>

            <h1 class="mb-4">Past Sales</h1>

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

                <div class="card scroll-if-needed p-0">
                    <?php 
                        $needSaleItemsModals = true;

                        require __DIR__ . '/../partials/salesTable.php';
                    ?>
                </div>
            </div>
        </main>

        <?php 
            require __DIR__ . '/../partials/saleItemsModals.php';
        ?>
    </div>

    <script src="/assets/js/bootstrap.min.js"></script>
</body>
</html>