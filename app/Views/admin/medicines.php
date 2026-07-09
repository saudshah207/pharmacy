<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/styles.css">
    <title>Medicines</title>
</head>
<body class="container-xxl d-flex flex-column">
    <div class="dashboard flex-grow-1 row">
        <?php require __DIR__ . '/partials/sidebar.php' ?>
        
        <main class="flex-grow-1 col-lg-10 col-12 p-3">
            <?php require __DIR__ . '/../partials/sidebarToggle.php'; ?>

            <div class="mb-4 row align-items-center justify-content-between gap-3 m-0">
                <h1 class="mb-0 col-8 p-0">All Medicines</h1>

                <button class="btn btn-primary col-3 w-max-content d-flex align-items-center justify-content-center gap-2 me-xl-0 me-5" data-bs-toggle="modal" data-bs-target="#addMedicineModal">
                    Add New Medicine

                    <img src="/assets/icons/add.svg" alt="plus icon">
                </button>
            </div>

            <?php
                require __DIR__ . '/../utils/displayActionResultAlert.php';

                displayActionResultAlert($medicineAdded, 'Medicine added successfully!', 'Failed to add medicine. Invalid data provided.');
                displayActionResultAlert($medicineEdited, 'Medicine edited successfully!', 'Failed to edit medicine. Invalid data provided.');
                displayActionResultAlert($medicineDeleted, 'Medicine deleted successfully!', 'Failed to delete medicine. Corresponding stock boxes exist.');
            ?>

            <div class="card scroll-if-needed">
                <table class="card-body">
                    <thead>
                        <th class="py-2 px-3">ID</th>
                        <th class="py-2 px-3">Name</th>
                        <th class="py-2 px-3">Category</th>
                        <th class="py-2 px-3">Suggested Stock</th>
                        <th class="py-2 px-3">Actions</th>
                    </thead>
                    <tbody>
                        <?php 
                            $medicineId = $medicineName = $medicineCategory = $minimumStockToKeep = null;

                            foreach ($medicines as $medicine) {
                                $medicineId = $medicine->getId();
                                $medicineName = $medicine->getName();
                                $medicineCategory = $medicine->getCategoryId() ? Category::findCategoryName($medicine->getCategoryId()) : 'N/A';
                                $minimumStockToKeep = $medicine->getMinimumStockLevel();

                                echo 
                                    "<tr data-medicine-id='$medicineId' data-medicine-name='$medicineName' data-medicine-category='$medicineCategory' data-medicine-min-stock-level='$minimumStockToKeep'>
                                        <td class='py-2 px-3'>$medicineId</td>
                                        <td class='py-2 px-3'>$medicineName</td>
                                        <td class='py-2 px-3'>$medicineCategory</td>
                                        <td class='py-2 px-3'>$minimumStockToKeep</td>
                                        <td class='py-2 px-3 d-flex gap-2' style='width: fit-content'>
                                            <button class='action-btn edit-medicine-btn btn btn-primary rounded-circle d-flex align-items-center justify-content-center' data-bs-toggle='modal' data-bs-target='#editMedicineModal'>
                                                <img src='/assets/icons/edit.svg' alt='edit icon'>
                                            </button>
                                            <button class='action-btn delete-medicine-btn btn btn-primary rounded-circle d-flex align-items-center justify-content-center' data-bs-toggle='modal' data-bs-target='#deleteMedicineModal'>
                                                <img src='/assets/icons/delete.svg' alt='delete icon'>
                                            </button>
                                        </td>
                                    </tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>

        <?php
            // $medicineNameInputValidation = 'maxlength="100" pattern="[A-Za-z]+[ ]?(:?[0-9][mg])?" title="Medicine name can only contain alphabets, numbers and spaces."';
            $medicineNameInputValidation = 'maxlength="100" pattern="[A-Za-z0-9 ]+" title="Medicine name can only contain alphabets, numbers and spaces."';
            $minStockLevelInputValidation = 'min="1"';
        ?>

        <div class="modal fade" id="addMedicineModal" tabindex="-1" aria-labelledby="addMedicineModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addMedicineModalLabel">Add Medicine</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addMedicineForm" class="row row-gap-3" action="/admin/medicines/add" method="post">
                        <label class="col-lg-6 col-12 d-flex flex-wrap gap-2" for="name">Name *
                            <input class="flex-grow-1 w-100" type="text" name="name" id="name" <?php echo $medicineNameInputValidation; ?> required>
                        </label>

                        <label class="col-lg-6 col-12 d-flex flex-wrap gap-2" for="category">Category *
                            <select class="w-100" name="category" id="category" required>
                                <?php 
                                    $categoryName = null;

                                    foreach ($categories as $category) {
                                        $categoryName = $category->getName();

                                        echo "<option value='$categoryName'>$categoryName</option>";
                                    }
                                ?>
                            </select>
                        </label>

                        <label class="col-lg-6 col-12 d-flex flex-wrap gap-2" for="minStockLevel">Suggested Stock *
                            <input class="flex-grow-1 w-100" type="number" name="minStockLevel" id="minStockLevel" <?php echo $minStockLevelInputValidation; ?> required>
                        </label>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="addMedicineForm" class="btn btn-primary">Add Medicine</button>
                </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editMedicineModal" tabindex="-1" aria-labelledby="editMedicineModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editMedicineModalLabel">Edit Medicine</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editMedicineForm" class="row row-gap-3" action="/admin/medicines/edit" method="post">
                        <input class="flex-grow-1 w-100" type="text" name="id" hidden>

                        <label class="col-lg-6 col-12 d-flex flex-wrap gap-2" for="name">Name *
                            <input class="flex-grow-1 w-100" type="text" name="name" id="name" <?php echo $medicineNameInputValidation; ?> required>
                        </label>

                        <label class="col-lg-6 col-12 d-flex flex-wrap gap-2" for="category">Category *
                            <select class="w-100" name="category" id="category" required>
                                <?php 
                                    $categoryName = null;

                                    foreach ($categories as $category) {
                                        $categoryName = $category->getName();

                                        echo "<option value='$categoryName'>$categoryName</option>";
                                    }
                                ?>
                            </select>
                        </label>

                        <label class="col-lg-6 col-12 d-flex flex-wrap gap-2" for="minStockLevel">Suggested Stock *
                            <input class="flex-grow-1 w-100" type="number" name="minStockLevel" id="minStockLevel" <?php echo $minStockLevelInputValidation; ?> required>
                        </label>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="editMedicineForm" class="btn btn-primary">Save Changes</button>
                </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteMedicineModal" tabindex="-1" aria-labelledby="deleteMedicineModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="deleteMedicineModalLabel">Delete Medicine</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="deleteMedicineForm" class="row row-gap-3" action="/admin/medicines/delete" method="post">
                        <p>Are you sure you want to delete <span class="medicine-to-delete"></span>?</p>
                        <input type="text" hidden name="medicineToDeleteId">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="deleteMedicineForm" class="btn btn-danger text-white">Delete Medicine</button>
                </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/setDeleteMedicineData.js"></script>
    <script src="/assets/js/setEditMedicineData.js"></script>
</body>
</html>