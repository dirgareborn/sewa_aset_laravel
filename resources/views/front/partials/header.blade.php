<div class="container-fluid bg-white p-0" style="margin-bottom:0!important;">
    <div class="row g-0 align-items-center flex-column-reverse flex-md-row" style="min-height:200px!important;">
        <!-- Bagian Teks -->
        <div class="col-md-6 p-4 mt-md-0">
            <h2 class="fw-bold text-primary mb-2 animated fadeIn">{{ $page_title ?? '' }}</h2>
            <nav aria-label="breadcrumb" class="animated fadeIn">
                <ol class="breadcrumb text-uppercase mb-0">
                    @for ($i = 1; $i <= count(Request::segments()); $i++)
                        <li class="breadcrumb-item">
                            <a href="{{ URL::to(implode('/', array_slice(Request::segments(), 0, $i, true))) }}">
                                {{ strtoupper(Request::segment($i)) }}
                            </a>
                        </li>
                    @endfor
                </ol>
            </nav>
        </div>

        <!-- Bagian Gambar -->
        <div class="col-md-6 animated fadeIn" style="height:200px!important; overflow:hidden;">
            <img 
                src="{{ asset('front/img/header.jpg') }}" 
                alt="Header" 
                class="img-fluid w-100 h-100"
                style="object-fit:cover; object-position:center center; display:block;">
        </div>
    </div>
</div>

<!-- Strip Warna Bawah -->
<div class="container-fluid bg-primary wow fadeIn" 
     data-wow-delay="0.1s" 
     style="padding:20px 0!important; margin-bottom:0px!important;">
</div>
