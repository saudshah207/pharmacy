<?php

require __DIR__ . '/../Model/User.php';
require __DIR__ . '/../Model/Sale.php';
require __DIR__ . '/../Model/Medicine.php';
require __DIR__ . '/../Model/Employee.php';
require __DIR__ . '/../Model/MedicineBox.php';
require __DIR__ . '/../Model/Category.php';
require __DIR__ . '/AuthController.php';

class AdminController {
    private $saleModel;
    private $medicineModel;
    private $employeeModel;
    private $medicineBoxModel;
    private $categoryModel;

    public function __construct($saleModel, $medicineModel, $employeeModel, $medicineBoxModel, $categoryModel) {
        $this->saleModel = $saleModel;
        $this->medicineModel = $medicineModel;
        $this->employeeModel = $employeeModel;
        $this->medicineBoxModel = $medicineBoxModel;
        $this->categoryModel = $categoryModel;
    } 

    public function index() {
        AuthController::redirectIfNeeded('employee');

        $username = $_SESSION['user'];
        $totalSales = $this->saleModel->getTotalSales();
        $revenue = $this->saleModel->getRevenue();
        $totalCost = $this->saleModel->getTotalPurchaseCost();
        $profit = $revenue - $totalCost;
        $recentSales = $this->saleModel->getRecentSales();
        [$mostSoldMedicine, $mostSalesCount] = $this->medicineModel->getMostSoldMedicine();

        require __DIR__ . '/../Views/admin/admin.php';
    }

    public function sales() {
        AuthController::redirectIfNeeded('employee');

        $totalSales = $this->saleModel->getTotalSales();
        $revenue = $this->saleModel->getRevenue();
        $sales = $this->saleModel->getAll();
        [$mostSoldMedicine, $mostSalesCount] = $this->medicineModel->getMostSoldMedicine();

        require __DIR__ . '/../Views/admin/sales.php';
    }

    public function medicines() {
        AuthController::redirectIfNeeded('employee');

        $medicines = $this->medicineModel->getAll();
        $categories = $this->categoryModel->getAll();

        $medicineAdded = $_SESSION['medicineAdded'] ?? null;
        $medicineEdited = $_SESSION['medicineEdited'] ?? null;
        $medicineDeleted = $_SESSION['medicineDeleted'] ?? null;

        unset($_SESSION['medicineAdded'], $_SESSION['medicineEdited'], $_SESSION['medicineDeleted']);

        require __DIR__ . '/../Views/admin/medicines.php';
    }

    public function addMedicine() {
        AuthController::redirectIfNeeded('employee');

        $_SESSION['medicineAdded'] = $this->medicineModel->add($_POST);
        
        header("location: /admin/medicines", true, 302);
        exit;
    }
    
    public function deleteMedicine() {
        AuthController::redirectIfNeeded('employee');

        $_SESSION['medicineDeleted'] = $this->medicineModel->delete($_POST);

        header("location: /admin/medicines", true, 302);
        exit;
    }
    
    public function editMedicine() {
        AuthController::redirectIfNeeded('employee');

        $_SESSION['medicineEdited'] = $this->medicineModel->edit($_POST);

        header("location: /admin/medicines", true, 302);
        exit;
    }

    public function employees() {
        AuthController::redirectIfNeeded('employee');

        $employees = $this->employeeModel->getAll();

        $employeeAdded = $_SESSION['employeeAdded'] ?? null;
        $duplicateUsernameErrorMessage = $_SESSION['employeeAddDuplicateUsernameErrorMessage'] ?? null;
        $duplicateEmailErrorMessage = $_SESSION['employeeAddDuplicateEmailErrorMessage'] ?? null;
        $employeeDeleted = $_SESSION['employeeDeleted'] ?? null;

        unset($_SESSION['employeeAdded'], $_SESSION['employeeAddDuplicateUsernameErrorMessage'], $_SESSION['employeeAddDuplicateEmailErrorMessage'], $_SESSION['employeeDeleted']);

        require __DIR__ . '/../Views/admin/employees.php';
    }

    public function addEmployee() {
        AuthController::redirectIfNeeded('employee');

        $_SESSION['employeeAdded'] = $this->employeeModel->add($_POST);

        header("location: /admin/employees", true, 302);
        exit;
    }

    public function deleteEmployee() {
        AuthController::redirectIfNeeded('employee');

        $_SESSION['employeeDeleted'] = $this->employeeModel->delete($_POST);

        header("location: /admin/employees", true, 302);
        exit;
    }

    public function stock() {
        AuthController::redirectIfNeeded('employee');

        $medicineBoxes = $this->medicineBoxModel->getAll();
        $medicines = $this->medicineModel->getAll();

        $medicineBoxAdded = $_SESSION['medicineBoxAdded'] ?? null;
        $medicineBoxEdited = $_SESSION['medicineBoxEdited'] ?? null;
        $medicineBoxDeleted = $_SESSION['medicineBoxDeleted'] ?? null;

        unset($_SESSION['medicineBoxAdded'], $_SESSION['medicineBoxEdited'], $_SESSION['medicineBoxDeleted']);

        require __DIR__ . '/../Views/admin/stock.php';
    }

    public function addStock() {
        AuthController::redirectIfNeeded('employee');

        $_SESSION['medicineBoxAdded'] = $this->medicineBoxModel->add($_POST);

        header("location: /admin/stock", true, 302);
        exit;
    }

    public function editStock() {
        AuthController::redirectIfNeeded('employee');

        $_SESSION['medicineBoxEdited'] = $this->medicineBoxModel->edit($_POST);

        header("location: /admin/stock", true, 302);
        exit;
    }

    public function deleteStock() {
        AuthController::redirectIfNeeded('employee');

        $_SESSION['medicineBoxDeleted'] = $this->medicineBoxModel->delete($_POST);

        header("location: /admin/stock", true, 302);
        exit;
    }

    public function categories() {
        AuthController::redirectIfNeeded('employee');

        $categories = $this->categoryModel->getAll();

        $categoryAdded = $_SESSION['categoryAdded'] ?? null;
        $categoryAddErrorMessage = $_SESSION['categoryAddErrorMessage'] ?? null;
        $categoryEdited = $_SESSION['categoryEdited'] ?? null;
        $categoryEditErrorMessage = $_SESSION['categoryEditErrorMessage'] ?? null;
        $categoryDeleted = $_SESSION['categoryDeleted'] ?? null;

        unset($_SESSION['categoryAdded'], $_SESSION['categoryAddErrorMessage'], $_SESSION['categoryEdited'], $_SESSION['categoryEditErrorMessage'], $_SESSION['categoryDeleted']);

        require __DIR__ . '/../Views/admin/categories.php';
    }

    public function addCategory() {
        AuthController::redirectIfNeeded('employee');

        $_SESSION['categoryAdded'] = $this->categoryModel->add($_POST);

        header("location: /admin/medicine-categories", true, 302);
        exit;
    }

    public function editCategory() {
        AuthController::redirectIfNeeded('employee');

        $_SESSION['categoryEdited'] = $this->categoryModel->edit($_POST);

        header("location: /admin/medicine-categories", true, 302);
        exit;
    }

    public function deleteCategory() {
        AuthController::redirectIfNeeded('employee');

        $_SESSION['categoryDeleted'] = $this->categoryModel->delete($_POST);

        header("location: /admin/medicine-categories", true, 302);
        exit;
    }
}