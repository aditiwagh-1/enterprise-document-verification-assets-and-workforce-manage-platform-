<?php
include "db.php";

/* =========================
   ADD ASSET
========================= */

if (isset($_POST['add_asset'])) {

    $asset_name  = trim($_POST['asset_name']);
    $category    = trim($_POST['category']);
    $assigned_to = trim($_POST['assigned_to']);
    $location    = trim($_POST['location']);
    $status      = $_POST['status'];

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO assets
        (asset_name, category, assigned_to, location, status)
        VALUES (?, ?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "sssss",
        $asset_name,
        $category,
        $assigned_to,
        $location,
        $status
    );

    mysqli_stmt_execute($stmt);

    header("Location: assets.php?success=added");
    exit();
}


/* =========================
   DELETE ASSET
========================= */

if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    $stmt = mysqli_prepare(
        $conn,
        "DELETE FROM assets WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    header("Location: assets.php?success=deleted");
    exit();
}


/* =========================
   UPDATE ASSET
========================= */

if (isset($_POST['update_asset'])) {

    $id          = intval($_POST['id']);
    $asset_name  = trim($_POST['asset_name']);
    $category    = trim($_POST['category']);
    $assigned_to = trim($_POST['assigned_to']);
    $location    = trim($_POST['location']);
    $status      = $_POST['status'];

    $stmt = mysqli_prepare(
        $conn,
        "UPDATE assets
         SET asset_name = ?,
             category = ?,
             assigned_to = ?,
             location = ?,
             status = ?
         WHERE id = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "sssssi",
        $asset_name,
        $category,
        $assigned_to,
        $location,
        $status,
        $id
    );

    mysqli_stmt_execute($stmt);

    header("Location: assets.php?success=updated");
    exit();
}


/* =========================
   SEARCH + FILTER
========================= */

$search  = $_GET['search'] ?? "";
$filter  = $_GET['status_filter'] ?? "";

$search = trim($search);

$sql = "SELECT * FROM assets WHERE 1=1";

$params = [];
$types = "";

if ($search != "") {

    $sql .= " AND (
        asset_name LIKE ?
        OR category LIKE ?
        OR assigned_to LIKE ?
        OR location LIKE ?
    )";

    $searchValue = "%" . $search . "%";

    $params[] = $searchValue;
    $params[] = $searchValue;
    $params[] = $searchValue;
    $params[] = $searchValue;

    $types .= "ssss";
}

if ($filter != "") {

    $sql .= " AND status = ?";

    $params[] = $filter;
    $types .= "s";
}

$sql .= " ORDER BY id DESC";

$stmt = mysqli_prepare($conn, $sql);

if (!empty($params)) {

    mysqli_stmt_bind_param(
        $stmt,
        $types,
        ...$params
    );
}

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);


/* =========================
   DASHBOARD STATISTICS
========================= */

$totalQuery = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM assets"
);

$totalAssets = mysqli_fetch_assoc($totalQuery)['total'];


$activeQuery = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM assets
     WHERE status = 'Active'"
);

$activeAssets = mysqli_fetch_assoc($activeQuery)['total'];


$availableQuery = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM assets
     WHERE status = 'Available'"
);

$availableAssets = mysqli_fetch_assoc($availableQuery)['total'];


$maintenanceQuery = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM assets
     WHERE status = 'Maintenance'"
);

