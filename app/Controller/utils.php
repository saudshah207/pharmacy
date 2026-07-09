<?php

function getAdminControllerModels() {
    global $dbConnection;

    $saleModel = new Sale($dbConnection);
    $medicineModel = new Medicine($dbConnection);
    $employeeModel = new Employee($dbConnection);
    $medicineBoxModel = new MedicineBox($dbConnection);
    $categoryModel = new Category($dbConnection);

    return [$saleModel, $medicineModel, $employeeModel, $medicineBoxModel, $categoryModel];
}

function getEmployeeControllerModels() {
    global $dbConnection;

    $saleModel = new Sale($dbConnection);
    $medicineModel = new Medicine($dbConnection);
    $employeeModel = new Employee($dbConnection);
    $medicineBoxModel = new MedicineBox($dbConnection);
    $categoryModel = new Category($dbConnection);

    return [$saleModel, $medicineModel, $employeeModel, $medicineBoxModel, $categoryModel];
}