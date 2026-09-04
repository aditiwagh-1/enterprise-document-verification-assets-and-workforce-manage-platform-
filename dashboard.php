<?php
include "db.php";

$employee_count = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM employees")
)['total'];

$asset_count = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM assets")
)['total'];

$document_count = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM documents")
)['total'];

$verified_count = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM documents WHERE status='Verified'")
)['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Enterprise Operations Dashboard</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f8;
        }

        .header {
            background: #1f2937;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .container {
            padding: 30px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            text-decoration: none;
            color: inherit;
        }

        .card h2 {
            margin: 10px 0;
            font-size: 35px;
        }

        .card p {
            color: #555;
        }

        .menu {
    background: #1f2937;
    padding: 15px 20px;
    display: flex;
    gap: 15px;
    margin-top: 30px;
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
    </style>
</head>

<body>

<div class="header">
    <h1>Enterprise Operations Dashboard</h1>
    <p>Document Verification • Workforce • QR-Based Asset Tracking</p>
</div>

<div class="container">

    <div class="cards">

        <a href="employees.php" class="card">
    <p>Employees</p>
    <h2><?php echo $employee_count; ?></h2>
</a>
<a href="assets.php" class="card">
    <p>Assets</p>
    <h2><?php echo $asset_count; ?></h2>
</a>
<a href="documents.php" class="card">
    <p>Documents</p>
    <h2><?php echo $document_count; ?></h2>
</a>

        <div class="card">
            <p>Verified Documents</p>
            <h2><?php echo $verified_count; ?></h2>
        </div>

    </div>

    <div class="menu">

    <a href="dashboard.php">🏠 Dashboard</a>

    <a href="employees.php">👨‍💼 Employees</a>

    <a href="assets.php">💻 Assets</a>

    <a href="documents.php">📄 Documents</a>

</div>
</div>

</body>
</html>