<?php
include "db.php";

if (isset($_POST['add_document'])){

    $employee_name = $_POST['employee_name'];
    $document_name = $_POST['document_name'];
    $document_type = $_POST['document_type'];

    // File upload
    $file_name = $_FILES['document_file']['name'];
    $file_tmp = $_FILES['document_file']['tmp_name'];

    $upload_folder = "uploads/";
    $file_path = $upload_folder . basename($file_name);

    if (move_uploaded_file($file_tmp, $file_path)) {

        $sql = "INSERT INTO documents
                (employee_name, document_name, document_type, status, file_path)
                VALUES
                ('$employee_name', '$document_name', '$document_type', 'Pending', '$file_path')";

        if (mysqli_query($conn, $sql)) {
            echo "<p style='color:green;'>Document uploaded successfully!</p>";
        } else {
            echo "<p style='color:red;'>Error: " . mysqli_error($conn) . "</p>";
        }

    } else {
        echo "<p style='color:red;'>File upload failed!</p>";
    }
}


// Verify document
if (isset($_GET['verify'])) {

    $id = $_GET['verify'];

    $sql = "UPDATE documents
            SET status='Verified'
            WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        echo "<p style='color:green;'>Document verified successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error: " . mysqli_error($conn) . "</p>";
    }
}
// Reject document
if (isset($_GET['reject'])) {

    $id = $_GET['reject'];
    $reason = $_GET['reason'];

    $sql = "UPDATE documents
            SET status='Rejected',
                rejection_reason='$reason'
            WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        echo "<p style='color:red;'>Document rejected successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error: " . mysqli_error($conn) . "</p>";
    }
}


// Get documents AFTER verification
$result = mysqli_query($conn, "SELECT * FROM documents");

?>

<!DOCTYPE html>
<html>

<head>

    <title>Document Verification</title>

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

    </style>

</head>

<body>

<h1>📄 Document Verification</h1>

<form method="POST" enctype="multipart/form-data">

    <h3>Add Document</h3>

    <input type="text"
           name="employee_name"
           placeholder="Employee Name"
           required>

    <input type="text"
           name="document_name"
           placeholder="Document Name"
           required>

    <select name="document_type">

        <option value="Identity Proof">Identity Proof</option>
        <option value="Certificate">Certificate</option>
        <option value="License">License</option>
        <option value="Other">Other</option>

    </select>

    <input type="file"
           name="document_file"
           required>

    <button type="submit" name="add_document">
        Upload Document
    </button>

</form>


<table>

    <tr>
        <th>ID</th>
        <th>Employee</th>
        <th>Document</th>
        <th>Type</th>
        <th>File</th>
        <th>Status</th>
        <th>Action</th>
        <th>Rejection Reason</th>
    </tr>

<?php

while ($row = mysqli_fetch_assoc($result)) {

?>

    <tr>

        <td><?php echo $row['id']; ?></td>

        <td><?php echo $row['employee_name']; ?></td>

        <td><?php echo $row['document_name']; ?></td>

        <td><?php echo $row['document_type']; ?></td>

        <td>
            <a href="<?php echo $row['file_path']; ?>" target="_blank">
                View Document
            </a>
        </td>

        <td>
            <?php echo $row['status']; ?>
        </td>

        <td>

           <?php if($row['status'] == 'Pending'){ ?>

    <a href="documents.php?verify=<?php echo $row['id']; ?>">
        <button type="button">Verify</button>
    </a>

    <button type="button"
        onclick="rejectDocument(<?php echo $row['id']; ?>)">
    Reject
</button>

<?php } elseif($row['status'] == 'Verified') { ?>

    Verified

<?php } else { ?>

    Rejected

<?php } ?>


        </td>
        <td>
    <?php
    if ($row['status'] == 'Rejected') {
        echo $row['rejection_reason'];
    } else {
        echo "-";
    }
    ?>
</td>

    </tr>

<?php

}

?>

</table>

<br>

<a href="dashboard.php">⬅ Back to Dashboard</a>
<script>
function rejectDocument(id) {

    let reason = prompt("Enter reason for rejecting this document:");

    if (reason !== null && reason.trim() !== "") {

        window.location.href =
            "documents.php?reject=" + id +
            "&reason=" + encodeURIComponent(reason);
    }
}
</script>
</body>

</html>