function setUpSetEditStockBoxData(editStockBoxForm) {
    const idInput = editStockBoxForm.querySelector("[name='id']"),
        medicineInput = editStockBoxForm.querySelector("[name='medicineId']"),
        stockInput = editStockBoxForm.querySelector("[name='stock']"),
        expiryDateInput = editStockBoxForm.querySelector("[name='expiry']"),
        purchasePriceInput = editStockBoxForm.querySelector("[name='purchasePrice']"),
        sellingPriceInput = editStockBoxForm.querySelector("[name='sellingPrice']");

    document.addEventListener("click", function (event) {
        const editButton = event.target.closest(".edit-stock-box-btn");

        if (!editButton) return;

        const boxToEditDataset = editButton.closest("tr").dataset;

        setBoxId(boxToEditDataset);
        setMedicine(boxToEditDataset);
        setStock(boxToEditDataset);
        setExpiryDate(boxToEditDataset);
        setPurchasePrice(boxToEditDataset);
        setSellingPrice(boxToEditDataset);
    });

    function setBoxId(boxDataset) {
        const boxToEditId = boxDataset.boxId;

        idInput.value = boxToEditId;
    }

    function setMedicine(boxDataset) {
        const boxToEditMedicine = boxDataset.medicineId;

        medicineInput.value = boxToEditMedicine;
    }

    function setStock(boxDataset) {
        const boxToEditStock = boxDataset.stock;

        stockInput.value = boxToEditStock;
    }

    function setExpiryDate(boxDataset) {
        const boxToEditDate = boxDataset.expiryDate;

        expiryDateInput.value = boxToEditDate;
    }

    function setPurchasePrice(boxDataset) {
        const boxToEditPurchasePrice = boxDataset.purchasePrice;

        purchasePriceInput.value = boxToEditPurchasePrice;
    }

    function setSellingPrice(boxDataset) {
        const boxToEditSellingPrice = boxDataset.sellingPrice;

        sellingPriceInput.value = boxToEditSellingPrice;
    }
}

setUpSetEditStockBoxData(document.querySelector("#editMedicineBoxForm"));