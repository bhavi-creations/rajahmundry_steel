<?php
include './db.connection/db_connection.php';

$blog_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($blog_id <= 0) {
    echo "Invalid Blog ID";
    exit;
}

// FETCH BLOG DATA
$stmt = $conn->prepare("SELECT * FROM blogs WHERE id = ?");
$stmt->bind_param("i", $blog_id);
$stmt->execute();
$blog = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$blog) {
    echo "Blog not found!";
    exit;
}

// NEWSLETTER FETCHING
$latestNewsletter = null;
$news_sql = "SELECT title, pdf_path FROM pdf_uploads ORDER BY id DESC LIMIT 1";
$news_result = $conn->query($news_sql);
if ($news_result && $news_result->num_rows > 0) {
    $latestNewsletter = $news_result->fetch_assoc();
}

function getLimitWords($text, $limit = 15)
{
    $text = strip_tags($text);
    $words = explode(' ', $text);
    if (count($words) > $limit) {
        return implode(' ', array_slice($words, 0, $limit));
    }
    return $text;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vision Dental </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-navy: #dea13e;
            --soft-cream: #f8ede3;
            --accent-pink: #ffcfd2;
        }

        body {
            background-color: #ffffff;
            font-family: 'Poppins', sans-serif;
            color: #333;
        }

        .fullblog-main-container {
            background-color: #f8f8f8;
            max-width: 1200px;
            margin: 30px auto;
            padding: 40px;
            border-radius: 30px;
            border: 2px solid #943483 ;
        }

        /* Menu Styles */
        .header-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .custom-menu {
            border-top: 1px solid #943483;
            border-bottom: 1px solid #943483;
            padding: 10px 0;
            margin: 20px 0;
            display: flex;
            justify-content: center;
            gap: 25px;
        }

        .custom-menu a {
            color: #943483;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            text-transform: uppercase;
            transition: 0.3s;
        }

        .custom-menu a:hover {
            color: var(--primary-navy);
        }

        /* Lang Switcher Centered */
        .lang-container {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }

        .lang-switcher {
            background: #e0f7f7;
            /* background: var(--soft-cream); */
            padding: 5px;
            border-radius: 50px;
            display: inline-flex;
        }

        .lang-btn {
            font-size: 13px;
            padding: 8px 20px;
            border-radius: 50px;
            border: none;
            background: transparent;
            color: #943483;
            /* color: var(--primary-navy); */
            font-weight: 600;
            cursor: pointer;
        }

        .lang-btn.active {
            background: #943483;
            /* background: var(--primary-navy); */
            color: white;
        }

        .service-badge {
            background:  #943483;
            /* background: var(--primary-navy); */
            color: #ffffff;
            padding: 5px 15px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 700;
            display: inline-block;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .doctor-card {
            background: var(--soft-cream);
            border-radius: 20px;
            padding: 25px;
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 35px;
        }

        .fullblog-profile-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #fff;
        }

        .content-box {
            padding: 30px;
            background: #943483;
            /* background: #eba121; */
            border-radius: 20px;
            position: relative;
            z-index: 2;
            color: white !important;
        }

        .img-wrapper img {
            width: 100%;
            height: 350px;
            object-fit: cover;
            border-radius: 25px;
        }

        @media (min-width: 992px) {
            .overlap-right {
                margin-right: -50px;
            }

            .overlap-left {
                margin-left: -50px;
            }
        }

        @media (max-width: 768px) {
            .fullblog-main-container {
                padding: 15px !important;
            }

            .custom-menu {
                gap: 10px;
            }

            .custom-menu a {
                font-size: 12px;
            }
        }

        .hidden-content {
            display: none;
            margin-top: 15px;
            padding: 20px;
            background: #943483;
            border-left: 5px solid var(--primary-navy);
            border-radius: 10px;
            color: #ffff00 !important;
            line-height: 1.8;
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .btn-dental {
            background: #943483;
            color: #ffff00;
            border: 2px solid white ;
            /* border: none; */
            padding: 8px 25px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 15px;
        }

        .sidebar-widget {
            background: #943483;
            border: 1px solid gold;
            padding: 20px;
            border-radius: 20px;
            margin-bottom: 25px;
        }

        .sidebar-title {
            font-weight: 700;
            font-size: 13px;
            color: white;
            text-transform: uppercase;
            margin-bottom: 15px;
            display: block;
            border-left: 4px solid var(--primary-navy);
            padding-left: 10px;
        }

        .key-point {
            background:white;
            /* background: var(--soft-cream); */
            padding: 8px 12px;
            border-radius: 10px;
            font-size: 12px;
            margin-bottom: 8px;
            color: #943483;
        }

        .tag-badge {
            display: inline-block;
            background: gold;
            color: #943483;
            padding: 4px 10px;
            border-radius: 50px;
            font-size: 10px;
            margin: 2px;
            font-weight: 600;
        }

        /* doctor section stylings   */
        /* Doctor Card Premium Styling */
        .doctor-card-premium {
            background-color: #943483;
            /* background: linear-gradient(135deg, #ffff00 0%, #cbcb5c 100%); */
            /* background: linear-gradient(135deg, #f8ede3 0%, #ffffff 100%); */
            border-radius: 25px;
            padding: 35px;
            display: flex;
            align-items: center;
            gap: 30px;
            margin-bottom: 40px;
            border: 1px solid rgba(222, 161, 62, 0.2);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }

        .doctor-card-premium:hover {
            transform: translateY(-5px);
        }

        .doctor-image-wrapper {
            position: relative;
            flex-shrink: 0;
        }

        .doctor-profile-img {
            width: 220px;
            height: 220px;
            object-fit: cover;
            border-radius: 20px;
            border: 5px solid #fff;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .experience-badge {
            position: absolute;
            bottom: -10px;
            right: -10px;
            background: #943483;
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 50px;
            border: 1px solid #dea13e;
            text-transform: uppercase;
        }

        .doctor-info {
            flex-grow: 1;
        }

        .doctor-name {
            font-size: 28px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 5px;
            letter-spacing: -0.5px;
        }

        .doctor-degree {
            display: block;
            color: #dea13e;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            margin-bottom: 15px;
            letter-spacing: 1px;
        }

        .doctor-quote-box {
            background: rgba(255, 255, 255, 0.6);
            padding: 20px;
            border-radius: 15px;
            position: relative;
            border-left: 4px solid #dea13e;
        }

        .quote-icon {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 20px;
            color: rgba(222, 161, 62, 0.2);
        }

        .doctor-description {
            font-size: 14px;
            line-height: 1.6;
            color: #555;
            margin-bottom: 0;
        }

        .highlight-text {
            display: block;
            margin-top: 5px;
            font-style: italic;
            color: #003366;
            font-weight: 500;
        }

        .doctor-socials {
            margin-top: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .follow-text {
            font-size: 12px;
            font-weight: 700;
            color: #ffffff;
            text-transform: uppercase;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .doctor-card-premium {
                flex-direction: column;
                text-align: center;
                padding: 25px;
            }

            .doctor-image-wrapper {
                margin-bottom: 10px;
            }

            .doctor-profile-img {
                width: 150px;
                height: 150px;
            }

            .doctor-socials {
                justify-content: center;
                flex-wrap: wrap;
            }

            .doctor-quote-box {
                border-left: none;
                border-top: 3px solid #dea13e;
            }
        }

        /* పాత కోడ్ ప్లేస్ లో ఇది రీప్లేస్ చేయండి */
        .img-wrapper img {
            width: 100%;
            height: auto;
            /* హైట్ ఫిక్స్ చేయకుండా ఆటోలో ఉంచండి */
            max-height: 500px;
            /* ఇమేజ్ మరీ పెద్దది కాకుండా ఒక లిమిట్ */
            object-fit: contain;
            /* ఇమేజ్ కట్ అవ్వకుండా బాక్స్‌లో సెట్ అవుతుంది */
            border-radius: 25px;
            background-color: #943483;
            /* ఒకవేళ ఇమేజ్ చిన్నదైతే సైడ్స్ నల్లగా కనిపిస్తాయి, కట్ అవ్వదు */
        }
    </style>
</head>

<body>

    <div class="container fullblog-main-container">
        <div class="header-section">
            <img src="assets/img/vision/large_icon.png" style="height:70px" alt="Logo">

            <nav class="custom-menu">
                <a href="index.php">Home</a>
                <a href="about_srinivasa_multispeciality_dental_hospital.php">About</a>
                <a href="services_srinivasa_multispeciality_dental_hospital.php">Services</a>
                <a href="gallery_srinivasa_multispeciality_dental_hospital.php">Gallery</a>
                <a href="blogs_srinivasa_multispeciality_dental_hospital.php">Blogs</a>
                <a href="contact_srinivasa_multispeciality_dental_hospital.php">Contact</a>
                <!-- <a href="appointment_srinivasa_dental_hospital.php">Appointment</a> -->
            </nav>

            <div class="lang-container">
                <div class="lang-switcher">
                    <button class="lang-btn active" id="btn-en" onclick="switchLang('en')">English</button>
                    <button class="lang-btn" id="btn-te" onclick="switchLang('te')">తెలుగు</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="doctor-card-premium">
                    <div class="doctor-image-wrapper">
                        <img src="assets/img/about/a11.png" class="doctor-profile-img" alt="Dr. Kiran">
                        <div class="experience-badge">8+ Years Exp</div>
                    </div>
                    <div class="doctor-info">
                        <div class="doctor-title-area">
                            <h2 class="doctor-name">Dr. Abhilash</h2>
                            <span class="doctor-degree">Dental Implant Surgeries, Laser Photomedicine, And Full Mouth Rehabilitations</span>
                        </div>
                        <div class="doctor-quote-box">
                            <i class="fas fa-quote-left quote-icon"></i>
                            <p class="doctor-description">
                                Brought global advancements like <strong>Invisalign & iTero</strong> to Kakinada, guided by the belief:
                                <span class="highlight-text">"Our people deserve the world's best too."</span>
                            </p>
                        </div>
                        <div class="doctor-socials">
                            <span class="follow-text">Expertise in:</span>
                            <span class="badge bg-white text-dark rounded-pill px-3 py-1">Invisalign</span>
                            <span class="badge bg-white text-dark rounded-pill px-3 py-1">iTero Scan</span>
                            <span class="badge bg-white text-dark rounded-pill px-3 py-1">Smile Design</span>
                        </div>
                    </div>
                </div>

                <div class="lang-container">
                    <!-- <div class="lang-switcher">
                    <button class="lang-btn active" id="btn-en" onclick="switchLang('en')">EN</button>
                    <button class="lang-btn" id="btn-te" onclick="switchLang('te')">తెలుగు</button>
                </div> -->
                    <div class="service-badge d-flex justify-content"><?php echo htmlspecialchars($blog['service_name'] ?? 'Dental Care'); ?></div>

                </div>

                <h4 class="fw-bold mb-4" id="title-1" style="color: #943483;"><?php echo $blog['title']; ?></h4>

                <div class="row align-items-center mb-4">
                    <div class="col-lg-7 order-2 order-lg-1">
                        <div class="content-box overlap-right">
                            <p class="text-#943483 small mb-0" id="short-1"><?php echo getLimitWords($blog['main_content'], 15); ?>...</p>
                            <button class="btn btn-dental" id="btn-1" onclick="toggleSection('1')">Read More</button>
                        </div>
                    </div>
                    <div class="col-lg-5 order-1 order-lg-2 img-wrapper">
                        <img src="./admin/uploads/photos/<?php echo $blog['main_image']; ?>" alt="Blog Image">
                    </div>
                </div>
                <div id="full-1" class="hidden-content mb-5"></div>

                <?php if (!empty($blog['section1_image'])): ?>
                    <div class="row align-items-center mb-4">
                        <div class="col-lg-5 img-wrapper">
                            <img src="./admin/uploads/photos/<?php echo $blog['section1_image']; ?>" alt="Blog Image 2">
                        </div>
                        <div class="col-lg-7">
                            <div class="content-box overlap-left">
                                <p class="text-#943483 small mb-0" id="short-2"><?php echo getLimitWords($blog['full_content'], 15); ?>...</p>
                                <button class="btn btn-dental" id="btn-2" onclick="toggleSection('2')">Read More</button>
                            </div>
                        </div>
                    </div>
                    <div id="full-2" class="hidden-content mb-5"></div>
                <?php endif; ?>
            </div>

            <div class="col-lg-4">


                <div class="sidebar-widget">
                    <span class="sidebar-title">Key Points</span>
                    <?php
                    if (!empty($blog['keypoints'])) {
                        $points = explode(',', $blog['keypoints']);
                        foreach ($points as $p) {
                            echo '<div class="key-point"><i class="fas fa-check-circle me-2 text-success"></i>' . htmlspecialchars(trim($p)) . '</div>';
                        }
                    }
                    ?>
                </div>

                <div class="sidebar-widget">
                    <span class="sidebar-title">Tags</span>
                    <div class="mt-2">
                        <?php
                        if (!empty($blog['hashtags'])) {
                            $tags = explode(',', $blog['hashtags']);
                            foreach ($tags as $t) {
                                echo '<span class="tag-badge">#' . htmlspecialchars(trim($t)) . '</span>';
                            }
                        }
                        ?>
                    </div>
                </div>



                <div class="sidebar-widget">
                    <span class="sidebar-title">Our Services</span>

                    <div class="mt-3 custom-scroll-container" style="max-height: 320px; overflow-y: auto; padding-right: 5px;">

                        <a href="root-canal-treatments-in-guntur.php" class="key-point d-flex align-items-center text-decoration-none" style="display: flex; margin-bottom: 8px;">
                            <i class="fas fa-check-circle me-2 text-warning"></i> Root Canal Treatment
                        </a>

                        <a href="wisdom-tooth-removel-treatments-in-guntur.php" class="key-point d-flex align-items-center text-decoration-none" style="display: flex; margin-bottom: 8px;">
                            <i class="fas fa-check-circle me-2 text-warning"></i> Wisdom Tooth Removel
                        </a>

                        <a href="jaw-pain-correction-treatments-in-guntur.php" class="key-point d-flex align-items-center text-decoration-none" style="display: flex; margin-bottom: 8px;">
                            <i class="fas fa-check-circle me-2 text-warning"></i> Jaw Pain / Correction
                        </a>

                        <a href="dentaldentures-treatments-in-guntur.php" class="key-point d-flex align-items-center text-decoration-none" style="display: flex; margin-bottom: 8px;">
                            <i class="fas fa-check-circle me-2 text-warning"></i> Dentures
                        </a>

                        <a href="dentalcrown-bridge-treatment-in-guntur.php" class="key-point d-flex align-items-center text-decoration-none" style="display: flex; margin-bottom: 8px;">
                            <i class="fas fa-check-circle me-2 text-warning"></i> Crowns & Bridges
                        </a>

                        <a href="dental-implants-treatments-in-guntur.php" class="key-point d-flex align-items-center text-decoration-none" style="display: flex; margin-bottom: 8px;">
                            <i class="fas fa-check-circle me-2 text-warning"></i> Dental Implants<
                                </a>

                                <a href="invisalignaligners_clearaligners_treatment-in-guntur.php" class="key-point d-flex align-items-center text-decoration-none" style="display: flex; margin-bottom: 8px;">
                                    <i class="fas fa-check-circle me-2 text-warning"></i> Invisalign
                                </a>

                                <a href="dentalbraces-treatments-in-guntur.php" class="key-point d-flex align-items-center text-decoration-none" style="display: flex; margin-bottom: 8px;">
                                    <i class="fas fa-check-circle me-2 text-warning"></i> Braces
                                </a>


                                <a href="laminate-veneers-treatments-in-guntur.php" class="key-point d-flex align-items-center text-decoration-none" style="display: flex; margin-bottom: 8px;">
                                    <i class="fas fa-check-circle me-2 text-warning"></i> Laminate Veneers
                                </a>


                                <a href="smile-designing-treatments-in-guntur.php" class="key-point d-flex align-items-center text-decoration-none" style="display: flex; margin-bottom: 8px;">
                                    <i class="fas fa-check-circle me-2 text-warning"></i> Smile Designing
                                </a>

                                <a href="laser-assisted-teeth-cleaning-treatments-in-guntur.php" class="key-point d-flex align-items-center text-decoration-none" style="display: flex; margin-bottom: 8px;">
                                    <i class="fas fa-check-circle me-2 text-warning"></i> Laser assisted Teeth Cleaning
                                </a>
                                <a href="gum-depigmentation-treatments-in-guntur.php" class="key-point d-flex align-items-center text-decoration-none" style="display: flex; margin-bottom: 8px;">
                                    <i class="fas fa-check-circle me-2 text-warning"></i> Gum Depigmentation
                                </a>
                                <a href="laser-teeth-whitening-treatments-in-guntur.php" class="key-point d-flex align-items-center text-decoration-none" style="display: flex; margin-bottom: 8px;">
                                    <i class="fas fa-check-circle me-2 text-warning"></i> Laser Teeth Whitening
                                </a>
                                <a href="laser-gum-surgeries-treatments-in-guntur.php" class="key-point d-flex align-items-center text-decoration-none" style="display: flex; margin-bottom: 8px;">
                                    <i class="fas fa-check-circle me-2 text-warning"></i>Laser Gum Surgeries
                                </a>
                                <a href="mouth-ulcers-treatments-in-guntur.php" class="key-point d-flex align-items-center text-decoration-none" style="display: flex; margin-bottom: 8px;">
                                    <i class="fas fa-check-circle me-2 text-warning"></i>Mouth Ulcers
                                </a>
                                <a href="precancerous-lesion-in-mouth-treatments-in-guntur.php" class="key-point d-flex align-items-center text-decoration-none" style="display: flex; margin-bottom: 8px;">
                                    <i class="fas fa-check-circle me-2 text-warning"></i> Precancerous Lesion in Mouth
                                </a>

                                <a href="laser-crown-lengthening-treatments-in-guntur.php" class="key-point d-flex align-items-center text-decoration-none" style="display: flex; margin-bottom: 8px;">
                                    <i class="fas fa-check-circle me-2 text-warning"></i> Laser Crown Lengthening
                                </a>
                                <a href="pediatric-dentist-treatments-in-guntur.php" class="key-point d-flex align-items-center text-decoration-none" style="display: flex; margin-bottom: 8px;">
                                    <i class="fas fa-check-circle me-2 text-warning"></i> Pediatric Dentist
                                </a>
                                <a href="paedodontist-doctors-treatments-in-guntur.php" class="key-point d-flex align-items-center text-decoration-none" style="display: flex; margin-bottom: 8px;">
                                    <i class="fas fa-check-circle me-2 text-warning"></i> Paedodontist Doctors
                                </a>
                               

                    </div>
                </div>



            </div>
        </div>
    </div>

    <script>
        const blogData = {
            en: {
                title: <?php echo json_encode($blog['title']); ?>,
                short1: <?php echo json_encode(getLimitWords($blog['main_content'], 15)); ?>,
                remaining1: <?php echo json_encode(mb_substr($blog['main_content'], mb_strlen(getLimitWords($blog['main_content'], 15)))); ?>,
                short2: <?php echo json_encode(getLimitWords($blog['full_content'] ?? '', 15)); ?>,
                remaining2: <?php echo json_encode(mb_substr($blog['full_content'] ?? '', mb_strlen(getLimitWords($blog['full_content'] ?? '', 15)))); ?>
            },
            te: {
                title: <?php echo json_encode($blog['telugu_title'] ?: $blog['title']); ?>,
                short1: <?php echo json_encode(getLimitWords($blog['telugu_main_content'] ?: $blog['main_content'], 15)); ?>,
                remaining1: <?php echo json_encode(mb_substr($blog['telugu_main_content'] ?: $blog['main_content'], mb_strlen(getLimitWords($blog['telugu_main_content'] ?: $blog['main_content'], 15)))); ?>,
                short2: <?php echo json_encode(getLimitWords($blog['telugu_full_content'] ?: ($blog['full_content'] ?? ''), 15)); ?>,
                remaining2: <?php echo json_encode(mb_substr($blog['telugu_full_content'] ?: ($blog['full_content'] ?? ''), mb_strlen(getLimitWords($blog['telugu_full_content'] ?: ($blog['full_content'] ?? ''), 15)))); ?>
            }
        };

        let currentLang = 'en';

        function switchLang(lang) {
            currentLang = lang;
            document.getElementById('title-1').innerText = blogData[lang].title;
            document.getElementById('short-1').innerText = blogData[lang].short1 + "...";
            if (document.getElementById('short-2')) document.getElementById('short-2').innerText = blogData[lang].short2 + "...";
            document.getElementById('btn-en').classList.toggle('active', lang === 'en');
            document.getElementById('btn-te').classList.toggle('active', lang === 'te');
            resetSection('1');
            if (document.getElementById('full-2')) resetSection('2');
        }

        function toggleSection(sid) {
            const fullDiv = document.getElementById('full-' + sid);
            const btn = document.getElementById('btn-' + sid);
            const content = blogData[currentLang]['remaining' + sid].replace(/\n/g, "<br>");
            if (fullDiv.style.display === "block") {
                fullDiv.style.display = "none";
                btn.innerText = "Read More";
            } else {
                fullDiv.innerHTML = content;
                fullDiv.style.display = "block";
                btn.innerText = "Read Less";
                fullDiv.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
            }
        }

        function resetSection(sid) {
            const fullDiv = document.getElementById('full-' + sid);
            if (fullDiv) {
                fullDiv.style.display = "none";
                document.getElementById('btn-' + sid).innerText = "Read More";
            }
        }
    </script>
</body>

</html>