<?php
include "db.php";

echo "Database:".
mysqli_fetch_assoc(mysqli_query($conn,"SELECT DATABASE()"))
['DATABASE()'];
if (isset($_POST['add_employee'])) {


    $name = $_POST['name'];
    $department = $_POST['department'];
    $designation = $_POST['designation'];
    $attendance = $_POST['attendance'];

    $sql = "INSERT INTO employees (name, department, designation, attendance)
            VALUES ('$name', '$department', '$designation', '$attendance')";

    if (mysqli_query($conn, $sql)) {
        echo "<p style='color:green;'>Employee added successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error: " . mysqli_error($conn) . "</p>";
    }
}
$result = mysqli_query($conn, "SELECT * FROM employees");
?>

<!DOCTYPE html>
<html>
<head>

    <title>Employees</title>

    <style>
        .menu {
    background: #1f2937;
    padding: 15px 20px;
    display: flex;
    gap: 15px;
    margin-bottom: 30px;
    border-radius: 8px;
}

.menu a {
    text-decoration: none;
    color: white;
    padding: 10px 18px;
    border-radius: 5px;
    font-weight: bold;
}

.menu a:hover {
    background: #374151;
}

        body {
            font-family: Arial;
            background: #f4f6f8;
            padding: 30px;
        }

        h1 {
            text-align: center;
        }

        form {
            background: white;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 10px;
        }

        input, select {
            padding: 10px;
            margin: 5px;
        }

        button {
            padding: 10px 20px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 5px;
        }

        table {
            width: 100%;
            background: white;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background: #1f2937;
            color: white;
        }

    </style>

</head>

<body>
    <div class="menu">

    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="employees.php">👨‍💼 Employees</a>
    <a href="assets.php">💻 Assets</a>
    <a href="documents.php">📄 Documents</a>

</div>

<h1>👨‍💼 Workforce Management</h1>

<form method="POST">

    <h3>Add Employee</h3>

    <input type="text" name="name" placeholder="Employee Name" required>

    <input type="text" name="department" placeholder="Department" required>

    <input type="text" name="designation" placeholder="Designation" required>

    <select name="attendance">
        <option value="Present">Present</option>
        <option value="Absent">Absent</option>
    </select>

    <button type="submit" name="add_employee">
        Add Employee
    </button>

</form>

<table>

    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Department</th>
        <th>Designation</th>
        <th>Attendance</th>
    </tr>

<?php

while ($row = mysqli_fetch_assoc($result)) {

?>

    <tr>

        <td><?php echo $row['id']; ?></td>

        <td><?php echo $row['name']; ?></td>

        <td><?php echo $row['department']; ?></td>

        <td><?php echo $row['designation']; ?></td>

        <td><?php echo $row['attendance']; ?></td>

    </tr>

<?php
}
?>

</table>

<br>

<a href="dashboard.php">⬅ Back to Dashboard</a>

</body>
</html>