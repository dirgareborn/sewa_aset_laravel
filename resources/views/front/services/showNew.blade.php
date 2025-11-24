@extends('front.layouts.app')

@section('title', $page_title)
@push('style')
<script type="application/ld+json">
    {
      "@context": "https://schema.org/",
      "@type": "Product",
      "name": "{{ $service['name'] }}",
      "image": "{{ asset('strorage/uploads/service/slides/'. ($service->slides->first()->image ?? '')) }}",
      "description": "{{ $service['description'] ?? '' }}",
      "sku": "{{ $service['id'] }}",
      "offers": {
          "@type": "Offer",
          "url": "{{ url('unit-bisnis/'.$service['unit']['slug'].'/'. $service['slug']) }}",
          "priceCurrency": "IDR",
          "price": "{{ $service['base_price'] }}",
          "availability": "https://schema.org/InStock"
      }
  }
</script>
<style>
    img {
        max-width: 100%;
    }

    .preview {
        @media (min-width:320px) {
            .card {
                padding: 1em;
                margin: 10px 10px;
            }
        }

        @media (min-width:480px) {
            .card {
                padding: 1em;
                margin: 10px 10px;
            }
        }
    }

    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -webkit-flex-direction: column;
    -ms-flex-direction: column;
    flex-direction: column;
}

@media screen and (max-width: 996px) {
    .preview {
        margin-bottom: 20px;
    }
}

.preview-pic {
    -webkit-box-flex: 1;
    -webkit-flex-grow: 1;
    -ms-flex-positive: 1;
    flex-grow: 1;
}

.preview-thumbnail.nav-tabs {
    border: none;
    margin-top: 15px;
}

.preview-thumbnail.nav-tabs li {
    width: 18%;
    margin-right: 2.5%;
}

.preview-thumbnail.nav-tabs li img {
    max-width: 100%;
    display: block;
}

.preview-thumbnail.nav-tabs li a {
    padding: 0;
    margin: 0;
}

.preview-thumbnail.nav-tabs li:last-of-type {
    margin-right: 0;
}

.tab-content {
    overflow: hidden;
}

.tab-content img {
    width: 100%;
    -webkit-animation-name: opacity;
    animation-name: opacity;
    -webkit-animation-duration: .3s;
    animation-duration: .3s;
}

.card {
    margin: 10px 50px;
    background: #22222;
    padding: 0em;
    line-height: 1.5em;
}

@media screen and (min-width: 997px) {

    .wrapper {
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
    }
}

.details {
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -webkit-flex-direction: column;
    -ms-flex-direction: column;
    flex-direction: column;
}

.colors {
    -webkit-box-flex: 1;
    -webkit-flex-grow: 1;
    -ms-flex-positive: 1;
    flex-grow: 1;
}

.product-title,
.price,
.sizes,
.colors {
    text-transform: UPPERCASE;
    font-weight: bold;
}

.checked,
.price span {
    color: #ff9f1a;
}

.product-title,
.rating,
.product-description,
.price,
.vote,
.sizes {
    margin-bottom: 15px;
}

.product-title {
    margin-top: 0;
}

.size {
    margin-right: 10px;
}

.size:first-of-type {
    margin-left: 40px;
}

.color {
    display: inline-block;
    vertical-align: middle;
    margin-right: 10px;
    height: 2em;
    width: 2em;
    border-radius: 2px;
}

.color:first-of-type {
    margin-left: 20px;
}

.add-to-cart,
.like {
    background: #ff9f1a;
    padding: 1.2em 1.5em;
    border: none;
    text-transform: UPPERCASE;
    font-weight: bold;
    color: #fff;
    -webkit-transition: background .3s ease;
    transition: background .3s ease;
}

.add-to-cart:hover,
.like:hover {
    background: #b36800;
    color: #fff;
}



@-webkit-keyframes opacity {
    0% {
        opacity: 0;
        -webkit-transform: scale(3);
        transform: scale(3);
    }

    100% {
        opacity: 1;
        -webkit-transform: scale(1);
        transform: scale(1);
    }
}

