function setUpSetEditMedicineData(editMedicineForm) {
    const idInput = editMedicineForm.querySelector("[name='id']"),
        nameInput = editMedicineForm.querySelector("[name='name']"),
        categoryInput = editMedicineForm.querySelector("[name='category']"),
        minStockLevelInput = editMedicineForm.querySelector("[name='minStockLevel']");

    document.addEventListener("click", function (event) {
        const editButton = event.target.closest(".edit-medicine-btn");

        if (!editButton) return;

        const medicineToEditDataset = editButton.closest('tr').dataset;

        setMedicineId(medicineToEditDataset);
        setMedicineName(medicineToEditDataset);
        setMedicineCategory(medicineToEditDataset);
        setMedicineMinimumStockLevel(medicineToEditDataset);
    });

    function setMedicineId(medicineDataset) {
        const medicineToEditId = medicineDataset.medicineId;

        idInput.value = medicineToEditId;
    }

    function setMedicineName(medicineDataset) {
        const medicineToEdit = medicineDataset.medicineName;

        nameInput.value = medicineToEdit;
    }

    function setMedicineCategory(medicineDataset) {
        const medicineToEditCategory = medicineDataset.medicineCategory;

        categoryInput.value = medicineToEditCategory;
    }

    function setMedicineMinimumStockLevel(medicineDataset) {
        const medicineToEditMinimumStockLevel = medicineDataset.medicineMinStockLevel;

        minStockLevelInput.value = medicineToEditMinimumStockLevel;
    }
}

setUpSetEditMedicineData(document.querySelector('#editMedicineForm'));