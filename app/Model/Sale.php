<?php

class Sale {
    private $id;
    private $totalBill;
    private $cashierId;
    private $paymentMethod;
    private $date;

    private $db;

    public function __construct($dbConnection = null) {
        $this->db = $dbConnection;
    }

    public function getId() {
        return $this->id;
    }

    public function getTotalBill() {
        return $this->totalBill;
    }

    public function getCashierId() {
        return $this->cashierId;
    }

    public function getPaymentMethod() {
        return $this->paymentMethod;
    }

    public function getDate() {
        return $this->date;
    }

    public function getSales($resultSet) {
        $sales = [];

        if ($resultSet) {
            while ($sale = $resultSet->fetch_object('Sale')) {
                $sales[] = $sale;
            }
        }

        return $sales;
    }

    public function getRecentSales() {
        $result = $this->db->query('SELECT * FROM sales ORDER BY id DESC LIMIT 5;');

        return $this->getSales($result);
    }

    public function getAll() {
        $result = $this->db->query('SELECT * FROM sales ORDER BY id DESC');

        return $this->getSales($result);
    }

    public static function getSaleItems($saleId) {
        global $dbConnection;

        $statement = $dbConnection->prepare("SELECT * FROM sales_details WHERE saleId = ?");
        $statement = bindAndExecuteStatement($statement, 'i', [$saleId])['statement'];

        $saleItems = $statement->get_result()->fetch_all(MYSQLI_ASSOC);

        $medicineIdQuery = "SELECT medicineId FROM boxes WHERE id = ?";

        for ($i=0; $i < count($saleItems); $i++) { 
            $boxId = $saleItems[$i]['boxId'];

            $statement = $dbConnection->prepare($medicineIdQuery);
            $statement = bindAndExecuteStatement($statement, 'i', [$boxId])['statement'];

            $medicineId = $statement->get_result()->fetch_assoc()['medicineId'];

            $medicineName = Medicine::findMedicineName($medicineId);

            $saleItems[$i]['medicineName'] = $medicineName;
        }

        return $saleItems;
    }

    public function getTotalSales() {
        $sales = $this->db->query("SELECT COUNT(*) as salesCount FROM sales")->fetch_assoc();

        return $sales['salesCount'];
    }

    public function getRevenue() {
        /* COALESCE() returns the first non-NULL argument or NULL if no argument is non-NULL. 
        Needed since SUM() returns NULL if there's nothing to sum. */
        $revenue = $this->db->query("SELECT COALESCE(SUM(totalBill), 0) as revenue FROM sales")->fetch_assoc();

        return $revenue['revenue'];
    }

    public function getTotalPurchaseCost() {
        $saleDetails = $this->db->query("SELECT boxId, quantity FROM sales_details;")->fetch_all(MYSQLI_ASSOC);
        
        $boxes = MedicineBox::getBoxesWithTheirPurchasePrices();
        $cost = 0;

        foreach ($saleDetails as $detail) {
            $cost += $boxes[$detail['boxId']] * $detail['quantity'];
        }

        return $cost;
    }

    public static function getBoxesWithTheirSales($dbConnection) {
        $saleDetails = $dbConnection->query("SELECT * FROM sales_details");

        $boxes = [];

        while ($detail = $saleDetails->fetch_assoc()) {
            if (!isset($boxes[$detail['boxId']])) $boxes[$detail['boxId']] = $detail['quantity'];
            else $boxes[$detail['boxId']] += $detail['quantity'];           
        }

        return $boxes;
    }

    public function getEmployeeSales($username, $limit = null) {
        $employeeId = "SELECT id FROM employees WHERE username = ?";

        $query = "SELECT * FROM sales WHERE cashierId = ($employeeId) ORDER BY id DESC";

        $query .= $limit ? " LIMIT $limit;" : '';

        $statement = $this->db->prepare($query);
        $statement = bindAndExecuteStatement($statement, 's', [$username])['statement'];

        $result = $statement->get_result();

        return $this->getSales($result);
    }

    public function getSaleDate() {
        $date = getDate();
        [$year, $month, $day] = [$date['year'], $date['mon'], $date['mday']];

        if ($month < 10) {
            $month = '0' . (string)$month;
        }

        if ($day < 10) {
            $day = '0' . (string)$day;
        }

        $date = "$year-$month-$day"; 

        return $date;
    }

    public function addSaleItem($saleId, $boxId, $quantity, $unitPrice, $subTotal) {
        $statement = $this->db->prepare(
            "INSERT INTO sales_details 
            (saleId, boxId, quantity, unitPrice, subTotal) 
            VALUES 
            ($saleId, ?, ?, ?, ?);"
        );
        $status = bindAndExecuteStatement($statement, 'iidd', [$boxId, $quantity, $unitPrice, $subTotal])['status'];

        return $status;
    }

    public function addSaleItems($itemsCount, $saleId, $boxIds, $quantities, $unitPrices, $subTotals) {
        $status = false;
        
        for ($i=0; $i < $itemsCount; $i++) {
            $status = $this->addSaleItem($saleId, $boxIds[$i], $quantities[$i], $unitPrices[$i], $subTotals[$i]);
        }

        return $status;
    }

    public function getLastSaleId() {
        $id = $this->db->query(
            "SELECT MAX(id) FROM sales;"
        )->fetch_assoc()['MAX(id)']; 

        return $id;
    }

    public function addSale($totalBill, $cashierId, $paymentMethod, $date) {
        $statement = $this->db->prepare(
            "INSERT INTO sales
            (totalBill, cashierId, paymentMethod, date) 
            VALUES 
            (?, ?, ?, '$date');"
        );
        $status = bindAndExecuteStatement($statement, 'dis', [$totalBill, $cashierId, $paymentMethod])['status'];

        if ($status) {
            return $this->getLastSaleId();
        }

        return null;
    }

    public function add($data) {
        $boxIds = $data['boxId'];
        $unitPrices = $data['unitPrice'];
        $quantities = $data['quantity'];
        $paymentMethod = $data['paymentMethod'];
        $cashierId = $data['cashierId'];
        $saleDate = $this->getSaleDate(); 

        $saleItemsCount = count($boxIds);
        $subTotal = 0;
        $totalBill = 0;

        $subTotals = [];

        for ($i=0; $i < $saleItemsCount; $i++) { 
            $subTotal = +$quantities[$i] * +$unitPrices[$i];

            $subTotals[] = $subTotal;

            $totalBill += $subTotal;
            $subTotal = 0;
        }

        $stockUpdated = MedicineBox::updateStock($saleItemsCount, $boxIds, $quantities, $data['medicineName']);

        if (!$stockUpdated) return $stockUpdated;

        $saleId = $this->addSale($totalBill, $cashierId, $paymentMethod, $saleDate);
        
        $status = false;

        if ($saleId) {
            $status = $this->addSaleItems($saleItemsCount, $saleId, $boxIds, $quantities, $unitPrices, $subTotals);
        }

        return $status;
    }
}