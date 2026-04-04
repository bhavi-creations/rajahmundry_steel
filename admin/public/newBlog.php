<?php
include '../../db.connection/db_connection.php'; // DB connection

// Fetch all services from services table
$services_result = $conn->query("SELECT id, service_name FROM services ORDER BY service_name ASC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Vision dental hospital - Dashboard</title>

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">

        <?php include 'sidebar.php'; ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include 'navbar.php'; ?>

                <div class="container-fluid">

                    <h1 class="h3 mb-4 text-gray-800">CREATE BLOG</h1>

                    <div class="card shadow mb-4">
                        <div class="card-body">

                            <form id="addblogform" action="addBlog.php" method="POST" enctype="multipart/form-data">

                                <h4 class="text-primary">English Content</h4>
                                <hr>

                                <div class="mb-3">
                                    <label>English Title</label>
                                    <input type="text" class="form-control" name="title" id="blog_title" required>
                                </div>

                                <div class="mb-3">
                                    <label class="text-danger"><b>Custom URL Slug (Meeru URL lo emi ravalo ikkada rayandi)</b></label>
                                    <input type="text" class="form-control" name="slug" id="blog_slug" placeholder="e.g. best-dental-clinic-in-kakinada" required>
                                    <small class="text-muted">Space badalu dash (-) use cheyyandi. Example: my-new-blog</small>
                                </div>

                                <div class="mb-3">
                                    <select id="service" name="service" class="form-control" required>
                                        <option value="">Select a Service</option>
                                        <?php if ($services_result->num_rows > 0): ?>
                                            <?php while ($service = $services_result->fetch_assoc()): ?>
                                                <option value="<?= htmlspecialchars($service['service_name']) ?>">
                                                    <?= htmlspecialchars($service['service_name']) ?>
                                                </option>
                                            <?php endwhile; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>


                                <div class="mb-3">
                                    <label>English Main Content</label>
                                    <div id="mainEditor" style="height:200px;"></div>
                                    <input type="hidden" name="main_content" id="mainContentData">
                                </div>

                                <div class="mb-3">
                                    <label>English Full Content</label>
                                    <div id="fullEditor" style="height:300px;"></div>
                                    <input type="hidden" name="full_content" id="fullContentData">
                                </div>

                                <h4 class="text-primary mt-5">Telugu Content</h4>
                                <hr>

                                <div class="mb-3">
                                    <label>Telugu Title</label>
                                    <input type="text" class="form-control" name="telugu_title" required>
                                </div>

                                <div class="mb-3">
                                    <label>Telugu Main Content</label>
                                    <div id="teluguMainEditor" style="height:200px;"></div>
                                    <input type="hidden" name="telugu_main_content" id="teluguMainData">
                                </div>

                                <div class="mb-3">
                                    <label>Telugu Full Content</label>
                                    <div id="teluguFullEditor" style="height:300px;"></div>
                                    <input type="hidden" name="telugu_full_content" id="teluguFullData">
                                </div>

                                <h4 class="text-primary mt-5">Images</h4>
                                <hr>

                                <div class="mb-3">
                                    <label>Main Image</label>
                                    <input type="file" class="form-control" name="main_image">
                                </div>

                                <div class="mb-3">
                                    <label>Section 1 Image</label>
                                    <input type="file" class="form-control" name="section1_image">
                                </div>

                                <button type="reset" class="btn btn-danger">Clear</button>
                                <button type="submit" class="btn btn-success">Publish</button>

                            </form>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

    <script>
        const quillMain = new Quill("#mainEditor", {
            theme: "snow"
        });
        const quillFull = new Quill("#fullEditor", {
            theme: "snow"
        });
        const tqMain = new Quill("#teluguMainEditor", {
            theme: "snow"
        });
        const tqFull = new Quill("#teluguFullEditor", {
            theme: "snow"
        });

        // Auto-generate slug from title (Optional but helpful)
        document.getElementById('blog_title').addEventListener('input', function() {
            let title = this.value;
            let slug = title.toLowerCase().split(' ').join('-').replace(/[^\w-]+/g, '');
            document.getElementById('blog_slug').value = slug;
        });

        document.querySelector("#addblogform").onsubmit = function() {
            document.querySelector("#mainContentData").value = quillMain.root.innerHTML;
            document.querySelector("#fullContentData").value = quillFull.root.innerHTML;
            document.querySelector("#teluguMainData").value = tqMain.root.innerHTML;
            document.querySelector("#teluguFullData").value = tqFull.root.innerHTML;
        };
    </script>

</body>

</html>



<!-- <div class="mb-3">
                                            <label class="form-label text-primary">Select Service</label>
                                            <select id="service" name="service" class="form-control" required>
                                                <option value="">Select a Service</option>
                                                <option value="Root Canal">Root Canal</option>
                                                <option value="Teeth Braces">Teeth Braces</option>
                                                <option value="Pediatric Dentist">Pediatric Dentist</option>
                                                <option value="Paedodontist Doctors">Paedodontist Doctors </option>
                                                <option value="Clear Aligners">Clear Aligners</option>
                                                <option value="Laminate Veneers">Laminate Veneers</option>
                                                <option value="Crown Bridge">Crown & Bridge</option>
                                                <option value="Dental Implants">Dental Implants</option>
                                                <option value="Dentures Treatment">Dentures</option>
                                                <option value="Invisalign">Invisalign </option>
                                                <option value="Jaw Corrective">Jaw Corrective</option>
                                                <option value="Laser Gum">Laser & Gum</option>
                                                <option value="Smile Designing">Smile Designing</option>
                                                <option value="Smile Makeover">Smile Makeover</option>
                                                <option value="Teeth Alignment">Teeth Alignment</option>
                                                <option value="Tooth Extraction">Tooth Extraction</option>
                                                <option value="Tooth Cleaning">Teeth Cleaning</option>
                                                <option value="Gum Depigment">Gum Depigment</option>
                                                <option value="Teeth Whitening">Teeth Whitening</option>
                                                <option value="Laser Gum Surgery">Laser Gum Surgery </option>
                                                <option value="Mouth Ulcers">Mouth Ulcers </option>
                                                <option value="Precancerous Lesion">Precancerous Lesion</option>
                                                <option value="Laser Crown Lengthening">Laser Crown Lengthening</option>

                                            </select>
                                        </div> -->