<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
</head>
<body>
    <h1>Showing All Users</h1>

    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Role</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach ($users as $user) {
                    echo "<tr>";
                    
                    echo "<td>" . $user->getUsername() . "</td>";
                    echo "<td>" . $user->getRole() . "</td>";

                    $activityStatus = $user->getIsActive() ? "Active" : "Inactive"; 
                    echo "<td>" . $activityStatus . "</td>";

                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>
</body>
</html>