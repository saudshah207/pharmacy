function setUpSetDeleteMedicineData(deleteMedicineModal) {
    const medicineNameElement = deleteMedicineModal.querySelector(".medicine-to-delete"),
        medicineIdFormInput = deleteMedicineModal.querySelector("[name='medicineToDeleteId']");

    document.addEventListener("click", setMedicineName);
    document.addEventListener("click", setDeleteMedicineId);

    function setMedicineName(event) {
        const deleteMedicineButton = event.target.closest(".delete-medicine-btn");

        if (!deleteMedicineButton) return;

        const medicineTodelete = deleteMedicineButton.closest('tr').dataset.medicineName;

        medicineNameElement.textContent = medicineTodelete;
    }

    function setDeleteMedicineId(event) {
        const deleteMedicineButton = event.target.closest(".delete-medicine-btn");

        if (!deleteMedicineButton) return;

        const medicineTodeleteId = deleteMedicineButton.closest('tr').dataset.medicineId;

        medicineIdFormInput.value = medicineTodeleteId;
    }
}

setUpSetDeleteMedicineData(document.querySelector('#deleteMedicineModal'));