<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>Categories</title>

    <style>
        .categories-card {
            width: min(100%, 350px);
        }
    </style>
</head>
<body class="container-xxl d-flex flex-column">
    <div class="dashboard flex-grow-1 row">
        <?php require __DIR__ . '/partials/sidebar.php' ?>
        
        <main class="flex-grow-1 col-lg-10 col-12 p-3">
            <?php require __DIR__ . '/../partials/sidebarToggle.php'; ?>

            <div class="mb-4 row align-items-center justify-content-between gap-3 m-0">
                <h1 class="mb-0 col-8 p-0">All Medicine Categories</h1>

                <button class="btn btn-primary col-3 w-max-content d-flex align-items-center justify-content-center gap-2 me-xl-0 me-5" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    Add New Category

                    <img src="/assets/icons/add.svg" alt="plus icon">
                </button>
            </div>

            <?php
                require __DIR__ . '/../utils/displayActionResultAlert.php';

                $categoryAddFailureMessage = 'Failed to add category. ' . ($categoryAddErrorMessage ?? 'Invalid data provided.');
                displayActionResultAlert($categoryAdded, 'Category added successfully!', $categoryAddFailureMessage);

                $categoryEditFailureMessage = 'Failed to edit category. ' . ($categoryEditErrorMessage ?? 'Invalid data provided.');
                displayActionResultAlert($categoryEdited, 'Category edited successfully!', $categoryEditFailureMessage);

                displayActionResultAlert($categoryDeleted, 'Category deleted successfully!', 'Failed to delete category. An error occurred.');
            ?>

            <div class="categories-card card scroll-if-needed">
                <table class="card-body">
                    <thead>
                        <th class="py-2 px-3">ID</th>
                        <th class="py-2 px-3">Category Name</th>
                        <th class="py-2 px-3">Actions</th>
                    </thead>
                    <tbody>
                        <?php 
                            $categoryId = $categoryName = null;

                            foreach ($categories as $category) {
                                $categoryId = $category->getId();
                                $categoryName = $category->getName();
                                
                                echo 
                                    "<tr data-category-id='$categoryId' data-category-name='$categoryName'>
                                        <td class='py-2 px-3'>$categoryId</td>
                                        <td class='py-2 px-3'>$categoryName</td>
                                        <td class='py-2 px-3 d-flex gap-2'>
                                            <button class='action-btn edit-category-btn btn btn-primary rounded-circle d-flex align-items-center justify-content-center' data-bs-toggle='modal' data-bs-target='#editCategoryModal'>
                                                <img src='/assets/icons/edit.svg' alt='edit icon'>
                                            </button>
                                            <button class='action-btn delete-category-btn btn btn-primary rounded-circle d-flex align-items-center justify-content-center' data-bs-toggle='modal' data-bs-target='#deleteCategoryModal'>
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
            $categoryInputValidation = 'maxlength="50" pattern="[A-Za-z ]+" title="Category names must only contain alphabets along with optional spaces"';
        ?>

        <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="addCategoryModalLabel">Add Category</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addCategoryForm" class="row row-gap-3" action="/admin/medicine-categories/add" method="post">
                            <label class="col-12 d-flex flex-wrap gap-2" for="name">Category Name *
                                <input class="flex-grow-1 w-100" type="text" name="name" id="name" <?php echo $categoryInputValidation; ?> required>
                            </label>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" form="addCategoryForm" class="btn btn-primary">Add Category</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="editCategoryModalLabel">Edit Category</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editCategoryForm" class="row row-gap-3" action="/admin/medicine-categories/edit" method="post">
                            <input class="flex-grow-1 w-100" type="text" name="id" hidden>

                            <label class="col-12 d-flex flex-wrap gap-2" for="name">Category Name *
                                <input class="flex-grow-1 w-100" type="text" name="name" id="name" <?php echo $categoryInputValidation; ?> required>
                            </label>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" form="editCategoryForm" class="btn btn-primary">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="deleteCategoryModalLabel">Delete Category</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="deleteCategoryForm" class="row row-gap-3" action="/admin/medicine-categories/delete" method="post">
                            <p>Are you sure you want to delete "<span class="category-to-delete"></span>" category?</p>
                            <input class="flex-grow-1 w-100" type="text" name="id" hidden>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" form="deleteCategoryForm" class="btn btn-danger text-white">Delete Category</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/setEditCategoryData.js"></script>
    <script src="/assets/js/setDeleteCategoryData.js"></script>
</body>
</html>