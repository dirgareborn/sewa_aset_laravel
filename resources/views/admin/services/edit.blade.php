@extends('admin.layout.app')

@push('style')
    <style>
        .field_wrapper {
            display: none;
        }

        .form-attribute {
            width: 20%;
            padding: .375rem .75rem;
        }

        .step {
            display: none;
        }

        .step.active {
            display: block;
        }

        .step-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .preview-img {
            width: 120px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }
    </style>
@endpush

@section('content')
    <section class="content">
        <div class="container-fluid">

            <div class="card card-default">
                <div class="card-header">
                    <a href="{{ url('admin/services') }}" class="btn btn-sm btn-info float-right">
                        <i class="fas fa-backward"></i> Kembali
                    </a>
                    <h3 class="card-title">Edit Layanan</h3>
                </div>

                <div class="card-body">
                    @include('admin.partials.alert')

                    <form id="serviceForm" action="{{ route('admin.services.update', $service->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- ======================= STEP 1 ======================= --}}
                        <div class="step active" id="step-1">
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nama Layanan</label>
                                        <input type="text" name="name" class="form-control"
                                            value="{{ old('name', $service->name) }}" placeholder="Ketikkan Nama Layanan">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Unit Bisnis</label>
                                        <select class="form-control select2bs4" name="unit_id">
                                            <option value="">--- Pilih Unit Bisnis ---</option>
                                            @foreach ($units as $u)
                                                <option value="{{ $u->id }}"
                                                    {{ $u->id == $service->unit_id ? 'selected' : '' }}>
                                                    {{ $u->department->name }} - {{ $u->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Lokasi</label>
                                        <select class="form-control select2bs4" name="location_id">
                                            <option value="">--- Pilih Lokasi ---</option>
                                            @foreach ($locations as $loc)
                                                <option value="{{ $loc->id }}"
                                                    {{ $loc->id == $service->location_id ? 'selected' : '' }}>
                                                    {{ $loc->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Harga</label>
                                        <input type="number" min="0" max="999999" name="base_price"
                                            class="form-control" value="{{ old('base_price', $service->base_price) }}"
                                            placeholder="Masukkan harga dasar">
                                    </div>
                                </div>

                                {{-- Harga Per Tipe --}}
                                <div class="col-md-12 mt-2 border-top pt-2 d-flex align-items-center">
                                    <input type="checkbox" class="me-2" name="is_price_per_type" value="1"
                                        {{ $service->is_price_per_type ? 'checked' : '' }}>
                                    <label class="ms-1">Gunakan Harga Per Tipe Pengguna</label>
                                </div>

                                <div class="col-md-12 field_wrapper"
                                    style="{{ $service->is_price_per_type ? '' : 'display:none;' }}">
                                    @if ($service->prices && count($service->prices) > 0)
                                        @foreach ($service->prices as $p)
                                            <div class="mt-2">
                                                <select class="form-attribute" name="customer_type[]">
                                                    <option value="umum"
                                                        {{ $p->customer_type == 'umum' ? 'selected' : '' }}>Umum</option>
                                                    <option value="civitas"
                                                        {{ $p->customer_type == 'civitas' ? 'selected' : '' }}>Civitas
                                                    </option>
                                                    <option value="mahasiswa"
                                                        {{ $p->customer_type == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa
                                                    </option>
                                                </select>

                                                <input class="form-attribute" type="number" min="0"
                                                    placeholder="Harga" name="price[]" value="{{ $p->price }}">

                                                <a href="javascript:void(0);"
                                                    class="add_button btn btn-info btn-sm">Tambah</a>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="mt-2">
                                            <select class="form-attribute" name="customer_type[]">
                                                <option value="">--- Pilih Tipe Pengguna ---</option>
                                                <option value="umum">Umum</option>
                                                <option value="civitas">Civitas</option>
                                                <option value="mahasiswa">Mahasiswa</option>
                                            </select>

                                            <input type="number" class="form-attribute" name="price[]" placeholder="Harga">
                                            <a href="javascript:void(0);" class="add_button btn btn-info btn-sm">Tambah</a>
                                        </div>
                                    @endif

                                </div>

                            </div>

                            <div class="step-buttons">
                                <button type="button" class="btn btn-primary next-step">Lanjut</button>
                            </div>
                        </div>

                        {{-- ======================= STEP 2 ======================= --}}
                        <div class="step" id="step-2">
                            <div class="row">

                                <div class="col-md-12">
                                    <label>Foto Slide</label>
                                    <input type="file" name="slides[]" id="slideInput" class="form-control" multiple>
                                </div>
                                <div class="col-md-12">
                                    @forelse($service->slides as $slide)
                                        <div class="mb-2 d-flex align-items-center">
                                            <a target="_blank"
                                                href="{{ asset('storage/uploads/services/slides/' . $slide->image) }}">
                                                <img style="max-width:150px;"
                                                    src="{{ asset('storage/uploads/services/slides/' . $slide->image) }}"
                                                    alt="Slide Image">
                                            </a>
                                            <a href="javascript:void(0)" class="confirmDelete ms-2"
                                                record="service-image-slide" recordid="{{ $slide->id }}"
                                                title="Hapus Foto Slide">
                                                <i class="fas fa-trash text-danger"></i>
                                            </a>
                                            <input type="hidden" name="current_slide_image[]"
                                                value="{{ $slide->image }}">
                                        </div>
                                    @empty
                                        <p>Tidak ada foto slide.</p>
                                    @endforelse
                                </div>
                                <div class="col-md-12 mt-3">
                                    <label>Fasilitas</label>
                                    <textarea name="facility" class="form-control" rows="3">{{ old('facility', $service->facility) }}</textarea>
                                </div>

                                <div class="step-buttons">
                                    <button type="button" class="btn btn-secondary prev-step">Kembali</button>
                                    <button type="submit" class="btn btn-success">Update</button>
                                </div>

                            </div>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let currentStep = 1;
            const totalSteps = document.querySelectorAll('.step').length;

            function showStep(step) {
                document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
                document.getElementById('step-' + step).classList.add('active');
            }

            document.querySelectorAll('.next-step').forEach(btn => {
                btn.addEventListener('click', () => {
                    if (currentStep < totalSteps) {
                        currentStep++;
                        showStep(currentStep);
                    }
                });
            });

            document.querySelectorAll('.prev-step').forEach(btn => {
                btn.addEventListener('click', () => {
                    if (currentStep > 1) {
                        currentStep--;
                        showStep(currentStep);
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // checkbox show/hide
            $('input[name="is_price_per_type"]').on('change', function() {
                if ($(this).is(':checked')) $('.field_wrapper').slideDown();
                else $('.field_wrapper').slideUp();
            });

            // dynamic repeat fields
            var maxField = 5;
            var addButton = $('.add_button');
            var wrapper = $('.field_wrapper');

            var fieldHTML = `
            <div class="mt-2">
                <select class="form-attribute" name="customer_type[]">
                    <option value="umum">Umum</option>
                    <option value="civitas">Civitas</option>
                    <option value="mahasiswa">Mahasiswa</option>
                </select>
                <input type="number" min="0" class="form-attribute" name="price[]" placeholder="Harga">
                <a href="javascript:void(0);" class="remove_button btn btn-danger btn-sm">Hapus</a>
            </div>`;

            var x = {{ count($service->prices) }};

            $(addButton).click(function() {
                if (x < maxField) {
                    x++;
                    $(wrapper).append(fieldHTML);
                } else {
                    alert('Maksimal 5 field.');
                }
            });

            $(wrapper).on('click', '.remove_button', function(e) {
                e.preventDefault();
                $(this).parent('div').remove();
                x--;
            });

            // Preview Image
            const slideInput = document.getElementById('slideInput');
            const slidePreview = document.getElementById('slidePreview');

            slideInput.addEventListener('change', function() {
                slidePreview.innerHTML = '';
                [...this.files].forEach(file => {
                    const reader = new FileReader();
                    reader.onload = e => {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.classList.add('preview-img');
                        slidePreview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
            });

        });
    </script>
    @include('admin.partials._swalDeleteConfirm')
@endpush
