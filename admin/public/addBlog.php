<?php
// Database connection
include '../../db.connection/db_connection.php';

// Generate unique filename
function generateUniqueFileName($fileName)
{
    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
    return uniqid() . '_' . time() . '.' . $ext;
}

// Allowed image extensions
$allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];

// Upload image function
function uploadImage($fileKey, $directoryName, $allowed_extensions)
{
    if (!empty($_FILES[$fileKey]['name'])) {
        $ext = strtolower(pathinfo($_FILES[$fileKey]['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed_extensions)) {
            die("Invalid file type for $fileKey");
        }
        $directory = __DIR__ . "/../uploads/$directoryName/";
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        $fileName = generateUniqueFileName($_FILES[$fileKey]['name']);
        if (!move_uploaded_file($_FILES[$fileKey]['tmp_name'], $directory . $fileName)) {
            die("Error uploading $fileKey");
        }
        return $fileName;
    }
    return '';
}

// Form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $blog_id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    // Main fields
    $title = $_POST['title'] ?? '';

    // --- SLUG LOGIC START ---
    $slug = $_POST['slug'] ?? '';
    if (empty($slug)) {
        // Oka vela slug empty ga vadilithe title nundi generate chestundi
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    } else {
        // Space unte hyphen tho replace chestundi
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $slug)));
    }
    // --- SLUG LOGIC END ---

    $main_content = $_POST['main_content'] ?? '';
    $full_content = $_POST['full_content'] ?? '';
    $service = $_POST['service'] ?? '';

    // Telugu fields
    $telugu_title = $_POST['telugu_title'] ?? '';
    $telugu_main_content = $_POST['telugu_main_content'] ?? '';
    $telugu_full_content = $_POST['telugu_full_content'] ?? '';

    // Section content
    $section1_content = $_POST['section1_content'] ?? '';
    $section2_content = $_POST['section2_content'] ?? '';
    $section3_content = $_POST['section3_content'] ?? '';

    if (empty($title) || empty($main_content) || empty($full_content) || empty($service)) {
        die("Required fields missing");
    }

    // Upload images
    $title_image = uploadImage('title_image', 'photos', $allowed_extensions);
    $main_image  = uploadImage('main_image', 'photos', $allowed_extensions);

    // Video upload
    $video = '';
    if (!empty($_FILES['video']['name'])) {
        $videoDir = __DIR__ . "/../uploads/videos/";
        if (!is_dir($videoDir)) {
            mkdir($videoDir, 0777, true);
        }
        $video = generateUniqueFileName($_FILES['video']['name']);
        move_uploaded_file($_FILES['video']['tmp_name'], $videoDir . $video);
    }

    // Section images
    $section1_image = uploadImage('section1_image', 'photos', $allowed_extensions);
    $section2_image = uploadImage('section2_image', 'photos', $allowed_extensions);
    $section3_image = uploadImage('section3_image', 'photos', $allowed_extensions);

    // UPDATE
    if ($blog_id > 0) {
        $stmt = $conn->prepare("
            UPDATE blogs SET
                title=?, slug=?, main_content=?, full_content=?,
                telugu_title=?, telugu_main_content=?, telugu_full_content=?,
                title_image=?, main_image=?, video=?,
                service=?,
                section1_content=?, section1_image=?,
                section2_content=?, section2_image=?,
                section3_content=?, section3_image=?
            WHERE id=?
        ");

        $stmt->bind_param(
            "sssssssssssssssssi",
            $title,
            $slug,
            $main_content,
            $full_content,
            $telugu_title,
            $telugu_main_content,
            $telugu_full_content,
            $title_image,
            $main_image,
            $video,
            $service,
            $section1_content,
            $section1_image,
            $section2_content,
            $section2_image,
            $section3_content,
            $section3_image,
            $blog_id
        );
    }
    // INSERT
    else {
        $stmt = $conn->prepare("
            INSERT INTO blogs
            (title, slug, main_content, full_content,
             telugu_title, telugu_main_content, telugu_full_content,
             title_image, main_image, video,
             service,
             section1_content, section1_image,
             section2_content, section2_image,
             section3_content, section3_image,
             created_at)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW())
        ");

        $stmt->bind_param(
            "sssssssssssssssss",
            $title,
            $slug,
            $main_content,
            $full_content,
            $telugu_title,
            $telugu_main_content,
            $telugu_full_content,
            $title_image,
            $main_image,
            $video,
            $service,
            $section1_content,
            $section1_image,
            $section2_content,
            $section2_image,
            $section3_content,
            $section3_image
        );
    }

    if ($stmt->execute()) {
        header("Location: allBlog.php");
        exit;
    } else {
        die("SQL Error: " . $stmt->error);
    }
}

$conn->close();
