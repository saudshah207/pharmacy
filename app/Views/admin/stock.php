<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>Stock</title>
</head>
<body class="container-xxl d-flex flex-column">
    <div class="dashboard flex-grow-1 row">
        <?php require __DIR__ . '/partials/sidebar.php' ?>
        
        <main class="flex-grow-1 col-lg-10 col-12 p-3">
            <?php require __DIR__ . '/../partials/sidebarToggle.php'; ?>

            <div class="mb-4 row align-items-center justify-content-between gap-3 m-0">
                <h1 class="mb-0 col-8 p-0">All Stock Boxes</h1>

                <button class="btn btn-primary col-3 w-max-content d-flex align-items-center justify-content-center gap-2 me-xl-0 me-5" data-bs-toggle="modal" data-bs-target="#addMedicineBoxModal">
                    Add New Box

                    <img src="/assets/icons/add.svg" alt="plus icon">
                </button>
            </div>

            <?php
                require __DIR__ . '/../utils/displayActionResultAlert.php';

                displayActionResultAlert($medicineBoxAdded, 'Stock box added successfully!', 'Failed to add stock box. Invalid data provided.');
                displayActionResultAlert($medicineBoxEdited, 'Stock box edited successfully!', 'Failed to edit stock box. Invalid data provided.');
                displayActionResultAlert($medicineBoxDeleted, 'Stock box deleted successfully!', 'Failed to delete stock box. An error occurred.');
            ?>

            <div class="card scroll-if-needed">
                <?php 
                    $isAdmin = $_SESSION['user_role'] === "admin";

                    require __DIR__ . '/../partials/stockTable.php'; 
                ?>
            </div>
        </main>

        <?php
            $minExpiryDate = new DateTime();
            $minExpiryDate = $minExpiryDate->add(new DateInterval('P1D')); // add a period of 1 day to current date

            $expiryInputValidation = 'min="' . $minExpiryDate->format('Y-m-d') . '"';
            $priceInputValidation = 'min="1" step="any"';
        ?>

        <div class="modal fade" id="editMedicineBoxModal" tabindex="-1" aria-labelledby="editMedicineBoxModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="editMedicineBoxModalLabel">Edit Stock Box</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editMedicineBoxForm" class="row row-gap-3" action="/admin/stock/edit" method="post">
                            <input class="flex-grow-1 w-100" type="text" name="id" hidden>

                            <label class="col-lg-6 col-12 d-flex flex-wrap gap-2" for="medicine">Medicine *
                                <select class="w-100" name="medicineId" id="medicine" required>
                                    <?php 
                                        $medicineName = null;

                                        foreach ($medicines as $medicine) {
                                            $medicineId = $medicine->getId();
                                            $medicineName = $medicine->getName();
                                            
                                            echo "<option value='$medicineId'>$medicineName</option>";
                                        }
                                        ?>
                                </select>
                            </label>

                            <label class="col-lg-6 col-12 d-flex flex-wrap gap-2" for="stock">Stock *
                                <input class="flex-grow-1 w-100" type="number" name="stock" id="stock" min="0" required>
                            </label>

                            <label class="col-lg-6 col-12 d-flex flex-wrap gap-2" for="expiry">Expiry *
                                <input class="flex-grow-1 w-100" type="date" name="expiry" id="expiry" <?php echo $expiryInputValidation; ?> required>
                            </label>

                            <label class="col-lg-6 col-12 d-flex flex-wrap gap-2" for="purchasePrice">Purchase Price *
                                <input class="flex-grow-1 w-100" type="number" name="purchasePrice" id="purchasePrice" <?php echo $priceInputValidation; ?> required>
                            </label>

                            <label class="col-lg-6 col-12 d-flex flex-wrap gap-2" for="sellingPrice">Selling Price *
                                <input class="flex-grow-1 w-100" type="number" name="sellingPrice" id="sellingPrice" <?php echo $priceInputValidation; ?> required>
                            </label>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" form="editMedicineBoxForm" class="btn btn-primary">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addMedicineBoxModal" tabindex="-1" aria-labelledby="addMedicineBoxModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="addMedicineBoxModalLabel">Add Stock Box</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addMedicineBoxForm" class="row row-gap-3" action="/admin/stock/add" method="post">
                            <label class="col-lg-6 col-12 d-flex flex-wrap gap-2" for="medicine">Medicine *
                                <select class="w-100" name="medicineId" id="medicine" required>
                                    <?php 
                                        $medicineName = null;

                                        foreach ($medicines as $medicine) {
                                            $medicineId = $medicine->getId();
                                            $medicineName = $medicine->getName();
                                            
                                            echo "<option value='$medicineId'>$medicineName</option>";
                                        }
                                    ?>
                                </select>
                            </label>

                            <label class="col-lg-6 col-12 d-flex flex-wrap gap-2" for="stock">Stock *
                                <input class="flex-grow-1 w-100" type="number" name="stock" id="stock" min="1" required>
                            </label>

                            <label class="col-lg-6 col-12 d-flex flex-wrap gap-2" for="expiry">Expiry *
                                <input class="flex-grow-1 w-100" type="date" name="expiry" id="expiry" <?php echo $expiryInputValidation; ?> required>
                            </label>

                            <label class="col-lg-6 col-12 d-flex flex-wrap gap-2" for="purchasePrice">Purchase Price *
                                <input class="flex-grow-1 w-100" type="number" name="purchasePrice" id="purchasePrice" <?php echo $priceInputValidation; ?> required>
                            </label>

                            <label class="col-lg-6 col-12 d-flex flex-wrap gap-2" for="sellingPrice">Selling Price *
                                <input class="flex-grow-1 w-100" type="number" name="sellingPrice" id="sellingPrice" <?php echo $priceInputValidation; ?> required>
                            </label>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" form="addMedicineBoxForm" class="btn btn-primary">Add Box</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteMedicineBoxModal" tabindex="-1" aria-labelledby="deleteMedicineBoxModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="deleteBoxMedicineModalLabel">Delete Stock Box</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="deleteMedicineBoxForm" class="row row-gap-3" action="/admin/stock/delete" method="post">
                        <p>Are you sure you want to delete <span class="medicine-to-delete"></span> box?</p>
                        <input type="text" hidden name="medicineBoxToDeleteId">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="deleteMedicineBoxForm" class="btn btn-danger text-white">Delete Stock</button>
                </div>
                </div>
            </div>
        </div>

    </div>

    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/setEditStockBoxData.js"></script>
    <script src="/assets/js/setDeleteStockBoxData.js"></script>
</body>
</html>