@extends('admin.layout.app')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <a href="{{ route('admin.employees.index') }}" class="btn btn-sm btn-flat btn-info float-right">
                        <i class="fas fa-solid fa-backward"></i> Kembali
                    </a>
                    <h3 class="card-title">{{ $title }}</h3>
                </div>

                <div class="card-body">
                    @include('admin.partials.alert')

                    <form
                        action="{{ isset($employee) ? route('admin.employees.update', $employee) : route('admin.employees.store') }}"
                        method="POST" enctype="multipart/form-data">

                        @csrf
                        @if (isset($employee))
                            @method('PUT')
                        @endif

                        <div class="row">
                            <!-- LEFT -->
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label>Nama</label>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ old('name', $employee->name ?? '') }}" required>
                                </div>

                                <div class="form-group">
                                    <label>NIP / ID</label>
                                    <input type="text" name="employee_id" class="form-control"
                                        value="{{ old('employee_id', $employee->employee_id ?? '') }}" required>
                                </div>

                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control"
                                        value="{{ old('email', $employee->email ?? '') }}" required>
                                </div>

                                <div class="form-group">
                                    <label>Jabatan / Position</label>
                                    <input type="text" name="position" class="form-control"
                                        value="{{ old('position', $employee->position ?? '') }}" required>
                                </div>

                                <div class="form-group">
                                    <label>Role</label>
                                    <select name="role" class="form-control" required>
                                        <option value="office"
                                            {{ old('role', $employee->role ?? '') == 'office' ? 'selected' : '' }}>
                                            Office
                                        </option>
                                        <option value="unit"
                                            {{ old('role', $employee->role ?? '') == 'unit' ? 'selected' : '' }}>
                                            Unit Bisnis
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Staff Global?</label><br>
                                    <input type="checkbox" name="is_global_staff" value="1"
                                        {{ old('is_global_staff', $employee->is_global_staff ?? 0) == 1 ? 'checked' : '' }}>
                                    <span>Ya, staff global</span>
                                </div>

                                <div class="form-group">
                                    <label>Foto</label>

                                    @if (isset($employee) && $employee->image && Storage::disk('public')->exists($employee->image))
                                        <div class="mb-2">
                                            <img id="previewImg" src="{{ Storage::url($employee->image) }}"
                                                class="img-thumbnail" width="120">
                                        </div>
                                    @else
                                        <img id="previewImg" src="#" style="display:none;" class="img-thumbnail"
                                            width="120">
                                    @endif

                                    <input type="file" name="image" class="form-control" onchange="previewImage(this)">
                                </div>

                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="1"
                                            {{ old('status', $employee->status ?? '') == 1 ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="0"
                                            {{ old('status', $employee->status ?? '') == 0 ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </div>

                            </div>

                            <!-- RIGHT -->
                            <div class="col-md-6">

                                <h5>Sosial Media</h5>
                                <div class="row">
                                    <div id="sosmed_hidden_fields"></div>
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

                                <input type="hidden" id="edit_index">

                                <button type="button" class="btn btn-primary mt-3" onclick="saveSosmed()">Simpan</button>

                                <hr>
                                <table class="table table-bordered" id="table_sosmed">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Icon</th>
                                            <th>URL</th>
                                            <th width="120">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>

                                <textarea name="sosmed[]" id="sosmed_json" class="form-control" rows="10">{{ old('sosmed', isset($employee) ? json_encode($employee->sosmed, JSON_PRETTY_PRINT) : '[]') }}</textarea>

                                <button type="submit" class="btn btn-primary mt-4">
                                    {{ isset($employee) ? 'Update' : 'Create' }}
                                </button>

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
        /* -------------------------------
                           PREVIEW IMAGE
                        --------------------------------*/
        function previewImage(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    let img = document.getElementById('previewImg');
                    img.src = e.target.result;
                    img.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        /* -------------------------------
           SOSMED HANDLER
        --------------------------------*/
        let sosmedList = [];
        let editIndex = null;

        document.addEventListener("DOMContentLoaded", function() {
            let oldJson = document.getElementById("sosmed_json").value.trim();

            try {
                let parsed = JSON.parse(oldJson);
                sosmedList = Array.isArray(parsed) ? parsed : [];
            } catch (e) {
                sosmedList = [];
            }

            loadTable();
        });

        function saveSosmed() {
            let name = sm_name.value.trim();
            let icon = sm_icon.value.trim();
            let url = sm_url.value.trim();

            if (!name || !icon || !url) {
                alert("Isi semua field sosial media");
                return;
            }

            let data = {
                socialmedia_name: name,
                socialmedia_icon: icon,
                url: url
            };

            if (editIndex !== null) {
                sosmedList[editIndex] = data;
                editIndex = null;
            } else {
                sosmedList.push(data);
            }

            updateJSON();
            loadTable();
            clearForm();
        }

        function loadTable() {
            let tbody = document.querySelector("#table_sosmed tbody");
            tbody.innerHTML = "";

            sosmedList.forEach((item, i) => {
                tbody.innerHTML += `
            <tr>
                <td>${item.socialmedia_name}</td>
                <td><i class="${item.socialmedia_icon}"></i> ${item.socialmedia_icon}</td>
                <td>${item.url}</td>
                <td>
                    <button class="btn btn-warning btn-sm" onclick="editSosmed(${i})">Edit</button>
                    <button class="btn btn-danger btn-sm" onclick="deleteSosmed(${i})">Hapus</button>
                </td>
            </tr>`;
            });
        }

        function editSosmed(i) {
            let item = sosmedList[i];
            sm_name.value = item.socialmedia_name;
            sm_icon.value = item.socialmedia_icon;
            sm_url.value = item.url;
            editIndex = i;
        }

        function deleteSosmed(i) {
            if (confirm("Yakin hapus?")) {
                sosmedList.splice(i, 1);
                updateJSON();
                loadTable();
            }
        }

        function updateJSON() {
            sosmed_json.value = JSON.stringify(sosmedList, null, 4);
        }

        function clearForm() {
            sm_name.value = "";
            sm_icon.value = "";
            sm_url.value = "";
            editIndex = null;
        }

        function updateJSON() {
            let container = document.getElementById("sosmed_hidden_fields");
            container.innerHTML = ""; // reset

            sosmedList.forEach(item => {
                container.innerHTML += `
            <input type="hidden" name="sosmed[name][]" value="${item.socialmedia_name}">
            <input type="hidden" name="sosmed[icon][]" value="${item.socialmedia_icon}">
            <input type="hidden" name="sosmed[url][]" value="${item.url}">
        `;
            });
        }
    </script>
@endpush
