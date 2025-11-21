@extends('admin.layout.app')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <a href="{{ route('admin.employees.index') }}" class="btn btn-sm btn-flat btn-info float-right"> <i
                            class="fas fa-solid fa-backward"></i> Kembali</a>
                    <h3 class="card-title">{{ $title }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            @include('admin.partials.alert')
                            <form
                                action="{{ isset($employee) ? route('admin.employees.update', $employee) : route('admin.employees.store') }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @if (isset($employee))
                                    @method('PUT')
                                @endif
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label> Nama</label>
                                            <input type="name" class="form-control" name="name"
                                                value="{{ old('name', $employee->name ?? '') }}" placeholder="State">
                                        </div>
                                        <div class="form-group">
                                            <label>NIP / ID</label>
                                            <input type="text" class="form-control" name="employee_id"
                                                value="{{ old('employee_id', $employee->employee_id ?? '') }}"
                                                placeholder="NIP" required>
                                        </div>
                                        <div class="form-group">
                                            <label> Email </label>
                                            <input type="email" class="form-control" name="email"
                                                value="{{ old('email', $employee->email ?? '') }}" placeholder="Email"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label> Alamat </label>
                                            <input type="text" class="form-control" name="address"
                                                value="{{ old('address', $employee->address ?? '') }}"
                                                placeholder="Address">
                                        </div>
                                        <div class="form-group">
                                            <label> Kota/Kab</label>
                                            <input type="text" class="form-control" name="city"
                                                value="{{ old('city', $employee->city ?? '') }}" placeholder="City">
                                        </div>
                                        <div class="form-group">
                                            <label> Provinsi</label>
                                            <input type="text" class="form-control" name="state"
                                                value="{{ old('state', $employee->state ?? '') }}" placeholder="State">
                                        </div>
                                        <div class="form-group">
                                            <label> Kode Pos </label>
                                            <input type="text" class="form-control" name="postal_code"
                                                value="{{ old('postal_code', $employee->postal_code ?? '') }}"
                                                placeholder="Postal Code">
                                        </div>
                                        <div class="form-group">
                                            <label>Foto</label>
                                            @if (!empty($employee->image) && Storage::disk('public')->exists($employee->image))
                                                <div class="mb-2">
                                                    <img src="{{ Storage::url($employee->image) }}" alt="Employee Image"
                                                        width="100" class="img-thumbnail">
                                                </div>
                                            @else
                                                <p class="text-muted">Belum ada foto</p>
                                            @endif
                                            <input type="file" name="image" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">

                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Nama</label>
                                                <input type="text" id="sm_name" class="form-control">
                                            </div>

                                            <div class="col-md-4">
                                                <label>Icon</label>
                                                <select id="sm_icon" class="form-control">
                                                    <option value="">-- Pilih Icon --</option>
                                                    <option value="fab fa-facebook">Facebook</option>
                                                    <option value="fab fa-instagram">Instagram</option>
                                                    <option value="fab fa-twitter">Twitter</option>
                                                    <option value="fab fa-youtube">YouTube</option>
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label>URL</label>
                                                <input type="text" id="sm_url" class="form-control">
                                            </div>
                                        </div>

                                        <input type="hidden" id="edit_index"> <!-- penanda sedang edit -->

                                        <button type="button" class="btn btn-primary mt-3"
                                            onclick="saveSosmed()">Simpan</button>

                                        <hr>
                                        <h5>Daftar Sosial Media</h5>
                                        <table class="table table-bordered" id="table_sosmed">
                                            <thead>
                                                <tr>
                                                    <th>Nama</th>
                                                    <th>Icon</th>
                                                    <th>URL</th>
                                                    <th width="150">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                        <hr>
                                        <textarea name="sosmed" id="sosmed_json" class="form-control" rows="10">{{ old('sosmed', isset($employee) ? json_encode($employee->sosmed, JSON_PRETTY_PRINT) : '[]') }}</textarea>

                                        <div class="form-group mt-3">
                                            <label>Status</label>
                                            <select name="status" class="form-control">
                                                <option value="1"
                                                    {{ old('status', $employee->status ?? '') == 1 ? 'selected' : '' }}>
                                                    Active</option>
                                                <option value="0"
                                                    {{ old('status', $employee->status ?? '') == 0 ? 'selected' : '' }}>
                                                    Inactive</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Assign Unit Bisnis</label>
                                            <select name="categories[]" class="form-control" multiple>
                                                @foreach ($categories as $cat)
                                                    <option value="{{ $cat->id }}"
                                                        @if (isset($employee) && $employee->categories->contains($cat->id)) selected @endif>
                                                        {{ $cat->category_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <button type="submit"
                                            class="btn btn-primary">{{ isset($employee) ? 'Update' : 'Create' }}</button>
                                    </div>

                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
    </section>
    <!-- /.content -->
@endsection
@push('scripts')
    <script>
        let sosmedList = [];
        let editIndex = null;

        // LOAD DATA DARI TEXTAREA SAAT EDIT
        document.addEventListener("DOMContentLoaded", function() {
            let oldJson = document.getElementById("sosmed_json").value.trim();

            try {
                let parsed = JSON.parse(oldJson);

                // Jika hasil parse bukan array → fallback []
                sosmedList = Array.isArray(parsed) ? parsed : [];
            } catch (e) {
                console.error("JSON error:", e);
                sosmedList = [];
            }

            loadTable();
        });

        // SIMPAN BARU ATAU UPDATE
        function saveSosmed() {
            let name = document.getElementById("sm_name").value.trim();
            let icon = document.getElementById("sm_icon").value.trim();
            let url = document.getElementById("sm_url").value.trim();

            if (name === "" || icon === "" || url === "") {
                alert("Isi semua data sosial media.");
                return;
            }

            let item = {
                socialmedia_name: name,
                socialmedia_icon: icon,
                url: url
            };

            if (editIndex !== null) {
                sosmedList[editIndex] = item;
                editIndex = null;
            } else {
                sosmedList.push(item);
            }

            clearForm();
            loadTable();
            updateJSON();
        }

        // TAMPILKAN DI TABEL
        function loadTable() {
            let tbody = document.querySelector("#table_sosmed tbody");
            tbody.innerHTML = "";

            sosmedList.forEach((item, index) => {
                tbody.innerHTML += `
            <tr>
                <td>${item.socialmedia_name}</td>
                <td><i class="${item.socialmedia_icon}"></i> ${item.socialmedia_icon}</td>
                <td>${item.url}</td>
                <td>
                    <button type="button" class="btn btn-warning btn-sm" onclick="editSosmed(${index})">Edit</button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteSosmed(${index})">Hapus</button>
                </td>
            </tr>
        `;
            });
        }

        // EDIT DATA
        function editSosmed(index) {
            let item = sosmedList[index];

            document.getElementById("sm_name").value = item.socialmedia_name;
            document.getElementById("sm_icon").value = item.socialmedia_icon;
            document.getElementById("sm_url").value = item.url;

            editIndex = index;
        }

        // HAPUS DATA
        function deleteSosmed(index) {
            if (!confirm("Hapus data ini?")) return;
            sosmedList.splice(index, 1);
            loadTable();
            updateJSON();
        }

        // UPDATE TEXTAREA JSON
        function updateJSON() {
            document.getElementById("sosmed_json").value = JSON.stringify(sosmedList, null, 4);
        }

        // RESET FORM INPUT
        function clearForm() {
            document.getElementById("sm_name").value = "";
            document.getElementById("sm_icon").value = "";
            document.getElementById("sm_url").value = "";
        }
    </script>
@endpush
