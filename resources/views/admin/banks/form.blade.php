@extends('admin.layout.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card clearfix">
                        <div class="card-header clearfix">
                            <a href="{{ route('admin.banks.index') }}" class="btn btn-sm btn-flat btn-info float-right"> <i
                                    class="fas fa-list"></i> Daftar</a>
                            <h3 class="card-title">Akun Bank</h3>
                        </div>

                        <div class="card-body">
                            @include('admin.partials.alert')
                            <form method="POST" enctype="multipart/form-data"
                                action="{{ $bank ? route('admin.banks.update', $bank) : route('admin.banks.store') }}">
                                @csrf
                                @if ($bank)
                                    @method('PUT')
                                @endif
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Service</label>
                                        <select name="service_id" class="form-control" required>
                                            <option value="">-- Pilih --</option>
                                            @foreach ($services as $sv)
                                                <option value="{{ $sv->id }}"
                                                    {{ old('service_id', $bank->service_id ?? '') == $sv->id ? 'selected' : '' }}>
                                                    {{ $sv->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Type</label>
                                        <select name="type" id="type_select" class="form-control" required
                                            onchange="toggleType()">
                                            <option value="qris"
                                                {{ old('type', $bank->type ?? '') == 'qris' ? 'selected' : '' }}>QRIS
                                            </option>
                                            <option value="va"
                                                {{ old('type', $bank->type ?? '') == 'va' ? 'selected' : '' }}>Virtual
                                                Account
                                            </option>
                                        </select>
                                    </div>
                                    <div id="va_section" class="col-12 mt-4" style="display:none">
                                        <h5>Virtual Account</h5>

                                        <label>Bank Name</label>
                                        <input type="text" class="form-control" name="bank_name"
                                            value="{{ old('bank_name', $bank->bank_name ?? '') }}">

                                        <label class="mt-2">Account Name</label>
                                        <input type="text" class="form-control" name="account_name"
                                            value="{{ old('account_name', $bank->account_name ?? '') }}">

                                        <label class="mt-2">Account Number</label>
                                        <input type="text" class="form-control" name="account_number"
                                            value="{{ old('account_number', $bank->account_number ?? '') }}">

                                        <label class="mt-2">Bank Icon</label>
                                        <input type="file" name="bank_icon" class="form-control">
                                        @if (!empty($bank->bank_icon))
                                            <img src="{{ Storage::url($bank->bank_icon) }}" width="100" class="mt-2">
                                        @endif
                                    </div>

                                    {{-- ================= QRIS ================= --}}
                                    <div id="qris_section" class="col-12 mt-4" style="display:none">
                                        <h5>QRIS</h5>
                                        <input type="hidden" name="bank_name" value="QRIS">
                                        <input type="hidden" name="account_name" value="QRIS">
                                        <input type="hidden" name="account_number" value="QRIS">
                                        <label>QRIS Image</label>
                                        <input type="file" name="qris_image" class="form-control">

                                        @if (!empty($bank->qris_image))
                                            <img src="{{ Storage::url($bank->qris_image) }}" width="150" class="mt-2">
                                            <br>
                                        @endif

                                        <label class="mt-2">Merchant Name</label>
                                        <input type="text" class="form-control" name="merchant_name"
                                            value="{{ old('merchant_name', $bank->merchant_name ?? '') }}">

                                        <label class="mt-2">Merchant ID</label>
                                        <input type="text" class="form-control" name="merchant_id"
                                            value="{{ old('merchant_id', $bank->merchant_id ?? '') }}">
                                    </div>

                                    <div class="col-md-4 mt-4">
                                        <label>Status</label>
                                        <select name="status" class="form-control">
                                            <option value="1"
                                                {{ old('status', $bank->status ?? 1) == 1 ? 'selected' : '' }}>Active
                                            </option>
                                            <option value="0"
                                                {{ old('status', $bank->status ?? 1) == 0 ? 'selected' : '' }}>Inactive
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <button class="btn btn-primary mt-4">Simpan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection

@push('scripts')
    <script>
        function toggleType() {
            let type = document.getElementById('type_select').value;

            document.getElementById('va_section').style.display = (type === 'va') ? 'block' : 'none';
            document.getElementById('qris_section').style.display = (type === 'qris') ? 'block' : 'none';
        }

        toggleType();
    </script>
@endpush
