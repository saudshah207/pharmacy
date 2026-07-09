function setUpSetDeleteCatgoryData(deleteCatgoryModal) {
    const nameElement = deleteCatgoryModal.querySelector(".category-to-delete"),
        idInput = deleteCatgoryModal.querySelector("[name='id']");

    document.addEventListener("click", function (event) {
        const deleteCategoryButton = event.target.closest(".delete-category-btn");

        if (!deleteCategoryButton) return;

        const categoryToDeleteDataset = deleteCategoryButton.closest('tr').dataset;

        setMedicineName(categoryToDeleteDataset);
        setCategoryId(categoryToDeleteDataset);
    });

    function setMedicineName(categoryDataset) {
        const categoryToDeleteName = categoryDataset.categoryName;

        nameElement.textContent = categoryToDeleteName;
    }

    function setCategoryId(categoryDataset) {
        const categoryToDeleteId = categoryDataset.categoryId;

        idInput.value = categoryToDeleteId;
    }
}

setUpSetDeleteCatgoryData(document.querySelector('#deleteCategoryModal'));