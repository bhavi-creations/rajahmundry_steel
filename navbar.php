<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rajahmundry-Steels</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500;600;700&family=Orbitron:wght@400;500;600;700;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <link rel="stylesheet" href="./assets/css/style.css">


    <!-- image slider  -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/freeps2/a7rarpress@main/swiper-bundle.min.css">

</head>

<body>

    <!-- ==================== THEME SWITCHER ==================== -->
    <div class="theme-switcher">
        <button class="theme-btn default" onclick="setTheme('default')" title="Steel Blue & Orange"></button>
        <button class="theme-btn monochrome" onclick="setTheme('monochrome')" title="Black & White"></button>
        <button class="theme-btn industrial" onclick="setTheme('industrial')" title="Industrial Yellow"></button>
        <button class="theme-btn teal" onclick="setTheme('teal')" title="Teal & Copper"></button>
        <button class="theme-btn navy" onclick="setTheme('navy')" title="Navy & Gold"></button>
        <button class="theme-btn crimson" onclick="setTheme('crimson')" title="Charcoal & Crimson"></button>
    </div>

    <!-- ==================== NAVBAR ==================== -->
    <div class="steel-nav-wrapper">
        <div class="container-xl">
            <nav class="navbar navbar-expand-lg steel-navbar">
                <div class="container-fluid px-2">
                    <div class="d-none d-md-block">
                        <a class="navbar-brand" href="#home">
                            <img src="./assets/img/rjy_steels.png" alt="RJY Steels" class="img-fluid "
                                style="width: 150px; height: auto;">
                        </a>
                    </div>

                    <div class="d-block d-md-none">
                        <a class="navbar-brand" href="#home">
                            <img src="./assets/img/rjy_steels.png" alt="RJY Steels" class="img-fluid "
                                style="width: 100px; height: auto;">
                        </a>
                    </div>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarContent">
                        <ul class="navbar-nav ms-auto align-items-lg-center">
                            <li class="nav-item">
                                <a class="nav-link active" href="index.php#home">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php#about">About</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php#services">Services</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php#gallery">Gallery</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="blogs.php">Blog</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php#testimonials">Reviews</a>
                            </li>
                            <li class="nav-item ms-lg-3">
                                <a href="index.php#contact" class="btn-steel-quote">
                                    Contact
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </div>