<?php
include "db.php";

if (isset($_POST['add_asset'])) {

    $asset_name = $_POST['asset_name'];
    $category = $_POST['category'];
    $assigned_to = $_POST['assigned_to'];
    $location = $_POST['location'];
    $status = $_POST['status'];

    $sql = "INSERT INTO assets
            (asset_name, category, assigned_to, location, status)
            VALUES
            ('$asset_name', '$category', '$assigned_to', '$location', '$status')";

    if (mysqli_query($conn, $sql)) {
        echo "<p style='color:green;'>Asset added successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error: " . mysqli_error($conn) . "</p>";
    }
}

$result = mysqli_query($conn, "SELECT * FROM assets");
?>

<!DOCTYPE html>
<html>

<head>

    <title>Asset Management</title>

    <style>

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
            cursor: pointer;
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

        a {
            text-decoration: none;
        }

    </style>

</head>

<body>

<h1>💻 Asset Management</h1>

<form method="POST">

    <h3>Add Asset</h3>

    <input type="text"
           name="asset_name"
           placeholder="Asset Name"
           required>

    <input type="text"
           name="category"
           placeholder="Category"
           required>

    <input type="text"
           name="assigned_to"
           placeholder="Assigned To"
           required>

    <input type="text"
           name="location"
           placeholder="Location"
           required>

    <select name="status">

        <option value="Active">Active</option>
        <option value="Available">Available</option>
        <option value="Maintenance">Maintenance</option>

    </select>

    <button type="submit" name="add_asset">
        Add Asset
    </button>

</form>


<table>

    <tr>
        <th>ID</th>
        <th>Asset Name</th>
        <th>Category</th>
        <th>Assigned To</th>
        <th>Location</th>
        <th>Status</th>
        <th>QR Code</th>
    </tr>


<?php

while ($row = mysqli_fetch_assoc($result)) {

?>

    <tr>

        <td><?php echo $row['id']; ?></td>

        <td><?php echo $row['asset_name']; ?></td>

        <td><?php echo $row['category']; ?></td>

        <td><?php echo $row['assigned_to']; ?></td>

        <td><?php echo $row['location']; ?></td>

        <td><?php echo $row['status']; ?></td>

        <td>
    
<img 
    src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=http://192.168.10.52/enterprise_platform/asset_details.php?id=<?php echo $row['id']; ?>" 
    alt="QR Code">
</td>

    </tr>

<?php

}

?>

</table>

<br>

<a href="dashboard.php">⬅ Back to Dashboard</a>

</body>

</html>