<?php
// DB Connection
$host = "localhost";
$user = "root";
$pass = "";
$db   = "vision";
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get ID
$id = $_GET['id'] ?? 0;

// Fetch existing record
$sql = "SELECT * FROM gallery WHERE id = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

// Update Form Submit
if (isset($_POST['submit'])) {
    $title = $_POST['title'];

    $new_filename = $row['image']; // keep old image by default

    // If new image uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        
        $img_name = $_FILES['image']['name'];
        $tmp_name = $_FILES['image']['tmp_name'];

        // Upload directory
        $upload_dir = __DIR__ . "/../uploads/gallery/";

        // Generate unique name
        $unique_name = time() . "_" . basename($img_name);

        // Final upload path
        $full_path = $upload_dir . $unique_name;

        // Upload file
        if (move_uploaded_file($tmp_name, $full_path)) {

            // Delete old file
            if (!empty($row['image']) && file_exists($upload_dir . $row['image'])) {
                unlink($upload_dir . $row['image']);
            }

            // Save only filename to DB
            $new_filename = $unique_name;
        }
    }

    // Update query
    $stmt = $conn->prepare("UPDATE gallery SET title=?, image=? WHERE id=?");
    $stmt->bind_param("ssi", $title, $new_filename, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Gallery updated successfully'); window.location='gallery_list.php';</script>";
    } else {
        echo "Database Update Failed: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Edit Gallery Image</title>
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body>

<div id="wrapper">

    <?php include 'sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <?php include 'navbar.php'; ?>

            <div class="container-fluid">

                <h1 class="h3 mb-4 text-gray-800">Edit Gallery Image</h1>

                <div class="row">
                    <div class="col-xl-8">
                        <div class="card shadow mb-4">

                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-success">Update Image Details</h6>
                            </div>

                            <div class="card-body">
                                <form method="post" enctype="multipart/form-data">

                                    <!-- Title -->
                                    <div class="mb-3">
                                        <label class="form-label text-primary">Image Title</label>
                                        <input type="text" class="form-control" 
                                               name="title"
                                               value="<?php echo htmlspecialchars($row['title']); ?>" required>
                                    </div>

                                    <!-- Current Image -->
                                    <div class="mb-3">
                                        <label class="form-label text-primary">Current Image</label><br>

                                        <?php if (!empty($row['image'])) { ?>
                                            <img src="../uploads/gallery/<?php echo $row['image']; ?>" 
                                                 style="max-width:200px;" 
                                                 class="img-thumbnail">
                                        <?php } ?>
                                    </div>

                                    <!-- Change Image -->
                                    <div class="mb-3">
                                        <label class="form-label text-primary">Change Image</label>
                                        <input type="file" name="image" class="form-control" accept="image/*">
                                    </div>

                                    <div class="row p-3">
                                        <button type="reset" class="btn btn-danger mx-2 col-xl-2">Clear</button>
                                        <button type="submit" name="submit" class="btn btn-success mx-2 col-xl-2">Update</button>
                                    </div>

                                </form>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>
