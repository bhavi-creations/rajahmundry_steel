<?php
include './db.connection/db_connection.php';

// 1. URL నుండి స్లగ్ లేదా ఐడి తీసుకోవడం
$slug_from_url = isset($_GET['slug']) ? mysqli_real_escape_string($conn, $_GET['slug']) : '';
$id_from_url = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (empty($slug_from_url) && $id_from_url <= 0) {
    header("Location: index.php"); // ఏమీ లేకపోతే హోమ్ పేజీకి పంపండి
    exit;
}

// 2. Fetch Blog Data
if (!empty($slug_from_url)) {
    $stmt = $conn->prepare("SELECT id, slug, title, main_content, full_content, title_image, main_image, video, telugu_title, telugu_main_content, telugu_full_content, section1_image, service FROM blogs WHERE slug = ?");
    $stmt->bind_param("s", $slug_from_url);
} else {
    $stmt = $conn->prepare("SELECT id, slug, title, main_content, full_content, title_image, main_image, video, telugu_title, telugu_main_content, telugu_full_content, section1_image, service FROM blogs WHERE id = ?");
    $stmt->bind_param("i", $id_from_url);
}

$stmt->execute();
$result = $stmt->get_result();
$blog = $result->fetch_assoc();
$stmt->close();

if (!$blog) {
    echo "<h2>Blog Not Found!</h2>";
    exit;
}

// 3. SEO Redirection: ఒకవేళ ఎవరైనా ID తో వస్తే, వారిని స్లగ్ URL కి రిడైరెక్ట్ చేస్తుంది
if ($id_from_url > 0 && !empty($blog['slug'])) {
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: " . $blog['slug']);
    exit();
}

// Reactions Count
$blog_id = $blog['id'];
$count_stmt = $conn->prepare("SELECT SUM(reaction='like') AS likes, SUM(reaction='dislike') AS dislikes FROM blog_reactions WHERE blog_id = ?");
$count_stmt->bind_param("i", $blog_id);
$count_stmt->execute();
$res = $count_stmt->get_result();
$counts = $res->fetch_assoc();
$count_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($blog['title']) ?></title>
    <style>
        .blog-detailed {
            max-width: 900px;
            margin: auto;
        }

        .main-video,
        .img-fluid {
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .lang-btn {
            cursor: pointer;
            padding: 8px 20px;
            border: 1px solid #ccc;
            background: #f8f9fa;
        }

        .lang-btn.active {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }

        .badge_service_name {
            background: #ff5722;
            color: white;
            border-radius: 20px;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .blog-title {
                font-size: 1.5rem;
            }

            .container {
                padding: 15px;
            }
        }
    </style>
</head>

<body style="background-color: #ffffff; color: white;">

    <?php include 'navbar.php'; ?>

    <main class="blogs_section">
        <div class="container blog-detailed" style="padding-top: 30px;">

            <div class="d-flex justify-content-center mb-4">
                <button id="english-btn" class="lang-btn active">English</button>
                <button id="telugu-btn" class="lang-btn ms-2">తెలుగు</button>
            </div>

            <?php if (!empty($blog['service'])) { ?>
                <div class="text-center mb-3">
                    <span class="badge_service_name px-4 py-1"><?= htmlspecialchars($blog['service']) ?></span>
                </div>
            <?php } ?>

            <div class="text-center mb-4">
                <?php if (!empty($blog['video'])): ?>
                    <video class='main-video' controls style='width:100%; max-height:450px;'>
                        <source src='./admin/uploads/videos/<?= $blog['video'] ?>' type='video/mp4'>
                    </video>
                <?php elseif (!empty($blog['main_image'])): ?>
                    <img src="./admin/uploads/photos/<?= $blog['main_image'] ?>" class="img-fluid" style="width:100%; height:auto;">
                <?php endif; ?>
            </div>

            <h1 class="blog-title text-center mb-4" style="font-weight:800;">
                <span id="title-en"><?= $blog['title'] ?></span>
                <span id="title-te" style="display:none;"><?= $blog['telugu_title'] ?></span>
            </h1>

            <div class="content-area" style="font-size: 1.1rem; line-height: 1.8;">
                <div id="body-en">
                    <p><?= $blog['main_content'] ?></p>
                    <div><?= $blog['full_content'] ?></div>
                </div>
                <div id="body-te" style="display:none;">
                    <p><?= $blog['telugu_main_content'] ?></p>
                    <div><?= $blog['telugu_full_content'] ?></div>
                </div>
            </div>

            <div class="d-flex justify-content-center mt-5">
                <button class="btn btn-outline-success me-3">👍 Like (<?= $counts['likes'] ?? 0 ?>)</button>
                <button class="btn btn-outline-danger">👎 Dislike (<?= $counts['dislikes'] ?? 0 ?>)</button>
            </div>
        </div>

        <div class="container my-5">
            <h2 class="text-center mb-4">LATEST BLOGS</h2>
            <div class="swiper blog-swiper">
                <div class="swiper-wrapper">
                    <?php
                    $latest = $conn->query("SELECT id, slug, title, main_image FROM blogs ORDER BY created_at DESC LIMIT 10");
                    while ($row = $latest->fetch_assoc()) {
                        $link = !empty($row['slug']) ? $row['slug'] : "fullblog.php?id=" . $row['id'];
                        $img = !empty($row['main_image']) ? "./admin/uploads/photos/" . $row['main_image'] : "default.png";
                        echo "<div class='swiper-slide'>
                            <div class='card bg-dark text-white' style='border-radius:15px; overflow:hidden;'>
                                <img src='$img' style='height:200px; object-fit:cover;'>
                                <div class='p-3'>
                                    <a href='$link' class='text-white text-decoration-none'><p>" . mb_strimwidth($row['title'], 0, 45, "...") . "</p></a>
                                </div>
                            </div>
                          </div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Language Toggle
        const enBtn = document.getElementById("english-btn");
        const teBtn = document.getElementById("telugu-btn");

        teBtn.onclick = () => {
            toggleLang('none', 'block');
            teBtn.classList.add('active');
            enBtn.classList.remove('active');
        };
        enBtn.onclick = () => {
            toggleLang('block', 'none');
            enBtn.classList.add('active');
            teBtn.classList.remove('active');
        };

        function toggleLang(enStyle, teStyle) {
            document.getElementById("title-en").style.display = enStyle;
            document.getElementById("body-en").style.display = enStyle;
            document.getElementById("title-te").style.display = teStyle;
            document.getElementById("body-te").style.display = teStyle;
        }

        // Swiper
        new Swiper(".blog-swiper", {
            slidesPerView: 1,
            spaceBetween: 20,
            autoplay: {
                delay: 3000
            },
            breakpoints: {
                640: {
                    slidesPerView: 2
                },
                1024: {
                    slidesPerView: 3
                }
            }
        });
    </script>

    <?php $conn->close(); ?>
</body>

</html>

<?php include 'footer.php' ?>