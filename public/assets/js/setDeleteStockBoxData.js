function setUpSetDeleteStockBoxData(deleteStockBoxModal) {
    const medicineNameElement = deleteStockBoxModal.querySelector(".medicine-to-delete"),
        boxIdFormInput = deleteStockBoxModal.querySelector("[name='medicineBoxToDeleteId']");

    document.addEventListener("click", function (event) {
        const deleteMedicineBoxButton = event.target.closest(".delete-stock-box-btn");

        if (!deleteMedicineBoxButton) return;

        const boxToDeleteDataset = deleteMedicineBoxButton.closest('tr').dataset;

        setMedicineName(boxToDeleteDataset);
        setMedicineBoxId(boxToDeleteDataset);
    });

    function setMedicineName(boxDataset) {
        const medicineBoxTodeleteName = boxDataset.medicineName;

        medicineNameElement.textContent = medicineBoxTodeleteName;
    }

    function setMedicineBoxId(boxDataset) {
        const medicineBoxTodeleteId = boxDataset.boxId;

        boxIdFormInput.value = medicineBoxTodeleteId;
    }
}

setUpSetDeleteStockBoxData(document.querySelector('#deleteMedicineBoxModal'));