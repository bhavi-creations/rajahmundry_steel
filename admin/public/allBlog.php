<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Vision Dental care - Dashboard</title>

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <div id="wrapper">

        <?php
        include 'sidebar.php';
        ?>
        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <?php
                include 'navbar.php';
                ?>
                <div class="container-fluid">

                    <style>
                        .card-custom {
                            margin: 6px;
                        }
                    </style>

                    <div class="container">
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h2 class="h2 mb-0 text-info mx-2"> Published Blogs</h2>
                            <a href="newBlog.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Create Blog</a>
                        </div>

                        <div class='row row-custom no-gutters'>
                            <?php
                            // Database connection
                            include '../../db.connection/db_connection.php';

                            // --- SLUG LOGIC INCLUDED IN SQL ---
                            $sql = "SELECT id, slug, title, main_content, main_image FROM blogs ORDER BY created_at DESC";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    // Determine image to display
                                    $image_path = !empty($row['main_image']) ? "../uploads/photos/{$row['main_image']}" : "https://mailrelay.com/wp-content/uploads/2018/03/que-es-un-blog-1.png";

                                    // SEO Friendly URL Link logic
                                    $blog_link = !empty($row['slug']) ? $row['slug'] : "fullblog_newpage.php?id=" . $row['id'];

                                    echo "
                                    <div class='col-12 col-md-4 col-custom'>
                                        <div class='card card-custom'>
                                            <img src='{$image_path}' class='card-img-top' alt='Blog Image' style='height:200px; object-fit: cover;'>
                                            <div class='card-body'>
                                                <h5 class='card-title' style='color:black;'>{$row['title']}</h5>
                                                <p class='card-text'>" . substr(strip_tags($row['main_content']), 0, 100) . "...</p>
                                                
                                                <div class='row'>
                                                    <a href='editBlog.php?id={$row['id']}' class='btn btn-warning col-xl-5 mx-2 my-2'>Edit Blog</a>
                                                    <a href='deleteBlog.php?id={$row['id']}' class='col-xl-5 btn btn-danger mx-2 my-2'>Delete</a>
                                                </div>

                                                <div class='row px-2'>
                                                     <a href='{$blog_link}' target='_blank' class='btn btn-info btn-block'><i class='fas fa-eye'></i> View Live Blog</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    ";
                                }
                            } else {
                                echo "<p>No blog posts found.</p>";
                            }

                            $conn->close();
                            ?>
                        </div>
                    </div>

                </div>
            </div>
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <div class="footer-widget__copyright">
                            <p class="mini_text" style="color:black"> ©2024 Visiondentacure . All Rights Reserved. Designed &
                                Developed by <a href="https://bhavicreations.com/" target="_blank" style="text-decoration: none;color:black">Bhavi
                                    Creations</a></p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>