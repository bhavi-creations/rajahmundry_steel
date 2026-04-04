<?php
// Database connection
include '../../db.connection/db_connection.php';

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $review_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $parent_id = !empty($_POST['parent_id']) ? intval($_POST['parent_id']) : NULL;
    $name      = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email     = isset($_POST['email']) ? trim($_POST['email']) : '';
    $rating    = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    $comment   = isset($_POST['comment']) ? trim($_POST['comment']) : '';

    // Validation
    if (empty($name) || empty($comment)) {
        die("Error: Name and Comment are required!");
    }

    // Insert or Update
    if ($review_id > 0) {
        // Update existing review
        $stmt = $conn->prepare("UPDATE reviews 
            SET parent_id = ?, name = ?, email = ?, rating = ?, comment = ?
            WHERE id = ?");
        $stmt->bind_param("issisi", $parent_id, $name, $email, $rating, $comment, $review_id);
    } else {
        // Insert new review
        $stmt = $conn->prepare("INSERT INTO reviews 
            (parent_id, name, email, rating, comment, created_at) 
            VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("issis", $parent_id, $name, $email, $rating, $comment);
    }

    // Execute SQL
    if ($stmt->execute()) {
        echo "Review saved successfully!";
        header("Location: ../../../../allreviews.php"); // 👈 Reviews list page
        exit();
    } else {
        echo "Error: " . $stmt->error;
        header("Location: newReview.php"); // 👈 Review form page
        exit();
    }

    $stmt->close();
}
$conn->close();
?>
