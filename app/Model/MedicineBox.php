<?php

class MedicineBox {
    private $id;
    private $medicineId;
    private $stock;
    private $expiryDate;
    private $purchasePrice;
    private $sellingPrice;
    private $isActive;

    private $db;

    public function __construct($dbConnection = null) {
        $this->db = $dbConnection;
    }

    public function getId() {
        return $this->id;
    }

    public function getMedicineId() {
        return $this->medicineId;
    }

    public function setMedicineId($medicineId) {
        if ($medicineId < 1) return false;

        $this->medicineId = $medicineId;

        return true;
    }

    public function getStock() {
        return $this->stock;
    }

    public function setStock($stock) {
        if ($stock < 0) return false;

        $this->stock = $stock;

        return true;
    }

    public function getExpiryDate() {
        return $this->expiryDate;
    }

    public function setExpiryDate($date) {
        [$year, $month, $day] = explode('-', $date);

        $year = (int)$year;
        $month = (int)$month;
        $day = (int)$day;
        
        $currentDate = getDate();
        
        if (($currentDate['year'] > $year) || 
        ($currentDate['year'] === $year && $currentDate['mon'] > $month) || 
        ($currentDate['year'] === $year && $currentDate['mon'] === $month && $currentDate['mday'] > $day)) {
            return false;
        }

        $this->expiryDate = $date;

        return true;
    }

    public function getPurchasePrice() {
        return $this->purchasePrice;
    }

    public function setPurchasePrice($price) {
        if ($price <= 0) return false;

        $this->purchasePrice = $price;

        return true;
    }

    public function getSellingPrice() {
        return $this->sellingPrice;
    }

    public function setSellingPrice($price) {
        if ($price <= 0) return false;

        $this->sellingPrice = $price;

        return true;
    }

    public static function AreThereCorrespondingBoxes($medicineId) {
        global $dbConnection;

        $statement = $dbConnection->prepare("SELECT COUNT(*) as boxesCount FROM boxes WHERE medicineId = ? AND isActive = 1");
        $statement = bindAndExecuteStatement($statement, 'i', [$medicineId])['statement'];
        
        $boxesCount = $statement->get_result()->fetch_assoc()['boxesCount']; 

        return $boxesCount > 0;
    }

    public static function getBoxesWithTheirPurchasePrices() {
        global $dbConnection;

        $result = $dbConnection->query("SELECT id, purchasePrice FROM boxes;");

        $boxes = [];

        if ($result) {
            while ($box = $result->fetch_assoc()) {
                $boxes[$box['id']] = $box['purchasePrice']; 
            }
        }

        return $boxes;
    } 

    public static function isStockEnough($boxesCount, $boxIds, $quantities, $medicineNames) {
        global $dbConnection;
        
        $stockSufficient = true;
        
        for ($i = 0; $i < $boxesCount; $i++) {
            $statement = $dbConnection->prepare("SELECT stock FROM boxes WHERE id = ?;");
            $statement = bindAndExecuteStatement($statement, 'i', [$boxIds[$i]])['statement'];

            $stock = $statement->get_result()->fetch_assoc()['stock'];

            if ($stock < $quantities[$i]) {
                $_SESSION['updateStockErrorMessages'][] = "Insufficient stock for $medicineNames[$i].";

                $stockSufficient = false;
            }
        }

        return $stockSufficient;
    }

    public static function updateStock($boxesCount, $boxIds, $quantities, $medicineNames) {        
        $status = MedicineBox::isStockEnough($boxesCount, $boxIds, $quantities, $medicineNames);

        if (!$status) return $status;

        global $dbConnection;
        
        for ($i = 0; $i < $boxesCount; $i++) {
            $statement = $dbConnection->prepare("UPDATE boxes SET stock = stock - ? WHERE id = ?;");
            $status = bindAndExecuteStatement($statement, 'ii', [$quantities[$i], $boxIds[$i]]);
        }

        return $status;
    }

    public function getAll() {
        $result = $this->db->query("SELECT * FROM boxes WHERE isActive = 1;");

        $boxes = [];

        if ($result) {
            while ($box = $result->fetch_object('MedicineBox')) {
                $boxes[] = $box;
            }
        }

        return $boxes;
    } 

    public function add($data) {
        $medicineId = $data['medicineId'];
        $stock = $data['stock'];
        $expiry = $data['expiry'];
        $purchasePrice = $data['purchasePrice'];
        $sellingPrice = $data['sellingPrice'];
        
        $medicineBox = new MedicineBox();

        if ($medicineBox->setMedicineId($medicineId) && $medicineBox->setStock($stock) && $medicineBox->setExpiryDate($expiry) && $medicineBox->setPurchasePrice($purchasePrice) && $medicineBox->setSellingPrice($sellingPrice)) {
            $statement = $this->db->prepare(
                "INSERT INTO boxes 
                (medicineId, stock, expiryDate, purchasePrice, sellingPrice) 
                VALUES 
                (?, ?, ?, ?, ?);"
            );
            $status = bindAndExecuteStatement($statement, 'iisdd', [$medicineId, $stock, $expiry, $purchasePrice, $sellingPrice])['status'];

            return $status;
        }

        return false;
    }

    public function edit($data) {
        $id = $data['id'];
        $medicineId = $data['medicineId'];
        $stock = $data['stock'];
        $expiry = $data['expiry'];
        $purchasePrice = $data['purchasePrice'];
        $sellingPrice = $data['sellingPrice'];
        
        $medicineBox = new MedicineBox();

        if ($medicineBox->setMedicineId($medicineId) && $medicineBox->setStock($stock) && $medicineBox->setExpiryDate($expiry) && $medicineBox->setPurchasePrice($purchasePrice) && $medicineBox->setSellingPrice($sellingPrice)) {
            $statement = $this->db->prepare(
                "UPDATE boxes SET 
                medicineId = ?, 
                stock = ?, 
                expiryDate = ?, 
                purchasePrice = ?, 
                sellingPrice = ? 
                WHERE id = ?"
            );
            $status = bindAndExecuteStatement($statement, 'iisddi', [$medicineId, $stock, $expiry, $purchasePrice, $sellingPrice, $id])['status'];

            return $status;
        }

        return false;
    }

    public function delete($data) {
        $id = $data['medicineBoxToDeleteId'];

        $statement = $this->db->prepare("UPDATE boxes SET isActive = 0 WHERE id = ?");
        $status = bindAndExecuteStatement($statement, 'i', [$id])['status'];

        return $status;
    }
}