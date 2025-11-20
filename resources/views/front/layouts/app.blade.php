<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $page_title ?? 'BPB - Universitas Negeri Makassar' }}</title>

    <meta name="robots" content="index, follow" />
    <meta name="author" content="{{ app_author() }}" />
    <meta name="version" content="{{ app_version() }}" />
    <meta name="last-updated" content="{{ app_last_updated() }}" />
    <meta name="theme-color" content="#0E2E50" />

    <!-- Schema.org -->
    <script type="application/ld+json">
      {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "Badan Pengembangan Bisnis UNM",
        "url": "{{ url('https://bpb.unm.ac.id') }}",
        "logo": "{{ is_logo($page_image ?? '') }}",
        "sameAs": [
          "https://www.facebook.com/bpb.unm",
          "https://x.com/bpb.unm",
          "https://www.instagram.com/bpb.unm"
        ]
      }
    </script>
    <meta name="description"
        content="{{ $page_description ?? 'Website Resmi Badan Pengembangan Bisnis Universitas Negeri Makassar' }}" />
    <meta name="keywords" content="Universitas Negeri Makassar" />
    <meta property="og:locale" content="id_ID" />
    <meta property="og:type" content="article" />
    <meta property="og:url" content="{{ Request::url() }}" />
    <meta property="og:site_name" content="{{ url('') }}" />
    <meta property="og:title" content="{{ $page_title ?? '' }}" />
    <meta property="og:description"
        content="{{ $page_description ?? 'Website Resmi Badan Pengembangan Bisnis Universitas Negeri Makassar' }}" />
    <meta property="og:image" content="{{ is_logo($page_image ?? '') }}?auto=format&fit=max&w=1200" />
    <meta property="og:image:alt" content="{{ is_logo($page_image ?? '') }}" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{{ $page_title ?? '' }}" />
    <meta name="twitter:description"
        content="{{ $page_description ?? 'Website Resmi Badan Pengembangan Bisnis Universitas Negeri Makassar' }}" />
    <meta name="twitter:image" content="{{ is_logo($page_image ?? '') }}?auto=format&fit=max&w=1200" />
    <meta name="facebook-domain-verification" content="w5e39xmuhdt35pjpezg5pkif7f501x" />
    <link rel="icon" type="image/png" href="{{ url('favicon.ico') }}" />
    <link rel="mask-icon" href="{{ url('favicon.ico') }}" color="#0E2E50" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap"
        rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="{{ asset('front/lib/animate/animate.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('front/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('front/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('front/css/style.min.css') }}" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.3/themes/smoothness/jquery-ui.min.css"
        rel="stylesheet" />
    @stack('style')
</head>

<body>
    <div class="container-fluid bg-white p-0">
        <div id="spinner"
            class="show d-flex justify-content-center align-items-center position-fixed w-100 vh-100 bg-white"
            style="z-index:9999;">
            <div class="spinner-border text-primary" style="width:3rem;height:3rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        @include('front.layouts.top-header')@include('front.partials.navbar')@if (Route::is('beranda') || Request::is('/'))
            @include('front.partials.slider')
        @endif
        <main>@yield('content')</main>
        @if (Route::is('beranda'))
            @include('front.partials.testimonial') @include('front.partials.mitra')
        @endif {{-- Footer --}} @include('front.partials.footer')
        @include('front.layouts.menu-bottom') {{-- Back to Top --}}
        <!-- <a href="#" class="btn btn-lg btn-warning btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a> -->
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('front/lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('front/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('front/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('front/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('front/js/custom.min.js') }}"></script>
    <script src="https://unpkg.com/feather-icons@4.29.2/dist/feather.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    @stack('scripts') @include('front.partials.chat')
    <script>
        const btnDirections = document.getElementById('btnDirections');
        if (btnDirections) {
            btnDirections.addEventListener('click', function() {
                const destLat = this.getAttribute('data-lat');
                const destLng = this.getAttribute('data-lng');
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const originLat = position.coords.latitude;
                            const originLng = position.coords.longitude;
                            const mapsUrl =
                                `https://www.google.com/maps/dir/?api=1&origin=${originLat},${originLng}&destination=${destLat},${destLng}&travelmode=driving`;

                            window.open(mapsUrl, '_blank');
                        },
                        function() {
                            // User menolak lokasi → fallback
                            const mapsUrl =
                                `https://www.google.com/maps/dir/?api=1&origin=My+Location&destination=${destLat},${destLng}&travelmode=driving`;
                            window.open(mapsUrl, '_blank');
                        }
                    );
                } else {
                    // Browser tidak support geolocation
                    const mapsUrl =
                        `https://www.google.com/maps/dir/?api=1&origin=My+Location&destination=${destLat},${destLng}&travelmode=driving`;
                    window.open(mapsUrl, '_blank');
                }
            });
        }
    </script>
    <div id="toastContainer" class="position-fixed top-50 start-50 translate-middle" style="z-index: 2000;">
        <div id="centerToast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive"
            aria-atomic="true" style="display: none; min-width: 250px; max-width: 350px;">
            <div class="d-flex">
                <div class="toast-body text-center fw-semibold" id="centerToastBody"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>
    <div id="cookieConsent" class="cookie-consent">
        <p>
            We are using cookies to give you the best experience. You can find out
            more about which cookies we are using or switch them off in privacy
            settings.
        </p>
        <div class="cookie-actions">
            <button id="acceptCookies" class="btn-accept">Accept</button>
            <a href="/kebijakan-privasi" class="btn-settings">Privacy Settings</a>
        </div>
    </div>
    <script>
        const canvas = document.getElementById("particle-canvas");
        if (canvas) {


            const ctx = canvas.getContext("2d");

            function resizeCanvas() {
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
            }
            resizeCanvas();
            window.addEventListener("resize", resizeCanvas);

            // Config
            const particleCount = 80;
            const maxDistance = 140;

            let particles = [];

            class Particle {
                constructor() {
                    this.x = Math.random() * canvas.width;
                    this.y = Math.random() * canvas.height;
                    this.vx = (Math.random() - 0.5) * 1;
                    this.vy = (Math.random() - 0.5) * 1;
                    this.size = 2;
                }

                update() {
                    this.x += this.vx;
                    this.y += this.vy;

                    if (this.x < 0 || this.x > canvas.width) this.vx *= -1;
                    if (this.y < 0 || this.y > canvas.height) this.vy *= -1;
                }

                draw() {
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                    ctx.fillStyle = "rgba(255,255,255,0.8)";
                    ctx.fill();
                }
            }

            function init() {
                particles = [];
                for (let i = 0; i < particleCount; i++) {
                    particles.push(new Particle());
                }
            }
            init();

            function animate() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                particles.forEach((p, i) => {
                    p.update();
                    p.draw();

                    // Connect with others if close enough
                    for (let j = i + 1; j < particles.length; j++) {
                        const p2 = particles[j];
                        const dx = p.x - p2.x;
                        const dy = p.y - p2.y;
                        const dist = Math.sqrt(dx * dx + dy * dy);

                        if (dist < maxDistance) {
                            ctx.beginPath();
                            ctx.strokeStyle = "rgba(255,255,255," + (1 - dist / maxDistance) + ")";
                            ctx.lineWidth = 1;
                            ctx.moveTo(p.x, p.y);
                            ctx.lineTo(p2.x, p2.y);
                            ctx.stroke();
                        }
                    }
                });

                requestAnimationFrame(animate);
            }
            animate();
        }
    </script>
</body>

</html>
