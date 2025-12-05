@extends('front.layouts.app')
@push('style')
    <style>
        .card img.transition {
            transition: transform 0.4s ease, filter 0.4s ease;
        }

        .card:hover img.transition {
            transform: scale(1.05);
            filter: brightness(0.95);
        }

        .card-body h5 {
            font-size: 1rem;
        }

        .card-body h6 {
            font-size: 0.95rem;
        }
    </style>
@endpush
@section('content')
    <!-- Property List Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-0 gx-5 align-items-end">
                <!-- <div class="col-lg-6"> -->
                <div class="text-start mx-auto mb-5 wow slideInLeft" data-wow-delay="0.1s">
                    <h1 class="mb-3">LAYANAN PENUNJANG AKADEMIK</h1>
                    <!-- <p>Eirmod sed ipsum dolor sit rebum labore magna erat. Tempor ut dolore lorem kasd vero ipsum sit eirmod sit diam justo sed rebum.</p> -->
                </div>
                <!-- </div> -->
            </div>
            <div class="tab-content">
                <div id="tab-1" class="tab-pane fade show p-0 active">
                    <div class="row g-4">
                        @foreach ($services as $service)
                            @php
                                $latitude = $service['locations']['latitude'];
                                $longitude = $service['locations']['longitude'];
                            @endphp

                            <div class="col-lg-3 col-md-6">
                                <div
                                    class="card h-100 border border-light shadow-sm position-relative overflow-hidden rounded-3">
                                    <!-- Product Image -->
                                    <div class="overflow-hidden position-relative" style="min-height: 220px;">
                                        <a href="{{ url('kategori/' . $service['unit']['url'] . '/' . $service['url']) }}">
                                            <img src="{{ is_product($service['service_image']) }}"
                                                class="card-img-top transition w-100 h-100" style="object-fit: cover;">
                                        </a>
                                        <!-- Badge -->
                                        <span class="badge bg-primary position-absolute top-0 start-0 m-3 py-1 px-2">Di
                                            Sewakan</span>
                                        <span
                                            class="badge bg-white text-primary position-absolute bottom-0 start-0 m-3 py-1 px-2 rounded-top">
                                            {{ $service['unit']['name'] }}
                                        </span>
                                    </div>

                                    <!-- Card Body -->
                                    <div class="card-body d-flex flex-column p-3">
                                        <h5 class="text-primary mb-2 fw-bold">@currency($service['price'])</h5>
                                        <a href="{{ url('kategori/' . $service['unit']['slug'] . '/' . $service['slug']) }}"
                                            class="h6 d-block mb-2 text-decoration-none text-dark">
                                            {{ $service['name'] }}
                                        </a>
                                        <p class="mb-3 text-muted small">
                                            <button class="btn btn-directions d-inline-flex align-items-center"
                                                id="btnDirections" data-lat="{{ $latitude }}"
                                                data-lng="{{ $longitude }}" title="Petunjuk Arah">
                                                <i class="fas fa-map-marker-alt me-2"></i>
                                                {{ $service['locations']['name'] }}
                                            </button>

                                        </p>
                                        <a href="{{ url('kategori/' . $service['unit']['slug'] . '/' . $service['slug']) }}"
                                            class="btn btn-outline-primary mt-auto">Cara Sewa</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container-fluid py-5">
        <div class="container">
            <h2 class="mb-4 text-center bg-title-orange">Pengetahuan Tanpa Informasi Hanyalah Potensi</h2>
            <div class="row g-4">
                @foreach ($informations as $info)
                    <div class="col-md-4">
                        <div class="card h-100 overflow-hidden position-relative" style="min-height: 220px;">
                            <img src="{{ first_image_or_default($info->content, asset('front/img/no-image.webp')) }}"
                                class="card-img-top transition w-100 h-100" alt="{{ $info->title }}"
                                style="object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title" title="{{ $info->title }}">{{ Str::limit($info->title, 30) }}</h5>
                                <p class="card-text text-truncate">{!! Str::limit(strip_tags($info->content), 150) !!}</p>
                                <a href="{{ route('informasi.show', $info->slug) }}"
                                    class="btn btn-sm btn-navy">Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $informations->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>
    <section class="team-section-parallax py-5">
        <!-- Particle Layer -->
        <canvas id="particle-canvas"></canvas>
        <div class="container py-5 text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s">
            <!-- Section Header -->
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-6">
                    <h2 class="section-title text-white">Badan Pengembangan Bisnis</h2>
                    <p class="bg-title-orange">Bersinergi Membangun Manfaat untuk Semua</p>
                </div>
            </div>

            <!-- Team Members -->
            <div class="row g-4">
                @foreach ($teams as $team)
                    <div class="col-lg-3 col-md-6">
                        <div class="team-card h-100">
                            <img src="{{ asset('storage/' . $team->image) }}" alt="{{ $team->name }}"
                                class="team-image w-100">

                            <div class="p-4">
                                <span class="role-tag">{{ $team->employee_id }}</span>
                                <h5>{{ $team->name }}</h5>

                                <p class="text-muted mb-4">
                                    Visionary leader with a passion for innovation and sustainable growth.
                                </p>

                                <div class="member-social d-flex">
                                    @foreach ($team->sosmed ?? [] as $sosmed)
                                        <a href="{{ $sosmed['url'] }}" class="social-link me-3" target="_blank">
                                            {{-- <i class="{{ $sosmed['socialmedia_icon'] }}"></i> --}}
                                            {{ ucfirst($sosmed['socialmedia_name']) }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>
@endsection
