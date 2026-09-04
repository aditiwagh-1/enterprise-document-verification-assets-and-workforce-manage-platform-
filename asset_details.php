<?php
include "db.php";

if (!isset($_GET['id'])) {
    die("Asset ID not provided.");
}

$id = $_GET['id'];

$sql = "SELECT * FROM assets WHERE id = $id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    die("Asset not found.");
}

$asset = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Asset Details</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 40px;
        }

        .card {
            width: 500px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }

        h2 {
            text-align: center;
        }

        p {
            font-size: 18px;
        }
    </style>
</head>

<body>

<div class="card">

    <h2>Asset Details</h2>

    <p><b>Asset ID:</b> <?php echo $asset['id']; ?></p>

    <p><b>Asset Name:</b> <?php echo $asset['asset_name']; ?></p>

    <p><b>Category:</b> <?php echo $asset['category']; ?></p>

    <p><b>Assigned To:</b> <?php echo $asset['assigned_to']; ?></p>

    <p><b>Location:</b> <?php echo $asset['location']; ?></p>

    <p><b>Status:</b> <?php echo $asset['status']; ?></p>

</div>

</body>
</html>