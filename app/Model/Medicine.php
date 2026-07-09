<?php 

class Medicine {
    private $id;
    private $name;
    private $categoryId;
    private $minStockLevel;
    private $isActive;

    private $db; 
    
    function __construct($dbConnection = null) {
        $this->db = $dbConnection;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $name = trim($name);

        $maxLength = 100;

        if ($name === '' || strlen($name) > $maxLength) return false;

        // '+' (1 or more characters) is required otherwise only a single allowed character will match 
        if (preg_match('/^[A-Za-z0-9 ]+$/', $name) === 1) {
            $this->name = $name;

            return true;
        }

        return false;
    }

    public function getCategoryId() {
        return $this->categoryId;
    }

    public function setCategoryId($categoryId) {
        if ($categoryId < 1) return false;

        $this->categoryId = $categoryId;

        return true;
    }

    public function getMinimumStockLevel() {
        return $this->minStockLevel;
    }

    public function setMinimumStockLevel($minStockLevel) {
        if ($minStockLevel < 1) return false;
        
        $this->minStockLevel = $minStockLevel;

        return true;
    }

    public function getMostSoldMedicine() {
        $boxes = Sale::getBoxesWithTheirSales($this->db);

        $mostSales = 0; 
        $mostSellingBoxId;
        foreach ($boxes as $id => $salesCount) {
            if ($salesCount > $mostSales) {
                $mostSales = $salesCount;
                $mostSellingBoxId = $id;
            }
        }

        $mostSoldMedicine = $this->db->query("SELECT name FROM medicines WHERE id = (
            SELECT medicineId FROM boxes WHERE id = $mostSellingBoxId
        )")->fetch_assoc()['name'];

        return [$mostSoldMedicine, $mostSales];
    }

    public function getAll() {
        $result = $this->db->query("SELECT * FROM medicines WHERE isActive = 1");

        $medicines = [];

        if ($result) {
            while ($medicine = $result->fetch_object('Medicine')) {
                $medicines[] = $medicine;
            }
        }

        return $medicines;
    }

    public static function findMedicineName($medicineId) {
        global $dbConnection;

        $statement = $dbConnection->prepare("SELECT name FROM medicines WHERE id = ?");
        $statement = bindAndExecuteStatement($statement, 'i', [$medicineId])['statement'];

        $name = $statement->get_result()->fetch_assoc()['name'];
    
        return $name;
    }

    public static function findMinimumStockLevel($medicineId) {
        global $dbConnection;

        $statement = $dbConnection->prepare("SELECT minStockLevel FROM medicines WHERE id = ?");
        $statement = bindAndExecuteStatement($statement, 'i', [$medicineId])['statement'];

        $minStockLevel = $statement->get_result()->fetch_assoc()['minStockLevel'];
    
        return $minStockLevel;
    } 

    public function add($data) {
        $name = $data['name'];
        $category = $data['category'];
        $minStockLevel = $data['minStockLevel'];

        $medicine = new Medicine();

        $categoryId = Category::findCategoryId($category);

        if ($medicine->setName($name) && $medicine->setCategoryId($categoryId) && $medicine->setMinimumStockLevel($minStockLevel)) {
            $statement = $this->db->prepare("INSERT INTO medicines (name, categoryId, minStockLevel) VALUES (?, $categoryId, ?)");
            $status = bindAndExecuteStatement($statement, 'si', [$name, $minStockLevel])['status'];

            return $status;
        }

        return false; 
    }

    public function delete($data) {
        $id = $data['medicineToDeleteId'];

        $doCorrespondingMedicineBoxesExist = MedicineBox::AreThereCorrespondingBoxes($id);

        if (!$doCorrespondingMedicineBoxesExist) {
            $statement = $this->db->prepare("UPDATE medicines SET isActive = 0 WHERE id = ?");
            $status = bindAndExecuteStatement($statement, 'i', [$id])['status'];

            return $status;
        }

        return false;
    }

    public function edit($data) {
        $id = $data['id'];
        $name = $data['name'];
        $category = $data['category'];
        $minStockLevel = $data['minStockLevel'];

        $medicine = new Medicine();

        $categoryId = Category::findCategoryId($category);

        if ($medicine->setName($name) && $medicine->setCategoryId($categoryId) && $medicine->setMinimumStockLevel($minStockLevel)) {
            $statement = $this->db->prepare("UPDATE medicines SET name = ?, categoryId = $categoryId, minStockLevel = ? WHERE id = ?");
            $status = bindAndExecuteStatement($statement, 'sii', [$name, $minStockLevel, $id])['status'];

            return $status;
        }

        return false;
    }
}