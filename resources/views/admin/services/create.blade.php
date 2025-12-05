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
                    <h3 class="card-title">Tambah Layanan</h3>
                </div>

                <div class="card-body">
                    @include('admin.partials.alert')

                    <form id="serviceForm" action="{{ route('admin.services.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        {{-- =======================
                        STEP 1
                    ========================= --}}
                        <div class="step active" id="step-1">

                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nama Layanan</label>
                                        <input type="text" name="name" class="form-control"
                                            value="{{ old('name') }}" placeholder="Ketikkan Nama Layanan">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Unit Bisnis</label>
                                        <select class="form-control select2bs4" name="unit_id">
                                            <option value="">--- Pilih Unit Bisnis ---</option>
                                            @foreach ($units as $u)
                                                <option value="{{ $u->id }}">{{ $u->department->name }} -
                                                    {{ $u->name }}</option>
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
                                                <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Harga</label>
                                        <input type="number" min="0" max="999999" name="base_price"
                                            class="form-control" value="{{ old('base_price') }}"
                                            placeholder="Masukkan harga dasar">
                                    </div>
                                </div>

                                {{-- Harga Per Tipe --}}
                                <div class="col-md-12 mt-2 border-top pt-2 d-flex align-items-center">
                                    <input type="checkbox" class="me-2" name="is_price_per_type" value="1">
                                    <label class="ms-1">Gunakan Harga Per Tipe Pengguna</label>
                                </div>

                                <div class="col-md-12 field_wrapper">
                                    <div>
                                        <select class="form-attribute" name="customer_type[]">
                                            <option value="">--- Pilih Tipe Pengguna ---</option>
                                            <option value="umum">Umum</option>
                                            <option value="civitas">Civitas</option>
                                            <option value="mahasiswa">Mahasiswa</option>
                                        </select>

                                        <input class="form-attribute" type="number" min="0" placeholder="Harga"
                                            name="price[]">

                                        <a href="javascript:void(0);" class="add_button btn btn-info btn-sm">Tambah</a>
                                    </div>
                                </div>

                            </div>

                            <div class="step-buttons">
                                <button type="button" class="btn btn-primary next-step">Lanjut</button>
                            </div>

                        </div>


                        {{-- =======================
                        STEP 2
                    ========================= --}}
                        <div class="step" id="step-2">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Foto Slide</label>
                                    <input type="file" name="slides[]" class="form-control" multiple>
                                </div>
                                <div class="row col-md-12 mt-3" id="slidePreview" style="gap:10px;">

                                </div>
                                <div class="col-md-12 mt-3">
                                    <label>Fasilitas</label>
                                    <textarea name="facility" class="form-control" rows="3" placeholder="Fasilitas dipisahkan dengan koma"></textarea>
                                </div>

                                <div class="step-buttons">
                                    <button type="button" class="btn btn-secondary prev-step">Kembali</button>
                                    <button type="submit" class="btn btn-success">Simpan</button>
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
                if ($(this).is(':checked')) {
                    $('.field_wrapper').slideDown();
                } else {
                    $('.field_wrapper').slideUp();
                }
            });

            // dynamic repeat fields
            var maxField = 5;
            var addButton = $('.add_button');
            var wrapper = $('.field_wrapper');

            var fieldHTML = `
        <div class="mt-2">
            <select class="form-attribute" name="customer_type[]">
                <option value="">--- Pilih Tipe Pengguna ---</option>
                <option value="umum">Umum</option>
                <option value="civitas">Civitas</option>
                <option value="mahasiswa">Mahasiswa</option>
            </select>

            <input type="number" min="0" class="form-attribute" name="price[]" placeholder="Harga">

            <a href="javascript:void(0);" class="remove_button btn btn-danger btn-sm">Hapus</a>
        </div>
    `;

            var x = 1;

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

        });
    </script>
@endpush
