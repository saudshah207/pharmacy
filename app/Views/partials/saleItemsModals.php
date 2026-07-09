<?php 
    function getSaleItemsTable($saleItems, $saleTotal) {
        $table = 
            "<table class='w-100'>
                <thead>
                    <th class='py-2 px-3'>Medicine</th>
                    <th class='py-2 px-3'>Quantity</th>
                    <th class='py-2 px-3'>Unit Price</th>
                    <th class='py-2 px-3'>Subtotal</th>
                </thead>
                <tbody>";

        foreach ($saleItems as $item) {
            $medicineName = $item['medicineName'];
            $quantity = $item['quantity'];
            $unitPrice = $item['unitPrice'];
            $subTotal = $item['subTotal'];

            $table .= 
                "<tr>
                    <td class='py-2 px-3'>$medicineName</td>
                    <td class='py-2 px-3'>$quantity</td>
                    <td class='py-2 px-3'>$unitPrice</td>
                    <td class='py-2 px-3'>$subTotal</td>
                </tr>";
        }

        $table .= 
                    "<tr>
                        <th class='py-2 px-3' colspan='3'>Total</th>
                        <td class='py-2 px-3 fw-semibold' colspan='1'>$saleTotal</td>
                    </tr>
                </tbody>
            </table>";

        return $table;
    }

    $salesCount = 0;

    foreach ($sales as $sale) {
        $saleId = $sale->getId();
        $saleTotal = $sale->getTotalBill();

        $saleItems = Sale::getSaleItems($saleId);
        $saleItemsTable = getSaleItemsTable($saleItems, $saleTotal);

        $salesCount++;

        echo 
            "<div class='modal fade' id='saleItemsModal-$salesCount' tabindex='-1' aria-labelledby='saleItemsModalLabel-$salesCount' aria-hidden='true'>
                <div class='modal-dialog modal-dialog-centered'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h1 class='modal-title fs-5' id='saleItemsModalLabel-$salesCount'>Sale Items</h1>
                            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                        </div>
                        <div class='modal-body scroll-if-needed'>
                            <td>
                                $saleItemsTable
                            </td>
                        </div>
                        <div class='modal-footer'>
                            <button class='btn btn-primary text-white' data-bs-dismiss='modal'>Done</button>
                        </div>
                    </div>
                </div>
            </div>";
    }
?>