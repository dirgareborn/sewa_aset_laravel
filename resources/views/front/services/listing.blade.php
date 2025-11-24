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
    @include('front.partials.breadcumb')

    <!-- Product Grid Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-4">
                
                <h2 class="mb-4">{{ $page_title }}</h2>

        @if($unit)
            <p class="text-muted mb-4">Unit: {{ $unit->name }}</p>
        @endif

        @if($services->count())
            <div class="row g-4">
                @foreach($services as $service)
                    @php
                        $priceInfo = \App\Services\ServicePriceService::getPrice($service, auth()->user()?->customer_type ?? 'umum');
                    @endphp
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm">
                            @if($service->slides->first()?->image)
                                <img src="{{ asset('storage/uploads/services/slides/'.$service->slides->first()->image) }}" class="card-img-top" alt="{{ $service->name }}">
                            @endif
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $service->name }}</h5>
                                <p class="card-text text-muted mb-2">{{ Str::limit($service->description, 80) }}</p>
                                <p class="mb-2">
                                    <span class="badge bg-success">Rp {{ number_format($priceInfo['final_price'],0,',','.') }}</span>
 
                                </p>
                                <a href="{{ route('front.services.show', [$service->unit->slug, $service->slug]) }}" class="btn btn-primary mt-auto">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $services->links('pagination::bootstrap-5') }}
            </div>
        @else
            <p class="text-muted">Belum ada layanan/aset ditemukan.</p>
        @endif
            </div>
        </div>
    </div>
    <!-- Product Grid End -->

    <!-- Custom Styles for hover and card -->
@endsection
