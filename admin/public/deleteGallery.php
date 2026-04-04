<?php
include '../../db.connection/db_connection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Get image path before deleting to remove the file
    $result = $conn->query("SELECT image FROM gallery WHERE id=$id");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $image_path = $row['image'];

        // Delete the image file
        if (file_exists($image_path)) {
            unlink($image_path);
        }

        // Delete record from database
        $conn->query("DELETE FROM gallery WHERE id=$id");

        echo "<script>alert('Image deleted successfully'); window.location='gallery_list.php';</script>";
    } else {
        echo "<script>alert('Image not found'); window.location='gallery_list.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request'); window.location='gallery_list.php';</script>";
}

$conn->close();
?>
