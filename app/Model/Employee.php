<?php

class Employee {
    private $id;
    private $username;
    private $firstName;
    private $lastName;
    private $gender;
    private $dateOfBirth;
    private $phoneNumber;
    private $email;
    private $designationId;

    private $db;

    public function __construct($dbConnection = null) {
        $this->db = $dbConnection;
    }

    public function getId() {
        return $this->id;
    } 

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $username = trim($username);
        
        $maxLength = 30;

        if (strlen($username) > $maxLength || $username === '') return false;

        if (preg_match('/^[\w$]+$/', $username) === 1) {
            $this->username = $username;

            return true;
        }

        return false;
    }

    public function getName() {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function setFirstName($firstName) {
        $firstName = trim($firstName);
        
        $maxLength = 20;

        if (strlen($firstName) > $maxLength || $firstName === '') return false;

        if (preg_match('/^[A-Za-z]+$/', $firstName) === 1) {
            $this->firstName = $firstName;

            return true;
        }

        return false;
    }

    public function setLastName($lastName) {
        $lastName = trim($lastName);
        
        $maxLength = 20;

        if (strlen($lastName) > $maxLength || $lastName === '') return false;

        if (preg_match('/^[A-Za-z]+$/', $lastName) === 1) {
            $this->lastName = $lastName;

            return true;
        }

        return false;
    }

    public function getGender() {
        if ($this->gender === 'm') return 'male';
        
        if ($this->gender === 'f') return 'female';

        return $this->gender;
    }

    public function setGender($gender) {
        $validGenders = ['m', 'f', 'other'];

        foreach ($validGenders as $validGender) {
            if ($gender === $validGender) {
                $this->gender = $gender;
                return true;
            }
        }

        return false;
    }

    public function getAge() {
        $age;

        [$year, $month, $day] = explode('-', $this->dateOfBirth);
        [$year, $month, $day] = [(int)$year, (int)$month, (int)$day];

        $currentDate = getDate();
        [$currentYear, $currentMonth, $currentDay] = [$currentDate['year'], $currentDate['mon'], $currentDate['mday']];

        if ($currentMonth > $month || ($currentMonth === $month && $currentDay >= $day))
            $age = $currentYear - $year;
        else if ($currentYear !== $year)
            $age = $currentYear - $year - 1;
        else 
            $age = 0;

        return $age;
    }

    public function setDateOfBirth($dateOfBirth) {
        $currentDate = getdate();
        $currentDateString = $currentDate['year'] . '-' . $currentDate['mon'] . '-' . $currentDate['mday'];
        $currentDate = new DateTime($currentDateString);

        $dateOfBirth = new DateTime($dateOfBirth);

        if ($dateOfBirth >= $currentDate) return false;

        $this->dateOfBirth = $dateOfBirth;

        return true;
    }

    public function getPhoneNumber() {
        return isset($this->phoneNumber) ? $this->phoneNumber : 'N/A';
    }

    public function setPhoneNumber($phoneNumber) {
        $phoneNumber = $phoneNumber === '' ? null : $phoneNumber;

        if ($phoneNumber === null || preg_match('/(:?(:?\+92 ?)|0)\d{3}[ ]?\d{7}/', $phoneNumber) === 1) {
            $this->phoneNumber = $phoneNumber;

            return true;
        }

        return false;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->email = $email;

            return true;
        }

        return false;
    }

    public static function findEmployeeUsername($employeeId) {
        global $dbConnection;

        $statement = $dbConnection->prepare("SELECT username FROM employees WHERE id = ?");
        $statement = bindAndExecuteStatement($statement, 'i', [$employeeId])['statement'];

        $username = $statement->get_result()->fetch_assoc()['username'];

        return $username;
    }

    public static function findEmployeeId($employeeUsername) {
        global $dbConnection;

        $statement = $dbConnection->prepare("SELECT id FROM employees WHERE username = ?");
        $statement = bindAndExecuteStatement($statement, 's', [$employeeUsername])['statement'];

        $id = $statement->get_result()->fetch_assoc()['id'];

        return $id;
    }

    public static function doesEmailAlreadyExist($email) {
        global $dbConnection;

        $statement = $dbConnection->prepare("SELECT COUNT(*) as emailOccurrences FROM employees WHERE email = ?");
        $statement = bindAndExecuteStatement($statement, 's', [$email])['statement'];

        $emailOccurrences = $statement->get_result()->fetch_assoc()['emailOccurrences'];

        return $emailOccurrences > 0;
    }

    public static function doesUsernameAlreadyExist($username) {
        global $dbConnection;

        $statement = $dbConnection->prepare("SELECT COUNT(*) as usernameOccurrences FROM employees WHERE username = ?");
        $statement = bindAndExecuteStatement($statement, 's', [$username])['statement'];

        $usernameOccurrences = $statement->get_result()->fetch_assoc()['usernameOccurrences'];

        return $usernameOccurrences > 0;
    }

    public function getAll() {
        $employeeRoleId = 2;
        $activeEmployeesUsernames = $this->db->query("SELECT username FROM users WHERE roleId = $employeeRoleId AND isActive = 1")->fetch_all(MYSQLI_ASSOC);
        
        $employeesResult = $this->db->query("SELECT * FROM employees");

        $employees = [];

        if ($employeesResult) {
            while ($employee = $employeesResult->fetch_object('Employee')) {
                foreach ($activeEmployeesUsernames as $username) {
                    if ($employee->getUsername() === $username['username'])
                        $employees[] = $employee;
                }
            }
        }

        return $employees;
    }

    public function add($data) {
        $username = $data['username'];
        $password = $data['password'];
        $firstName = $data['firstName']; 
        $lastName = $data['lastName']; 
        $gender = $data['gender']; 
        $dateOfBirth = $data['dateOfBirth']; 
        $phoneNumber = $data['phoneNumber']; 
        $email = $data['email'];

        $employee = new Employee();

        if ($employee->setUsername($username) && $employee->setFirstName($firstName) 
        && $employee->setLastName($lastName) && $employee->setGender($gender) 
        && $employee->setDateOfBirth($dateOfBirth) && $employee->setPhoneNumber($phoneNumber) 
        && $employee->setEmail($email)) {
            $status = false;

            $doesEmailAlreadyExist = Employee::doesEmailAlreadyExist($email);

            if ($doesEmailAlreadyExist) {
                $_SESSION['employeeAddDuplicateEmailErrorMessage'] = "Emails must be unique! Email \"$email\" is already taken.";

                if (Employee::doesUsernameAlreadyExist($username)) 
                    $_SESSION['employeeAddDuplicateUsernameErrorMessage'] = "Usernames must be unique! Username \"$username\" is already taken.";
            
                return $status;
            }

            try {
                $statement = $this->db->prepare(
                    "INSERT INTO users 
                    (username, password, roleId) 
                    VALUES 
                    (?, ?, 2)"
                );

                $password = password_hash($password, PASSWORD_DEFAULT);

                $status = bindAndExecuteStatement($statement, 'ss', [$username, $password])['status']; 
            } catch (Exception $e) {
                $_SESSION['employeeAddDuplicateUsernameErrorMessage'] = "Usernames must be unique! Username \"$username\" is already taken.";
            }

            if (!$status) return $status;

            $status = false;

            try {
                $employeeColumnsToInsertInto = $phoneNumber ? 
                    '(username, firstName, lastName, gender, dateOfBirth, phoneNumber, email)' : 
                    '(username, firstName, lastName, gender, dateOfBirth, email)';

                $employeeValuesPlaceholder = $phoneNumber ? 
                    "(?, ?, ?, ?, ?, ?, ?)" : 
                    "(?, ?, ?, ?, ?, ?)";

                $employeeValuesToInsert = $phoneNumber ? 
                    [$username, $firstName, $lastName, $gender, $dateOfBirth, $phoneNumber, $email] : 
                    [$username, $firstName, $lastName, $gender, $dateOfBirth, $email];

                $employeeValueTypes = $phoneNumber ? 
                    'sssssss' :
                    'ssssss';

                $statement = $this->db->prepare(
                    "INSERT INTO employees 
                    $employeeColumnsToInsertInto 
                    VALUES 
                    $employeeValuesPlaceholder;"
                );
                $status = bindAndExecuteStatement($statement, $employeeValueTypes, $employeeValuesToInsert)['status'];
            } catch (Exception $e) {
                $_SESSION['employeeAddDuplicateEmailErrorMessage'] = "Emails must be unique! Email \"$email\" is already taken.";
            }

            return $status;
        }

        return false;
    }

    public function delete($data) {
        $username = $data['username'];

        $statement = $this->db->prepare("UPDATE users SET isActive = 0 WHERE username = ?");
        $status = bindAndExecuteStatement($statement, 's', [$username])['status'];

        return $status;
    }

    public function getContributedRevenue($username) {
        $statement = $this->db->prepare("SELECT id FROM employees WHERE username = ?;");
        $statement = bindAndExecuteStatement($statement, 's', [$username])['statement'];

        $employeeId = $statement->get_result()->fetch_assoc()['id'];

        $revenue = $this->db->query("SELECT COALESCE(SUM(totalBill), 0) as revenue FROM sales WHERE cashierId = $employeeId;")->fetch_assoc()['revenue'];

        return $revenue;
    }
}