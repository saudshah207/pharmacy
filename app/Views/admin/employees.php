<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>Employees</title>

    <style>
        .password-display-toggle {
            bottom: 0.05rem;
            right: 0.85rem;

            background-color: #ffffff;
            border: none;
        }
    </style>
</head>
<body class="container-xxl d-flex flex-column">
    <div class="dashboard flex-grow-1 row">
        <?php require __DIR__ . '/partials/sidebar.php' ?>
        
        <main class="flex-grow-1 col-lg-10 col-12 p-3">
            <?php require __DIR__ . '/../partials/sidebarToggle.php'; ?>

            <div class="mb-4 row align-items-center justify-content-between gap-3 m-0">
                <h1 class="mb-0 col-8 p-0">All Cashiers</h1>

                <button class="btn btn-primary col-3 w-max-content d-flex align-items-center justify-content-center gap-2 me-xl-0 me-5" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                    Add New Cashier

                    <img src="/assets/icons/add.svg" alt="plus icon">
                </button>
            </div>

            <?php
                require __DIR__ . '/../utils/displayActionResultAlert.php';

                $employeeAddFailureMessage = 'Failed to add employee. ';
                if ($duplicateUsernameErrorMessage)
                    $employeeAddFailureMessage .= '<br />' . $duplicateUsernameErrorMessage;

                if ($duplicateEmailErrorMessage)
                    $employeeAddFailureMessage .= '<br />' . $duplicateEmailErrorMessage;

                if (!$duplicateUsernameErrorMessage && !$duplicateEmailErrorMessage) 
                    $employeeAddFailureMessage .= 'Invalid data provided.';
                displayActionResultAlert($employeeAdded, 'Employee added successfully!', $employeeAddFailureMessage);

                displayActionResultAlert($employeeDeleted, 'Employee deleted successfully!', 'Failed to delete employee. An error occurred.');
            ?>

            <?php
                if (count($employees) === 0) {
                    echo '<p>
                            No cashiers exist.
                        </p>';

                    return;
                }
            ?>

            <div class="card scroll-if-needed">
                <table class="card-body">
                    <thead>
                        <th class="py-2 px-3">ID</th>
                        <th class="py-2 px-3">Username</th>
                        <th class="py-2 px-3">Name</th>
                        <th class="py-2 px-3">Gender</th>
                        <th class="py-2 px-3">Age</th>
                        <th class="py-2 px-3">Phone Number</th>
                        <th class="py-2 px-3">Email</th>
                        <th class="py-2 px-3">Actions</th>
                    </thead>
                    <tbody>
                        <?php 
                            $employeeId = $employeeUsername = $employeeName = $employeeGender = $employeeAge = $employeePhoneNumber = $employeeEmail = null;

                            foreach ($employees as $employee) {
                                $employeeId = $employee->getId();
                                $employeeUsername = $employee->getUsername();
                                $employeeName = $employee->getName();
                                $employeeGender = $employee->getGender();
                                $employeeAge = $employee->getAge();
                                $employeePhoneNumber = $employee->getPhoneNumber();
                                $employeeEmail = $employee->getEmail();

                                echo 
                                    "<tr data-employee-username='$employeeUsername' data-employee-name='$employeeName'>
                                        <td class='py-2 px-3'>$employeeId</td>
                                        <td class='py-2 px-3'>$employeeUsername</td>
                                        <td class='py-2 px-3'>$employeeName</td>
                                        <td class='py-2 px-3'>$employeeGender</td>
                                        <td class='py-2 px-3'>$employeeAge</td>
                                        <td class='py-2 px-3'>$employeePhoneNumber</td>
                                        <td class='py-2 px-3'>$employeeEmail</td>
                                        <td class='py-2 px-3 d-flex gap-2'>
                                            <button class='action-btn delete-employee-btn btn btn-primary rounded-circle d-flex align-items-center justify-content-center' data-bs-toggle='modal' data-bs-target='#deleteEmployeeModal'>
                                                <img src='/assets/icons/delete.svg' alt='delete icon'>
                                            </button>
                                        </td>
                                    </tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>

        <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="addEmployeeModalLabel">Add Employee</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addEmployeeForm" class="row row-gap-3" action="/admin/employees/add" method="post">
                            <label class="col-lg-6 col-12 d-flex flex-wrap gap-2" for="username">Username *
                                <input class="flex-grow-1 w-100" type="text" name="username" id="username" minlength="2" maxlength="30" pattern="[\w$]{2,}" title="Usernames must be 2 to 30 characters long and can only contain alphabets, numbers and the symbols _ and $." required>
                            </label>
                            
                            <label class="col-lg-6 col-12 d-flex flex-wrap gap-2 position-relative" for="password">Password *
                                <input class="flex-grow-1 w-100" type="password" name="password" id="password" minlength="8" maxlength="35" pattern="[\w@$#]{8,}" title="Passwords must be 8 to 35 characters long and can only contain alphabets, numbers and the symbols _,@,$,#." required>

                                <button type="button" class="d-flex align-items-center justify-content-center position-absolute password-display-toggle">
                                    <img src="/assets/icons/visibility.svg" alt="toggle password display icon">
                                </button>
                            </label>

                            <label class="col-lg-6 col-12 d-flex flex-wrap gap-2" for="firstName">First Name *
                                <input class="flex-grow-1 w-100" type="text" name="firstName" id="firstName" minlength="1" maxlength="20" pattern="[A-Za-z]{1,}" title="Names must only contain alphabets." required>
                            </label>

                            <label class="col-lg-6 col-12 d-flex flex-wrap gap-2" for="lastName">Last Name *
                                <input class="flex-grow-1 w-100" type="text" name="lastName" id="lastName" minlength="1" maxlength="20" pattern="[A-Za-z]{1,}" title="Names must only contain alphabets." required>
                            </label>

                            <label class="col-lg-6 col-12 d-flex flex-wrap gap-2" for="gender" required>Gender *
                                <select class="flex-grow-1 w-100" name="gender" id="gender">
                                    <option value="m">male</option>
                                    <option value="f">female</option>
                                    <option value="other">other</option>
                                </select>
                            </label>

                            <label class="col-lg-6 col-12 d-flex flex-wrap gap-2" for="dateOfBirth">Date of Birth *
                                <?php
                                    $maxDateOfBirth = (new DateTime())->format('Y-m-d');
                                ?>

                                <input class="flex-grow-1 w-100" type="date" min="1950-01-01" max="<?php echo $maxDateOfBirth; ?>" name="dateOfBirth" id="dateOfBirth" required>
                            </label>

                            <label class="col-lg-6 col-12 d-flex flex-wrap gap-2" for="phoneNumber">Phone Number
                                <input class="flex-grow-1 w-100" type="tel" name="phoneNumber" id="phoneNumber" pattern="(:?(:?\+92 ?)|0)\d{3}[ ]?\d{7}" title="A valid Pakistani phone number is expected. Example: +92XXX XXXXXXX">
                            </label>

                            <label class="col-lg-6 col-12 d-flex flex-wrap gap-2" for="email">Email *
                                <input class="flex-grow-1 w-100" type="email" name="email" id="email" required>
                            </label>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" form="addEmployeeForm" class="btn btn-primary">Add Employee</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteEmployeeModal" tabindex="-1" aria-labelledby="deleteEmployeeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="deleteEmployeeModalLabel">Delete Employee</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="deleteEmployeeForm" class="row row-gap-3" action="/admin/employees/delete" method="post">
                            <p>Are you sure you want to delete "<span class="employee-to-delete"></span>" employee?</p>
                            <input class="flex-grow-1 w-100" type="text" name="username" hidden>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" form="deleteEmployeeForm" class="btn btn-danger text-white">Delete Employee</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/setDeleteEmployeeData.js"></script>
    <script src="/assets/js/togglePasswordDisplay.js"></script>
</body>
</html>