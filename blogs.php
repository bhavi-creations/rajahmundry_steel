<?php
include './db.connection/db_connection.php'; // Include your database connection file

// Retrieve service filter from GET request
$service = isset($_GET['service']) ? $_GET['service'] : '';

// --- SLUG FIELD ADDED TO SQL ---
$sql = "SELECT id, slug, title, main_content, main_image, created_at FROM blogs";
if (!empty($service)) {
  $sql .= " WHERE service = ?";
}
$sql .= " ORDER BY created_at DESC";

// Initialize statement
$stmt = $conn->prepare($sql);

// Bind parameters if service is set
if (!empty($service)) {
  $stmt->bind_param("s", $service);
}

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();
?>


<?php include 'navbar.php'; ?>


<main class="blogs_section">
  <!-- <div class="container">
    <div class="filter_buttons redirect_section mt-4">
      <a href="blogs?service="><button class="redirect_blog_srivice">All</button></a>
      <a href="blogs?service=Root Canal"><button class="redirect_blog_srivice">Root Canal</button></a>
      <a href="blogs?service=Teeth Braces"><button class="redirect_blog_srivice">Teeth Braces</button></a>
      <a href="blogs?service=Pediatric Dentist"><button class="redirect_blog_srivice"> Pediatric Dentist</button></a>
      <a href="blogs?service=Paedodontist Doctors"><button class="redirect_blog_srivice">Paedodontist Doctors </button></a>
      <a href="blogs?service=Clear Aligners"><button class="redirect_blog_srivice"> Clear Aligners</button></a>
      <a href="blogs?service=Laminate Veneers"><button class="redirect_blog_srivice">Laminate Veneers</button></a>
      <a href="blogs?service=Crown Bridge"><button class="redirect_blog_srivice">Crown & Bridge</button></a>
      <a href="blogs?service=Dental Implant"><button class="redirect_blog_srivice">Dental Implant</button></a>
      <a href="blogs?service=Dentures Treatments"><button class="redirect_blog_srivice">Dentures Treatments</button></a>
      <a href="blogs?service=Invisalign"><button class="redirect_blog_srivice">Invisalign </button></a>
      <a href="blogs?service=Jaw Corrective"><button class="redirect_blog_srivice">Jaw Corrective</button></a>
      <a href="blogs?service=Laser Gum"><button class="redirect_blog_srivice">Laser & Gum</button></a>
      <a href="blogs?service=Smile Designing"><button class="redirect_blog_srivice">Smile Designing</button></a>
      <a href="blogs?service=Smile Makeover"><button class="redirect_blog_srivice">Smile Makeover</button></a>
      <a href="blogs?service=Teeth Alignment"><button class="redirect_blog_srivice"> Teeth Alignment</button></a>
      <a href="blogs?service=Tooth Extraction"><button class="redirect_blog_srivice">Tooth Extraction</button></a>
      <a href="blogs?service=Tooth Cleaning"><button class="redirect_blog_srivice">Tooth Cleaning</button></a>
      <a href="blogs?service=Gum Depigment"><button class="redirect_blog_srivice">Gum Depigment</button></a>
      <a href="blogs?service=Teeth Whitening"><button class="redirect_blog_srivice">Teeth Whitening</button></a>
      <a href="blogs?service=Laser Gum Surgery"><button class="redirect_blog_srivice">Laser Gum Surgery</button></a>
      <a href="blogs?service=Mouth Ulcers"><button class="redirect_blog_srivice">Mouth Ulcers</button></a>
      <a href="blogs?service=Precancerous Lesion"><button class="redirect_blog_srivice">Precancerous Lesion</button></a>
      <a href="blogs?service=Laser Crown Lengthening"><button class="redirect_blog_srivice">Laser Crown Lengthening</button></a>
    </div>
  </div> -->
  <div class="blog_section">
    <span class="section-label">Blogs</span>
  </div>
  <div class="container blog-sidebar-list" style="padding-top: 20px; padding-bottom: 20px;">
    <div class="row">
      <div class="col-lg-12">
        <div class="grid row">
          <?php
          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              $image_path = !empty($row['main_image']) ? "admin/uploads/photos/{$row['main_image']}" : "default_image.png";

              // --- SEO FRIENDLY LINK LOGIC ---
              // If slug is available, use it directly. Otherwise, fallback to fullblog_newpage.php?id=...
              $blog_link = !empty($row['slug']) ? $row['slug'] : "fullblog_newpage.php?id=" . $row['id'];

              echo "
              <div class='grid-item col-sm-12 col-lg-4 mb-5'>
                  <div class='post-box card_bg_div_box'>
                      <figure>
                          <a href='{$blog_link}'>
                              <img src='{$image_path}' alt='Blog Image' class='img-fluid blog_box_image' style='height:250px; width:100%; object-fit:cover;'>
                          </a>
                      </figure>
                      <div class='box-content'>
                          <h5 class='box-title'><a class='box-title' href='{$blog_link}'>" . htmlspecialchars($row['title']) . "</a></h5>
                          <p class='post-desc mt-5' style='text-align: justify;'>" . substr(strip_tags($row['main_content']), 0, 90) . "...</p>
                          <a href='{$blog_link}'><button class='blog_main_btn'>Read More..</button></a>
                      </div>
                  </div>
              </div>";
            }
          } else {
            echo "<p>No blog posts found.</p>";
          }
          ?>
        </div>
      </div>
    </div>
  </div>
</main>
<?php include('./footer.php'); ?>

<script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
<script src="assets/vendor/aos/aos.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
<script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>
<script src="assets/js/main.js"></script>

</body>

</html>

<?php
$stmt->close();
$conn->close();
?>