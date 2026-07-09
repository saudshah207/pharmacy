<?php 
    $isAdmin = $isAdmin ?? null;
    $isEmployee = !$isAdmin;
    $needPurchasePrices = $isAdmin;
?>

<table class="card-body">
    <thead>
        <th class="py-2 px-3">ID</th>
        <th class="py-2 px-3">Medicine</th>
        <th class="py-2 px-3">Stock</th>
        <th class="py-2 px-3">Expiry</th>

        <?php 
            if ($needPurchasePrices) {
                echo "<th class='py-2 px-3'>Purchase Price</th>";
            }
        ?>

        <th class="py-2 px-3">
            <?php
                echo $isAdmin ? 'Selling Price' : 'Price';
            ?>
        </th>
        <th class="py-2 px-3">Actions</th>
    </thead>
    <tbody>
        <?php 
            $boxId = $medicineName = $stock = $expiryDate = $purchasePrice = $sellingPrice = $actionButtons = null;

            $actionButtons = $isAdmin ?  
                "<button class='action-btn edit-stock-box-btn btn btn-primary rounded-circle d-flex align-items-center justify-content-center' data-bs-toggle='modal' data-bs-target='#editMedicineBoxModal'>
                    <img src='/assets/icons/edit.svg' alt='edit icon'>
                </button>
                <button class='action-btn delete-stock-box-btn btn btn-primary rounded-circle d-flex align-items-center justify-content-center' data-bs-toggle='modal' data-bs-target='#deleteMedicineBoxModal'>
                    <img src='/assets/icons/delete.svg' alt='delete icon'>
                </button>" : 
                "<button class='action-btn add-to-cart-btn btn btn-primary rounded-circle d-flex align-items-center justify-content-center'>
                    <img src='/assets/icons/add.svg' alt='add icon'>
                </button>";        

            foreach ($medicineBoxes as $box) {
                $boxId = $box->getId();
                $medicineId = $box->getMedicineId();

                $medicineName = Medicine::findMedicineName($medicineId);
                $stock = $box->getStock();

                if ($isEmployee && $stock < 1) continue;

                $expiryDate = $box->getExpiryDate();
                $purchasePrice = $box->getPurchasePrice();
                $sellingPrice = $box->getSellingPrice();

                $minStockLevel = Medicine::findMinimumStockLevel($medicineId);
                $stockStatusIndicatorCSSClass = $stock >= $minStockLevel ? 'green-color' : 'warning-color';

                $purchasePriceRow = $needPurchasePrices ? 
                    "<td class='py-2 px-3'>$purchasePrice</td>" : 
                    '';

                $priceDataAttributes = $needPurchasePrices ? 
                    "data-purchase-price='$purchasePrice' data-selling-price='$sellingPrice'" : 
                    "data-price='$sellingPrice'";

                echo 
                    "<tr data-box-id='$boxId' data-medicine-id='$medicineId' data-medicine-name='$medicineName' data-stock='$stock' data-expiry-date='$expiryDate' $priceDataAttributes>
                        <td class='py-2 px-3'>$boxId</td>
                        <td class='py-2 px-3'>$medicineName</td>
                        <td class='py-2 px-3 $stockStatusIndicatorCSSClass'>$stock</td>
                        <td class='py-2 px-3'>$expiryDate</td>
                        $purchasePriceRow
                        <td class='py-2 px-3'>$sellingPrice</td>
                        <td class='py-2 px-3 d-flex gap-2'>
                            $actionButtons
                        </td>
                    </tr>";
            }
        ?>
    </tbody>
</table>