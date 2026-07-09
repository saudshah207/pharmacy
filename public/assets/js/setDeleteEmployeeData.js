function setUpSetDeleteEmployeeData(employeeDeleteForm) {
    const nameElement = employeeDeleteForm.querySelector(".employee-to-delete"), 
        usernameInput = employeeDeleteForm.querySelector("[name='username']");

    document.addEventListener("click", function (event) {
        const deleteButton = event.target.closest(".delete-employee-btn");

        if (!deleteButton) return;

        const employeeToDeleteDataset = deleteButton.closest('tr').dataset;

        setName(employeeToDeleteDataset);
        setUsername(employeeToDeleteDataset);
    });

    function setName(employeeDataset) {
        const employeeToDeleteName = employeeDataset.employeeName;

        nameElement.textContent = employeeToDeleteName;
    }

    function setUsername(employeeDataset) {
        const employeeToDeleteUsername = employeeDataset.employeeUsername;

        usernameInput.value = employeeToDeleteUsername;
    }
}

setUpSetDeleteEmployeeData(document.querySelector("#deleteEmployeeForm"));