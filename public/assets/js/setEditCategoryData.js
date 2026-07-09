function setUpSetEditCategoryBoxData(editStockBoxForm) {
    const idInput = editStockBoxForm.querySelector("[name='id']"),
        nameInput = editStockBoxForm.querySelector("[name='name']");

    document.addEventListener("click", function (event) {
        const editButton = event.target.closest(".edit-category-btn");

        if (!editButton) return;

        const categoryToEditDataset = editButton.closest("tr").dataset;

        setId(categoryToEditDataset);
        setName(categoryToEditDataset);
    });

    function setId(categoryDataset) {
        const categoryToEditId = categoryDataset.categoryId;

        idInput.value = categoryToEditId;
    }

    function setName(categoryDataset) {
        const categoryToEditName = categoryDataset.categoryName;

        nameInput.value = categoryToEditName;
    }
}

setUpSetEditCategoryBoxData(document.querySelector("#editCategoryForm"));