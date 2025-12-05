<!-- Modal Tambah Booking -->
<div class="modal fade" id="addBookingModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Tambah Booking</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <form action="{{ route('admin.bookings.store') }}" method="POST">
                @csrf

                <div class="modal-body">

                    <!-- Pilih Produk / Aset -->
                    <div class="mb-3">
                        <label class="form-label">Pilih Layanan</label>
                        <select name="service_id" id="service_id" class="form-control" required>
                            <option value="">-- Pilih Layanan --</option>
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}" data-price="{{ $service->base_price }}">
                                    {{ $service->name }} (Rp {{ number_format($service->base_price) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tanggal -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" required>
                        </div>

                        <div class="col-md-4 d-grid">
                            <button type="button" class="btn btn-info" id="checkAvailability">
                                Cek Ketersediaan
                            </button>
                            <div id="availabilityResult" class="mt-2"></div>
                        </div>
                    </div>

                    <!-- Harga -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Tipe Harga</label>
                            <select name="price_type" id="price_type" class="form-control">
                                <option value="normal">Normal</option>
                                <option value="manual">Manual</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Harga Manual</label>
                            <input type="number" name="manual_price" id="manual_price" class="form-control" disabled>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Total Harga</label>
                            <input type="number" name="grand_total" id="grand_total" class="form-control" readonly
                                required>
                        </div>
                    </div>

                    <!-- Customer -->
                    <div class="mb-3">
                        <label class="form-label">Customer</label>
                        <div class="row g-2 align-items-center">
                            <div class="col-auto d-flex align-items-center">
                                <div class="form-check mb-0">
                                    <input type="checkbox" class="form-check-input" id="new_customer_toggle">
                                    <label class="form-check-label" for="new_customer_toggle">Customer Baru</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select name="user_id" id="select_customer" class="form-control">
                                    <option value="">-- Pilih Customer --</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" data-type="{{ $user->customer_type }}">
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="text" id="new_customer_name" name="new_customer_name"
                                    class="form-control" placeholder="Nama Customer Baru" style="display:none;">
                            </div>
                            <div class="col-md-3">
                                <select name="customer_type" id="customer_type" class="form-control"
                                    style="display:none;">
                                    <option value="umum">Umum</option>
                                    <option value="civitas">Civitas</option>
                                    <option value="mahasiswa">Mahasiswa</option>
                                </select>
                            </div>
                        </div>
                    </div>



                    <!-- Internal Booking -->
                    <div class="mb-3">
                        <label class="form-label">Booking Internal?</label>
                        <select name="is_internal" class="form-control">
                            <option value="0">Tidak</option>
                            <option value="1">Ya</option>
                        </select>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <select name="payment_method" class="form-control" required>
                            <option value="transfer">Transfer</option>
                            <option value="tunai">Tunai</option>
                        </select>
                    </div>

                    <div class="alert alert-info fw-bold" id="price_display">
                        Harga akan dihitung otomatis berdasarkan layanan, tanggal & tipe harga.
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" id="btnSubmit" class="btn btn-primary btn-flat" disabled>
                        Simpan
                    </button>
                    <button type="button" class="btn btn-secondary btn-flat" data-dismiss="modal">
                        Batal
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
