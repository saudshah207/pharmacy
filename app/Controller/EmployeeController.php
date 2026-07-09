<?php

require __DIR__ . '/../Model/User.php';
require __DIR__ . '/AuthController.php';
require __DIR__ . '/../Model/Sale.php';
require __DIR__ . '/../Model/Medicine.php';
require __DIR__ . '/../Model/Employee.php';
require __DIR__ . '/../Model/MedicineBox.php';
require __DIR__ . '/../Model/Category.php';

class EmployeeController {
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
        AuthController::redirectIfNeeded('admin');

        $username = $_SESSION['user'];

        $recentEmployeeSales = $this->saleModel->getEmployeeSales($username, 5);
        $contributedRevenue = $this->employeeModel->getContributedRevenue($username);
        $totalSales = count($this->saleModel->getEmployeeSales($username));

        require __DIR__ . '/../Views/employee/employee.php';
    }

    public function sales() {
        AuthController::redirectIfNeeded('admin');

        $username = $_SESSION['user'];

        $sales = $this->saleModel->getEmployeeSales($username);
        $totalSales = count($sales);
        $contributedRevenue = $this->employeeModel->getContributedRevenue($username);

        require __DIR__ . '/../Views/employee/sales.php';
    }

    public function pos() {
        AuthController::redirectIfNeeded('admin');

        $username = $_SESSION['user'];

        $sales = $this->saleModel->getEmployeeSales($username);
        $totalSales = count($sales);
        $contributedRevenue = $this->employeeModel->getContributedRevenue($username);
        $medicineBoxes = $this->medicineBoxModel->getAll();

        $orderPlaced = $_SESSION['orderPlaced'] ?? null;
        $updateStockErrorMessages = $_SESSION['updateStockErrorMessages'] ?? null;

        unset($_SESSION['orderPlaced'], $_SESSION['updateStockErrorMessages']);
        
        require __DIR__ . '/../Views/employee/pos.php';
    }

    public function placeOrder() {
        AuthController::redirectIfNeeded('admin');

        $_SESSION['orderPlaced'] = $this->saleModel->add($_POST);

        header("location: /employee/pos", true, 302);
        exit;
    }
}