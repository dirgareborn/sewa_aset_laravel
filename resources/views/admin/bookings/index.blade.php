@extends('admin.layout.app')

@section('title', 'Manajemen Booking')

@section('content')
    <section class="content">
        <div class="container-fluid py-4">

            <div class="card clearfix border-0 shadow-sm">
                <div class="card-header bg-light py-3">
                    <div class="row align-items-center">

                        <!-- Kiri: Tombol -->
                        <div class="col-md-6 d-flex align-items-center gap-2">
                            <div class="btn-group mt-2">
                                <a href="#" class="btn btn-sm btn-info mb-2" data-toggle="modal"
                                    data-target="#addBookingModal" title="Tambah Booking">
                                    <i class="fas fa-plus"></i>
                                </a>

                                <a href="{{ route('admin.bookings.export') }}" target="_blank"
                                    class="btn btn-success btn-sm mb-2" title="Export Excel">
                                    <i class="fas fa-file-excel"></i>
                                </a>

                                <a href="{{ route('admin.bookings.exportPdf') }}" class="btn btn-danger btn-sm mb-2"
                                    title="Export PDF">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Kanan: Filter -->
                        <div class="col-md-6">
                            <form method="GET" class="d-flex justify-content-end align-items-center gap-2">

                                <input type="text" name="search" class="form-control form-control-sm"
                                    placeholder="Cari invoice / user..." value="{{ request('search') }}"
                                    style="max-width: 180px;">

                                <select name="status" class="form-select form-select-sm" style="max-width: 150px;">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                        Cancelled
                                    </option>
                                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired
                                    </option>
                                </select>

                                <button class="btn btn-primary btn-sm">
                                    <i class="fas fa-search me-1"></i> Filter
                                </button>
                            </form>
                        </div>

                    </div>
                </div>

                <!-- TABLE -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Invoice</th>
                                    <th>Pemesan</th>
                                    <th>Amount</th>
                                    <th>Diskon</th>
                                    <th>Status</th>
                                    <th>Tanggal Booking</th>
                                    <th>Email Sent</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($bookings as $booking)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>

                                        <td>
                                            <strong>{{ $booking->invoice_number }}</strong><br>
                                            <small class="text-muted">Token: {{ $booking->snap_token ?? '-' }}</small>
                                        </td>

                                        <td>
                                            {{ $booking->user->name ?? '-' }} <br>
                                            <small class="text-muted">{{ $booking->user->email ?? '' }}</small>
                                        </td>

                                        <td>Rp {{ number_format($booking->amount, 0, ',', '.') }}</td>

                                        <td>
                                            @if ($booking->coupon_code)
                                                <span class="badge bg-info">{{ $booking->coupon_code }}</span><br>
                                                <small class="text-success">
                                                    - Rp {{ number_format($booking->coupon_amount, 0, ',', '.') }}
                                                </small>
                                            @else
                                                <span class="text-muted">Tidak ada</span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            @php
                                                $color = match ($booking->status) {
                                                    'paid' => 'success',
                                                    'pending' => 'warning',
                                                    'cancelled' => 'danger',
                                                    'expired' => 'secondary',
                                                    default => 'dark',
                                                };
                                            @endphp
                                            <span
                                                class="badge bg-{{ $color }}">{{ ucfirst($booking->status) }}</span>
                                        </td>

                                        <td class="text-center">
                                            {{ date('d M Y H:i', strtotime($booking->booking_date)) }}
                                        </td>

                                        <td class="text-center">
                                            @if ($booking->email_sent_at)
                                                <span class="badge bg-success">
                                                    {{ date('d M Y H:i', strtotime($booking->email_sent_at)) }}
                                                </span>
                                            @else
                                                <span class="text-muted">Belum</span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            <a href="{{ route('admin.bookings.edit', $booking->id) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </a>

                                            <a href="{{ url('admin/booking-details/' . $booking->id) }}"
                                                class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>

                @if (method_exists($bookings, 'links'))
                    <div class="card-footer">
                        {{ $bookings->links('pagination::bootstrap-5') }}
                    </div>
                @endif

            </div>

        </div>

        {{-- Modal Tambah Booking jika dibutuhkan --}}
        @include('admin.bookings._bookingModal')

    </section>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            // Toggle new customer mode
            document.getElementById('new_customer_toggle').addEventListener('change', function() {
                const isNew = this.checked;
                document.getElementById('select_customer').style.display = isNew ? 'none' : 'block';
                document.getElementById('new_customer_name').style.display = isNew ? 'block' : 'none';
                document.getElementById('customer_type').style.display = isNew ? 'block' : 'none';
            });

            // Ketika user existing dipilih
            $('#select_customer').change(function() {
                var selectedType = $(this).find(':selected').data('type');
                if (selectedType) {
                    $('#customer_type_group').hide();
                    $('#customer_type').val(selectedType);
                    updateHarga(); // auto hitung harga
                }
            });

            // Bila admin ubah tipe customer (untuk new customer)
            $('#customer_type').change(updateHarga);

            // Bila admin ganti layanan
            $('[name="service_id"]').change(updateHarga);

            function updateHarga() {
                var serviceId = $('[name="service_id"]').val();
                var customerType = $('#customer_type').val();
                var startDate = $('#start_date').val();
                var endDate = $('#end_date').val();

                if (!serviceId || !customerType || !startDate || !endDate) {
                    $('#price_display').html(
                        'Lengkapi Layanan, tipe customer, dan tanggal dulu'
                    );
                    return;
                }

                $('#price_display').html('Menghitung harga...'); // Loading text

                $.ajax({
                    url: "{{ url('admin/get-price') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        service_id: serviceId,
                        customer_type: customerType,
                        start_date: startDate,
                        end_date: endDate
                    },
                    success: function(res) {
                        let total = parseFloat(res.total) || 0;
                        $('#price_display').html('Rp ' + res.price + ' x ' + res.qty +
                            ' hari = <b>Rp ' + res.total + '</b>');
                        $('#grand_total').val(total);
                    }
                });
            }



            function resetAvailability() {
                document.getElementById('availabilityResult').innerHTML = "";
                document.getElementById('btnSubmit').setAttribute('disabled', true);
            }

            // Cek Ketersediaan Jadwal


            $('#checkAvailability').click(function() {

                var startDate = $('#start_date').val();
                var endDate = $('#end_date').val();
                var serviceId = $('#service_id').val();

                // Validasi awal
                if (!startDate) {
                    $('#availabilityResult').html(
                        '<span class="text-warning fw-bold">Tanggal mulai harus diisi terlebih dahulu</span>'
                    );
                    return;
                }

                if (!endDate) {
                    $('#availabilityResult').html(
                        '<span class="text-warning fw-bold">Tanggal selesai harus diisi</span>');
                    return;
                }

                if (!serviceId) {
                    $('#availabilityResult').html(
                        '<span class="text-warning fw-bold">Pilih layanan terlebih dahulu</span>');
                    return;
                }

                if (new Date(startDate) > new Date(endDate)) {
                    $('#availabilityResult').html(
                        '<span class="text-danger fw-bold">Tanggal mulai tidak boleh lebih dari tanggal selesai</span>'
                    );
                    return;
                }

                $.ajax({
                    url: "{{ url('admin/check-availability') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        start_date: startDate,
                        end_date: endDate,
                        service_id: serviceId
                    },
                    success: function(response) {
                        if (response.status) {
                            $('#availabilityResult')
                                .html('<span class="text-success fw-bold">' + response.message +
                                    '</span>');
                            $('#btnSubmit').prop('disabled', false);
                        } else {
                            $('#availabilityResult')
                                .html('<span class="text-danger fw-bold">' + response.message +
                                    '</span>');
                            $('#btnSubmit').prop('disabled', true);
                        }
                    }
                });
            });


            // let hargaNormal = {{ $service->base_price ?? 0 }};
            // let qty = 0;
            let qty = 0;
            let hargaNormal = 0;

            function updateHargaNormal() {
                const selectedOption = $('#service_id option:selected');
                hargaNormal = parseFloat(selectedOption.data('price')) || 0;
            }

            function calculateGrandTotal() {
                const type = $('#price_type').val();
                const manual = parseFloat($('#manual_price').val()) || 0;

                if (type === 'normal') {
                    $('#manual_price').prop('disabled', true);
                    $('#manual_price_error').addClass('d-none');
                    updateHargaNormal();
                    $('#grand_total').val(qty * hargaNormal);
                    $('#price_info').text('Harga dihitung dari qty × harga normal 💰');
                }

                if (type === 'manual') {
                    $('#manual_price').prop('disabled', false);
                    if (!manual) {
                        $('#manual_price_error').removeClass('d-none');
                    } else {
                        $('#manual_price_error').addClass('d-none');
                    }
                    $('#grand_total').val(manual);
                    $('#price_info').text('Harga manual diinput user ✍️');
                }
            }

            function updateQty() {
                let start = new Date($('#start_date').val());
                let end = new Date($('#end_date').val());

                if (start && end && end >= start) {
                    let diff = Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1;
                    qty = diff;
                    calculateGrandTotal();
                }
            }

            $('#start_date, #end_date, #manual_price, #price_type').on('change keyup', function() {
                updateQty();
            });

            $('#price_type').on('change', calculateGrandTotal);
            $('#customer_type').on('change', calculateGrandTotal);
            $('#manual_price').on('input', calculateGrandTotal);
            $('#start_date, #end_date').on('change', updateQty);


            // Validasi sebelum submit
            $('form').on('submit', function(e) {
                if ($('#price_type').val() === 'manual' && !$('#manual_price').val()) {
                    e.preventDefault();
                    $('#manual_price_error').removeClass('d-none');
                }
            });

        });
    </script>
    @include('admin.partials._swalDeleteConfirm')
@endpush