@keyframes opacity {
    0% {
        opacity: 0;
        -webkit-transform: scale(3);
        transform: scale(3);
    }

    100% {
        opacity: 1;
        -webkit-transform: scale(1);
        transform: scale(1);
    }
}
.rating-stars .stars {
    display: flex;
    gap: 5px;
    cursor: pointer;
    font-size: 32px;
    color: #ccc;
}

.rating-stars .star.active {
    color: #ffc107; /* warna gold */
}

.rating-stars .star.hover {
    color: #ffdb70;
}
/*# sourceMappingURL=style.css.map */
</style>
@endpush
@section('content')
@include('front.partials.breadcumb')
<section class="service-detail py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-6">
                @if($service->slides->count())
                <div class="mb-3">
                    <img src="{{ asset('storage/uploads/services/slides/'.$service->slides->first()->image) }}" class="img-fluid rounded shadow-sm" alt="{{ $service->name }}">
                </div>
                @if($service->slides->count() > 1)
                <div class="d-flex gap-2 overflow-auto">
                    @foreach($service->slides as $slide)
                    <img src="{{ asset('storage/uploads/services/slides/'.$slide->image) }}" class="img-thumbnail" style="width:80px;height:80px;" alt="">
                    @endforeach
                </div>
                @endif
                @endif
            </div>
            <div class="col-md-6">
                <h2>{{ $service->name }}</h2>
                <p class="text-muted">{{ $service->unit->name }} / {{ $service->unit->parent?->name ?? '' }}</p>
                <p>{{ $service->description }}</p>

                <!-- <h4>Harga & Diskon</h4> -->
                <p>
                    <strong>Harga :</strong> Rp {{ number_format($priceInfo['service_price'],0,',','.') }} <br>
                </p>
                <ul class="list-unstyled">
                    <p class="">Fasilitas :</p>
                    @forelse($service['facility'] ?? []  as $facility)
                    <li class="">
                        <i class="fa fa-check-circle text-success me-2"></i>
                        <small>{{ $facility['name'] }}</small>
                    </li>
                    @empty
                    <li class="">
                        <i class="fa fa-times-circle text-danger me-2"></i>
                        <small>Tidak ada fasilitas</small>
                    </li>
                    @endforelse
                </ul>  
            @include('front.services._addToCart')
            </div>
            @include('front.services.rating')
        </div>
    </section>
    @endsection
    @push('scripts')
    <script type="text/javascript">
        var array = ["05-08-2024", "08-08-2024", "17-08-2024", "21-07-2024"];
        $(".date").datepicker({
            dateFormat: "yy-mm-dd",
            beforeShowDay: function(date) {
                var string = jQuery.datepicker.formatDate('dd-mm-yy', date);
                return [array.indexOf(string) == -1]; // disables dates in array
            }
        });
        document.addEventListener("DOMContentLoaded", function () {
            const stars = document.querySelectorAll('.rating-stars .star');
            const ratingInput = document.getElementById('rating-value');

            let currentRating = parseInt(ratingInput.value || 0);

    // Set initial rating on load (for edit form)
            highlightStars(currentRating);

            stars.forEach(star => {

        // Hover effect
                star.addEventListener('mouseover', function () {
                    highlightStars(this.dataset.value, true);
                });

        // Remove hover effect
                star.addEventListener('mouseout', function () {
                    highlightStars(currentRating);
                });

        // Click event
                star.addEventListener('click', function () {
                    currentRating = this.dataset.value;
                    ratingInput.value = currentRating;
                    highlightStars(currentRating);
                });
            });

            function highlightStars(rating, isHover = false) {
                stars.forEach(star => {
                    const starValue = parseInt(star.dataset.value);

                    star.classList.remove('active', 'hover');

                    if (starValue <= rating) {
                        star.classList.add(isHover ? 'hover' : 'active');
                    }
                });
            }
        });
    </script>
    @endpush
