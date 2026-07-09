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

            <h1 class="mb-4">Point of Sale</h1>

            <?php
                require __DIR__ . '/../utils/displayActionResultAlert.php';

                $orderPlaceFailureMessage = 'Failed to place order.';

                if ($updateStockErrorMessages) {
                    foreach ($updateStockErrorMessages as $message) {
                        $orderPlaceFailureMessage .= ' ' . $message;
                    }
                } else $orderPlaceFailureMessage .= ' An error occurred.';
                displayActionResultAlert($orderPlaced, 'Order placed successfully!', $orderPlaceFailureMessage);
            ?>

            <div class="row mx-0 gap-3 mb-3">
                <?php 
                    $cashierPage = true;

                    require __DIR__ . '/../partials/totalSales.php'; 
                ?>

                <?php 
                    $cashierContributed = true;

                    $revenue = $contributedRevenue;

                    require __DIR__ . '/../partials/revenue.php';
                ?>
            </div>

            <div class="row mx-0 gap-3 align-items-start">
                <div class="card col-lg col-12 medicines-table scroll-if-needed p-0">
                    <?php 
                        require __DIR__ . '/../partials/stockTable.php'; 
                    ?>
                </div>

                <div class="card cart col-lg col-12 p-0">
                    <div class="card-body">
                        <h2>Cart</h2>
                        <p class="empty-cart-message">Cart is currently empty.</p>
                        
                        <form action="/employee/pos/place-order" method="post">
                            <?php $cashierId = Employee::findEmployeeId($username); ?>
                            <input type="text" name="cashierId" value="<?php echo $cashierId; ?>" hidden>

                            <div class="scroll-if-needed">
                                <table class="items d-none w-100 mt-3 mb-2">
                                    <thead>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                        <th>Action</th>
                                    </thead>
                                    <tbody>

                                        <tr class="cart-total d-none">
                                            <th colspan="3">Total</th>
                                            <td colspan="3" class="fw-semibold total"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            

                            <fieldset class="payment-method-selector d-none mb-3">
                                <legend>Select a payment method</legend>
                            
                                <label for="cash">
                                    <input type="radio" name="paymentMethod" id="cash" value="cash" required>
                                    Cash
                                </label>
                                <label for="card">
                                    <input type="radio" name="paymentMethod" id="card" value="card">
                                    Card
                                </label>
                                <label for="online-transfer">
                                    <input type="radio" name="paymentMethod" id="online-transfer" value="online transfer">
                                    Online Transfer
                                </label>
                            </fieldset>

                            <button type="submit" class="confirm-order-button btn btn-primary" disabled>Confirm Order</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>


    </div>

    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/cart.js"></script>
</body>
</html>