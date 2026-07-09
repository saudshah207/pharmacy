<?php
    $needSalesCashiers = isset($needSalesCashiers) && $needSalesCashiers;
    $needSaleItemsModals = isset($needSaleItemsModals) && $needSaleItemsModals;
?>

<table class="card-body">
    <?php 
        if (isset($tableHeading) && $tableHeading !== '') {
            echo "<h2 class='sales-table-title card-title pt-3 mb-2'>$tableHeading</h2>";
        }   

        if (count($sales) === 0) {
            $margin = isset($tableHeading) ? 'mb-3' : 'my-3'; 
            echo "<p class='sales-table-subtitle $margin'>No sales exist.</p>";

            return;
        }
    ?>

    <thead>
        <th class="py-2 px-3">ID</th>
        <th class="py-2 px-3">Total</th>
        
        <?php
            if ($needSalesCashiers) {
                echo "<th class='py-2 px-3'>Cashier</th>";
            }
        ?>

        <th class="py-2 px-3">Method</th>
        <th class="py-2 px-3">Date</th>
        
        <?php
            if ($needSaleItemsModals) {
                echo "<th class='py-2 px-3'>Actions</th>";
            }
        ?>
    </thead>
    <tbody>
        <?php 
            $saleId = $saleTotal = $saleCashier = $paymentMethod = $saleDate = null;
            $saleCount = 0;

            foreach ($sales as $sale) {
                $saleId = $sale->getId();
                $saleTotal = $sale->getTotalBill();
                $saleCashier = Employee::findEmployeeUsername($sale->getCashierId());
                $paymentMethod = $sale->getPaymentMethod();
                $saleDate = $sale->getDate();

                $saleCount++;

                $saleCashierRow = $needSalesCashiers ? 
                    "<td class='py-2 px-3'>$saleCashier</td>" : 
                    '';
                $seeSaleItemsButton = $needSaleItemsModals ? 
                    "<td class='py-2 px-3'>
                        <button class='action-btn see-sale-items-btn btn btn-primary rounded-circle d-flex align-items-center justify-content-center' data-bs-toggle='modal' data-bs-target='#saleItemsModal-$saleCount'>
                            <svg xmlns='http://www.w3.org/2000/svg' height='24px' viewBox='0 -960 960 960' width='24px' fill='#ffffff'>
                                <path d='M592.1-388q46.13-46.22 46.13-112.09T592-612.1q-46.22-46.13-112.09-46.13T367.9-612q-46.13 46.22-46.13 112.09T368-387.9q46.22 46.13 112.09 46.13T592.1-388Zm-187.39-36.71q-31.09-31.1-31.09-75.29 0-44.19 31.09-75.29 31.1-31.09 75.29-31.09 44.19 0 75.29 31.09 31.09 31.1 31.09 75.29 0 44.19-31.09 75.29-31.1 31.09-75.29 31.09-44.19 0-75.29-31.09ZM234.27-301.12Q123.54-375.77 70.42-500q53.12-124.23 163.8-198.88 110.67-74.66 245.73-74.66 135.05 0 245.78 74.66Q836.46-624.23 889.58-500q-53.12 124.23-163.8 198.88-110.67 74.66-245.73 74.66-135.05 0-245.78-74.66ZM480-500Zm205.08 158.88Q778.77-399.81 827.96-500q-49.19-100.19-142.88-158.88-93.7-58.7-205.08-58.7-111.38 0-205.08 58.7Q181.23-600.19 132.04-500q49.19 100.19 142.88 158.88 93.7 58.7 205.08 58.7 111.38 0 205.08-58.7Z'/>
                            </svg>
                        </button>
                    </td>" : 
                    '';

                echo 
                    "<tr data-sale-id='$saleId'>
                        <td class='py-2 px-3'>$saleId</td>
                        <td class='py-2 px-3'>$saleTotal</td>
                        $saleCashierRow
                        <td class='py-2 px-3'>$paymentMethod</td>
                        <td class='py-2 px-3'>$saleDate</td>
                        $seeSaleItemsButton
                    </tr>";
            }
        ?>
    </tbody>
</table>