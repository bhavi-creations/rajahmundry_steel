
    <!-- ==================== FOOTER ==================== -->
    <footer class="footer">
        <div class="container-xl">
            <div class="row align-items-center g-3 mb-3">
                <div class="col-12 text-center">
                    <div
                        style="font-family:'Orbitron',sans-serif;font-size:clamp(1.2rem,3vw,1.8rem);font-weight:900;background:linear-gradient(135deg,var(--electric-blue),var(--molten-gold));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">
                        RAJAHMUNDRY STEELS
                    </div>
                    <p
                        style="font-size:11px;letter-spacing:3px;color:rgb(0, 0, 0);text-transform:uppercase;margin-top:4px; font-weight: 600; ">
                        Est. 1962 · 62 Years of Excellence</p>
                </div>
            </div>

            <div class="footer-links">
                <a href="#home" class="footer-link">Home</a>
                <a href="#about" class="footer-link">About</a>
                <a href="#services" class="footer-link">Services</a>
                <a href="#gallery" class="footer-link">Gallery</a>
                <a href="#testimonials" class="footer-link">Reviews</a>
                <a href="#contact" class="footer-link">Contact</a>
            </div>

            <div class="social-icons">
                <a href="#" class="social-icon" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" class="social-icon" aria-label="Twitter"><i class="bi bi-twitter"></i></a>
                <a href="#" class="social-icon" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                <a href="#" class="social-icon" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
                <a href="#" class="social-icon" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
            </div>

            <div class="footer-text mt-4">
                <p>&copy; 2026 Rajahmundry Steels. All rights reserved. &nbsp;·&nbsp; Trusted Since 1962.</p>
            </div>
        </div>
    </footer>

    <!-- ==================== SCRIPTS ==================== -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

    <script>
        // ---- Apply saved theme immediately (prevents flash) ----
        (function () {
            var saved = localStorage.getItem('selectedTheme') || 'default';
            if (saved !== 'default') document.documentElement.setAttribute('data-theme', saved);
        })();

        // ---- Theme Switcher ----
        function setTheme(theme) {
            if (theme === 'default') {
                document.documentElement.removeAttribute('data-theme');
            } else {
                document.documentElement.setAttribute('data-theme', theme);
            }
            localStorage.setItem('selectedTheme', theme);
        }

        // ---- Partner Swiper ----
        var swiper = new Swiper(".mySwiper", {
            slidesPerView: 3,
            spaceBetween: 20,
            loop: true,
            grabCursor: true,
            speed: 700,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            breakpoints: {
                0: { slidesPerView: 1, spaceBetween: 12 },
                480: { slidesPerView: 1, spaceBetween: 14 },
                576: { slidesPerView: 2, spaceBetween: 16 },
                992: { slidesPerView: 3, spaceBetween: 20 }
            }
        });

        // ---- Smooth Scroll ----
        document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
            anchor.addEventListener('click', function (e) {
                var targetId = this.getAttribute('href');
                if (targetId === '#') return;
                var target = document.querySelector(targetId);
                if (!target) return;
                e.preventDefault();
                var navHeight = document.querySelector('.steel-nav-wrapper').offsetHeight;
                var targetPos = target.getBoundingClientRect().top + window.pageYOffset - navHeight - 16;
                window.scrollTo({ top: targetPos, behavior: 'smooth' });

                // Close mobile nav if open
                var navCollapse = document.getElementById('navbarContent');
                if (navCollapse && navCollapse.classList.contains('show')) {
                    bootstrap.Collapse.getInstance(navCollapse)?.hide();
                }
            });
        });

        // ---- Active Nav Highlight ----
        window.addEventListener('scroll', function () {
            var sections = document.querySelectorAll('section[id], div[id="home"]');
            var navLinks = document.querySelectorAll('.nav-link[href^="#"]');
            var navH = document.querySelector('.steel-nav-wrapper').offsetHeight;
            var current = '';

            sections.forEach(function (section) {
                var top = section.getBoundingClientRect().top + window.pageYOffset - navH - 60;
                if (window.pageYOffset >= top) current = section.getAttribute('id');
            });

            navLinks.forEach(function (link) {
                link.classList.remove('active');
                if (link.getAttribute('href') === '#' + current) link.classList.add('active');
            });
        });

        // ---- Intersection Observer for Animations ----
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll(
                '.fade-in,.fade-in-up,.fade-in-down,.slide-in-up,.slide-in-left,.slide-in-right,.zoom-in'
            ).forEach(function (el) { observer.observe(el); });
        });

        // ---- Vision/Mission Tabs ----
        function switchMvgTab(event, contentId) {
            document.querySelectorAll('.mvg-content-panel').forEach(function (p) { p.classList.remove('mvg-active'); });
            document.querySelectorAll('.mvg-tab-trigger').forEach(function (b) { b.classList.remove('mvg-active'); });
            document.getElementById(contentId).classList.add('mvg-active');
            event.currentTarget.classList.add('mvg-active');
        }

        // ---- Testimonials Auto Slider ----
        (function () {
            var inner = document.getElementById('testiInner');
            var bar = document.getElementById('testiProgressBar');
            if (!inner) return;

            var cards = inner.querySelectorAll('.testi-card');
            var total = cards.length;
            var current = 0;
            var timer = null;

            function getVisible() { return window.innerWidth < 768 ? 1 : 2; }

            function getCardWidth() {
                if (!cards[0]) return 0;
                var gap = 18;
                return cards[0].offsetWidth + gap;
            }

            function slideTo(idx) {
                var vis = getVisible();
                var max = total - vis;
                if (idx > max) idx = 0;
                if (idx < 0) idx = max;
                current = idx;
                inner.style.transform = 'translateX(-' + (current * getCardWidth()) + 'px)';
                resetBar();
            }

            function resetBar() {
                if (timer) clearInterval(timer);
                bar.style.transition = 'none';
                bar.style.width = '0%';
                requestAnimationFrame(function () {
                    requestAnimationFrame(function () {
                        bar.style.transition = 'width 3.5s linear';
                        bar.style.width = '100%';
                    });
                });
                timer = setInterval(function () { slideTo(current + 1); }, 3500);
            }

            document.getElementById('tNext').addEventListener('click', function () { slideTo(current + 1); });
            document.getElementById('tPrev').addEventListener('click', function () { slideTo(current - 1); });

            resetBar();

            window.addEventListener('resize', function () {
                inner.style.transform = 'translateX(0)';
                current = 0;
                resetBar();
            });
        })();
    </script>
</body>

</html>