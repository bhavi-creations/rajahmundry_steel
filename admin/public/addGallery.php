<?php
// ======================================
// 🧩 ENABLE SAFE ERROR LOGGING
// ======================================
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_error.log'); 
error_reporting(E_ALL);
ini_set('display_errors', 1); // Set to 0 on production

// ======================================
// 🧩 DATABASE CONNECTION
// ======================================
include __DIR__ . '/../../db.connection/db_connection.php';

// ======================================
// 🧩 ALLOWED FILE FORMATS
// ======================================
$allowedImageFormats = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

// ======================================
// 🧩 FUNCTION: Generate Unique File Name
// ======================================
function generateUniqueFileName($fileName) {
    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
    return uniqid('file_') . '_' . time() . '.' . $ext;
}

// ======================================
// 🧩 FUNCTION: Ensure Directory Exists and Writable
// ======================================
function ensureWritableDirectory($dir) {
    if (!is_dir($dir)) {
        if (!mkdir($dir, 0777, true) && !is_dir($dir)) {
            error_log("Failed to create directory: " . $dir);
            die("Error: Failed to create upload directory ($dir).");
        }
    }

    if (!is_writable($dir)) {
        @chmod($dir, 0777);
        clearstatcache();
        if (!is_writable($dir)) {
            error_log("Directory not writable: " . $dir);
            die("Error: Upload directory ($dir) is not writable. Please check folder permissions.");
        }
    }
}

// ======================================
// 🧩 FUNCTION: Upload File
// ======================================
function uploadFile($fileKey, $uploadDir, $allowedFormats = []) {
    if (!empty($_FILES[$fileKey]['name'])) {
        ensureWritableDirectory($uploadDir);

        $ext = strtolower(pathinfo($_FILES[$fileKey]['name'], PATHINFO_EXTENSION));
        if (!empty($allowedFormats) && !in_array($ext, $allowedFormats)) {
            die("Error: Invalid file format for $fileKey. Allowed: " . implode(', ', $allowedFormats));
        }

        if ($_FILES[$fileKey]['error'] !== UPLOAD_ERR_OK) {
            die("Upload error for $fileKey: " . $_FILES[$fileKey]['error']);
        }

        $fileName = generateUniqueFileName($_FILES[$fileKey]['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES[$fileKey]['tmp_name'], $targetPath)) {
            return $fileName; 
        } else {
            error_log("Failed to move uploaded file for $fileKey to $targetPath");
            die("Error uploading $fileKey. Check folder permissions for: " . $uploadDir);
        }
    }
    return '';
}

// ======================================
// 🧩 MAIN SCRIPT
// ======================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $gallery_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $title      = trim($_POST['title'] ?? '');

    // Validation
    if (empty($title)) {
        die("Error: Title cannot be empty.");
    }

    // Directory
    $uploadsDir = __DIR__ . "/../uploads/gallery/";

    // Upload Image
    $image_path = uploadFile('image', $uploadsDir, $allowedImageFormats);

    // Preserve existing file if editing
    if ($gallery_id > 0) {
        $existing = $conn->query("SELECT * FROM gallery WHERE id=$gallery_id");
        if ($existing && $existing->num_rows > 0) {
            $existing = $existing->fetch_assoc();
            $image_path = $image_path ?: ($existing['image'] ?? '');
        } else {
            die("Error: Gallery item not found for editing.");
        }
    }

    // ======================================
    // SQL QUERIES
    // ======================================
    if ($gallery_id > 0) {
        // UPDATE
        $stmt = $conn->prepare("UPDATE gallery SET title=?, image=? WHERE id=?");
        if (!$stmt) { die("Prepare failed: " . $conn->error); }

        $stmt->bind_param("ssi", $title, $image_path, $gallery_id);

    } else {
        // INSERT
        $stmt = $conn->prepare("INSERT INTO gallery (title, image, created_at) VALUES (?, ?, NOW())");
        if (!$stmt) { die("Prepare failed: " . $conn->error); }

        $stmt->bind_param("ss", $title, $image_path);
    }

    // ======================================
    // EXECUTE AND REDIRECT
    // ======================================
    if ($stmt->execute()) {
        header("Location: gallery_list.php"); // Redirect to gallery list page
        exit();
    } else {
        error_log("Execute error: " . $stmt->error);
        die("Execute Error: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
}
?>
