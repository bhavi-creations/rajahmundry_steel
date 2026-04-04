<?php
include __DIR__ . '/../../db.connection/db_connection.php';

// Get comment id
$comment_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch comment details
$stmt = $conn->prepare("SELECT * FROM blog_comments WHERE id = ?");
$stmt->bind_param("i", $comment_id);
$stmt->execute();
$result = $stmt->get_result();
$comment = $result->fetch_assoc();

if (!$comment) {
    die("Comment not found!");
}

// Handle form submission for reply
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reply_text = trim($_POST['reply_text'] ?? '');

    if (!empty($reply_text)) {
        $stmt = $conn->prepare("UPDATE blog_comments SET reply_text=? WHERE id=?");
        $stmt->bind_param("si", $reply_text, $comment_id);

        if ($stmt->execute()) {
            // Redirect back to comments list
            header("Location: <blog_commets class="php"></blog_commets>?blog_id=" . $comment['blog_id'] . "&reply_added=1");
            exit;
        } else {
            echo "Error saving reply: " . $stmt->error;
        }
    } else {
        echo "Reply cannot be empty!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reply for Comment</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        textarea {
            width: 100%;
            resize: vertical;
            padding: 10px;
            border: 1px solid #d1d3e2;
            border-radius: 5px;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }
    </style>
</head>
<body id="page-top">
<div id="wrapper">
    <?php include 'sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include 'navbar.php'; ?>

            <div class="container-fluid">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h2 class="h3 mb-0 text-info">Reply for Comment</h2>
                    <a href="blog_commets.php?blog_id=<?= $comment['blog_id']; ?>" class="btn btn-sm btn-primary shadow-sm">
                        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Comments
                    </a>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Write Reply</h6>
                    </div>
                    <div class="card-body">
                        <p><strong><?= htmlspecialchars($comment['user_name']); ?>:</strong> 
                           <?= htmlspecialchars($comment['comment']); ?></p>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label for="reply_text">Reply Text</label>
                                <textarea id="reply_text" name="reply_text" rows="4"><?= isset($comment['reply_text']) ? htmlspecialchars($comment['reply_text']) : '' ?></textarea>
                            </div>

                            <button type="submit" class="btn btn-success">Submit Reply</button>
                            <a href="blog_commets.php?blog_id=<?= $comment['blog_id']; ?>" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>

                <?php if (!empty($comment['reply_text'])): ?>
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">Reply</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Admin:</strong> <?= htmlspecialchars($comment['reply_text']); ?></p>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>

        <footer class="sticky-footer bg-white">
            <div class="container my-auto text-center">
                <p style="color:black">©2024 Vision Dental. Designed & Developed by 
                    <a href="https://bhavicreations.com/" target="_blank" style="color:black;text-decoration:none;">Bhavi Creations</a>
                </p>
            </div>
        </footer>
    </div>
</div>

<a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>
</body>
</html>