$maintenanceAssets =
    mysqli_fetch_assoc($maintenanceQuery)['total'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Enterprise Asset Management</title>

<style>

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {

    font-family: Arial, sans-serif;

    background: #f1f5f9;

    color: #1e293b;

}


/* =========================
   HEADER
========================= */

.header {

    background: #0f172a;

    color: white;

    padding: 20px 35px;

    display: flex;

    justify-content: space-between;

    align-items: center;

}

.header h1 {

    font-size: 25px;

}

.header a {

    color: white;

    text-decoration: none;

    background: #2563eb;

    padding: 10px 18px;

    border-radius: 7px;

}


/* =========================
   CONTAINER
========================= */

.container {

    max-width: 1400px;

    margin: auto;

    padding: 30px;

}


/* =========================
   STATISTICS
========================= */

.stats {

    display: grid;

    grid-template-columns:
        repeat(4, 1fr);

    gap: 20px;

    margin-bottom: 30px;

}

.stat-card {

    background: white;

    padding: 25px;

    border-radius: 12px;

    box-shadow:
        0 3px 10px rgba(0,0,0,0.07);

}

.stat-card h3 {

    color: #64748b;

    font-size: 14px;

    margin-bottom: 10px;

}

.stat-card .number {

    font-size: 32px;

    font-weight: bold;

}


/* =========================
   FORM
========================= */

.form-card {

    background: white;

    padding: 25px;

    border-radius: 12px;

    margin-bottom: 30px;

    box-shadow:
        0 3px 10px rgba(0,0,0,0.07);

}

.form-card h2 {

    margin-bottom: 20px;

}

.form-grid {

    display: grid;

    grid-template-columns:
        repeat(5, 1fr);

    gap: 12px;

}

input,
select {

    width: 100%;

    padding: 12px;

    border: 1px solid #cbd5e1;

    border-radius: 7px;

    outline: none;

}

input:focus,
select:focus {

    border-color: #2563eb;

}

.btn {

    border: none;

    padding: 12px 18px;

    border-radius: 7px;

    cursor: pointer;

    font-weight: bold;

}

.btn-primary {

    background: #2563eb;

    color: white;

}


/* =========================
   SEARCH
========================= */

.search-card {

    background: white;

    padding: 20px;

    border-radius: 12px;

    margin-bottom: 20px;

}

.search-form {

    display: grid;

    grid-template-columns:
        2fr 1fr auto;

    gap: 10px;

}


/* =========================
   TABLE
========================= */

.table-container {

    background: white;

    border-radius: 12px;

    overflow-x: auto;

    box-shadow:
        0 3px 10px rgba(0,0,0,0.07);

}

table {

    width: 100%;

    border-collapse: collapse;

}

th {

    background: #1e293b;

    color: white;

    padding: 15px;

    text-align: center;

}

td {

    padding: 14px;

    border-bottom:
        1px solid #e2e8f0;

    text-align: center;

}

tr:hover {

    background: #f8fafc;

}


/* =========================
   STATUS
========================= */

.status {

    padding: 6px 12px;

    border-radius: 20px;

    font-size: 12px;

    font-weight: bold;

}

.active {

    background: #dcfce7;

    color: #166534;

}

.available {

    background: #dbeafe;

    color: #1d4ed8;

}

.maintenance {

    background: #fef3c7;

    color: #92400e;

}


/* =========================
   ACTION BUTTONS
========================= */

.action {

    text-decoration: none;

    padding: 7px 10px;

    border-radius: 5px;

    font-size: 12px;

    margin: 2px;

    display: inline-block;

}

.view {

    background: #dbeafe;

    color: #1d4ed8;

}

.delete {

    background: #fee2e2;

    color: #b91c1c;

}


/* =========================
   QR
========================= */

.qr {

    width: 75px;

    height: 75px;

}


/* =========================
   SUCCESS
========================= */

.alert {

    padding: 15px;

    background: #dcfce7;

    color: #166534;

    border-radius: 7px;

    margin-bottom: 20px;

}


/* =========================
   RESPONSIVE
========================= */

@media(max-width: 900px) {

    .stats {

        grid-template-columns:
            repeat(2, 1fr);

    }

    .form-grid {

        grid-template-columns:
            repeat(2, 1fr);

    }

}

@media(max-width: 600px) {

    .stats {

        grid-template-columns: 1fr;

    }

    .form-grid {

        grid-template-columns: 1fr;

    }

    .search-form {

        grid-template-columns: 1fr;

    }

    .header {

        padding: 15px;

    }

    .container {

        padding: 15px;

    }

}

</style>

</Head>


<body>


<!-- HEADER -->

<div class="header">

    <h1>💻 Enterprise Asset Management</h1>

    <a href="dashboard.php">
        ← Dashboard
    </a>

</div>


<div class="container">


<?php

if (isset($_GET['success'])) {

    $message = "";

    if ($_GET['success'] == "added") {

        $message = "Asset added successfully.";

    }

    if ($_GET['success'] == "updated") {

        $message = "Asset updated successfully.";

    }

    if ($_GET['success'] == "deleted") {

        $message = "Asset deleted successfully.";

    }

    if ($message != "") {

?>

<div class="alert">

    ✅ <?php echo htmlspecialchars($message); ?>

</div>

<?php

    }

}

?>


<!-- =========================
     STATISTICS
========================= -->

<div class="stats">


<div class="stat-card">

    <h3>Total Assets</h3>

    <div class="number">

        <?php echo $totalAssets; ?>

    </div>

</div>


<div class="stat-card">

    <h3>Active Assets</h3>

    <div class="number">

        <?php echo $activeAssets; ?>

    </div>

</div>


<div class="stat-card">

    <h3>Available Assets</h3>

    <div class="number">

        <?php echo $availableAssets; ?>

    </div>

</div>


<div class="stat-card">

    <h3>Under Maintenance</h3>

    <div class="number">

        <?php echo $maintenanceAssets; ?>

    </div>

</div>


</div>


<!-- =========================
     ADD ASSET
========================= -->

<div class="form-card">

<h2>➕ Add New Asset</h2>

<form method="POST">

<div class="form-grid">


<input
    type="text"
    name="asset_name"
    placeholder="Asset Name"
    required
>


<input
    type="text"
    name="category"
    placeholder="Category"
    required
>


<input
    type="text"
    name="assigned_to"
    placeholder="Assigned To"
    required
>


<input
    type="text"
    name="location"
    placeholder="Location"
    required
>


<select name="status">

    <option value="Active">
        Active
    </option>

    <option value="Available">
        Available
    </option>

    <option value="Maintenance">
        Maintenance
    </option>

</select>


<button
    type="submit"
    name="add_asset"
    class="btn btn-primary"
>

    Add Asset

</button>


</div>

</form>

</div>


<!-- =========================
     SEARCH
========================= -->

<div class="search-card">

<form method="GET"
      class="search-form">


<input
    type="text"
    name="search"
    placeholder="🔍 Search asset, employee, category or location..."
    value="<?php echo htmlspecialchars($search); ?>"
>


<select name="status_filter">

    <option value="">
        All Status
    </option>

    <option value="Active"
        <?php
        if ($filter == "Active")
            echo "selected";
        ?>
    >
        Active
    </option>

    <option value="Available"
        <?php
        if ($filter == "Available")
            echo "selected";
        ?>
    >
        Available
    </option>

    <option value="Maintenance"
        <?php
        if ($filter == "Maintenance")
            echo "selected";
        ?>
    >
        Maintenance
    </option>

</select>


<button
    class="btn btn-primary"
    type="submit"
>

    Search

</button>


</form>

</div>


<!-- =========================
     ASSET TABLE
========================= -->

<div class="table-container">

<table>

<thead>

<tr>

<th>ID</th>

<th>Asset</th>

<th>Category</th>

<th>Assigned To</th>

<th>Location</th>

<th>Status</th>

<th>QR Code</th>

<th>Actions</th>

</tr>

</thead>


<tbody>


<?php

while ($row = mysqli_fetch_assoc($result)) {

    $statusClass = strtolower(
        $row['status']
    );

?>

<tr>


<td>

    #<?php
    echo htmlspecialchars(
        $row['id']
    );
    ?>

</td>


<td>

    <strong>

    <?php

    echo htmlspecialchars(
        $row['asset_name']
    );

    ?>

    </strong>

</td>


<td>

<?php

echo htmlspecialchars(
    $row['category']
);

?>

</td>


<td>

<?php

echo htmlspecialchars(
    $row['assigned_to']
);

?>

</td>


<td>

<?php

echo htmlspecialchars(
    $row['location']
);

?>

</td>


<td>

<span class="status <?php
echo $statusClass;
?>">

<?php

echo htmlspecialchars(
    $row['status']
);

?>

</span>

</td>


<td>


<?php

$assetId = $row['id'];

$qrData =
    "http://192.168.10.52/enterprise_platform/asset_details.php?id="
    . $assetId;

$qrUrl =
    "https://api.qrserver.com/v1/create-qr-code/?size=100x100&data="
    . urlencode($qrData);

?>


<img
    class="qr"
    src="<?php echo htmlspecialchars($qrUrl); ?>"
    alt="Asset QR Code"
>


</td>


<td>


<a
    class="action view"
    href="asset_details.php?id=<?php
    echo $assetId;
    ?>"
>

    👁 View

</a>


<a
    class="action delete"
    href="assets.php?delete=<?php
    echo $assetId;
    ?>"
    onclick="return confirm(
        'Are you sure you want to delete this asset?'
    );"
>

    🗑 Delete

</a>


</td>


</tr>


<?php

}

?>


</tbody>

</table>

</div>


</div>


</body>

</html>