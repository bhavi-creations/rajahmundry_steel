<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Vision Dental - Gallery Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        .card-custom {
            margin: 6px;
        }
    </style>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include 'navbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h2 class="h2 mb-0 text-info mx-2">Gallery Items</h2>
                        <a href="newgallery.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                            <i class="fas fa-plus fa-sm text-white-50"></i> Add New Image
                        </a>
                    </div>

                    <div class="row row-custom no-gutters">
                        <?php
                        // Database connection
                        include '../../db.connection/db_connection.php';

                        // Fetch gallery data
                        $sql = "SELECT id, title, image FROM gallery ORDER BY created_at DESC";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $image_path = !empty($row['image']) ? "../uploads/gallery/{$row['image']}" : "https://via.placeholder.com/300x200.png?text=No+Image";

                                echo "
                                <div class='col-12 col-md-4 col-custom'>
                                    <div class='card card-custom'>
                                        <img src='{$image_path}' class='card-img-top' alt='Gallery Image' style='height:200px; object-fit:cover;'>
                                        <div class='card-body'>
                                            <h5 class='card-title' style='color:black;'>{$row['title']}</h5>
                                            <div class='row'>
                                                <a href='editGallery.php?id={$row['id']}' class='btn btn-warning col-xl-4 mx-3 my-2'>Edit</a>
                                                <a href='deleteGallery.php?id={$row['id']}' class='col-xl-4 btn btn-danger mx-3 my-2'>Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                ";
                            }
                        } else {
                            echo "<p>No gallery items found.</p>";
                        }

                        $conn->close();
                        ?>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <p class="mini_text" style="color:black">
                            ©2024 Vision Dental. All Rights Reserved. Designed & Developed by 
                            <a href="https://bhavicreations.com/" target="_blank" style="text-decoration: none;color:black">Bhavi Creations</a>
                        </p>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
