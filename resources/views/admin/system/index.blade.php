@extends('admin.layout.app')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
@endpush
@section('content')
    <section class="content">
        <div class="container-fluid">
            <!-- Toast Container -->
            <div aria-live="polite" aria-atomic="true" class="position-relative">
                <div class="toast-container position-fixed top-0 end-0 p-3" id="toast-container"></div>
            </div>
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link active" href="#system_info" data-toggle="tab">System Info</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#log_error" data-toggle="tab">Log Error</a>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content">

                        <!-- System Info -->
                        <div class="tab-pane fade show active" id="system_info">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Key</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($info as $key => $value)
                                        <tr>
                                            <td>{{ $key }}</td>
                                            <td>{{ $value }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Log Error -->
                        <div class="tab-pane fade" id="log_error">
                            <div class="mb-2 d-flex align-items-center">
                                <!-- Filter tetap di kiri -->
                                <form method="GET" action="{{ route('admin.system') }}" class="form-inline me-auto">
                                    <select name="level" class="form-control form-control-sm me-2">
                                        <option value="">Semua Level</option>
                                        <option value="ERROR" {{ request('level') == 'ERROR' ? 'selected' : '' }}>ERROR
                                        </option>
                                        <option value="CRITICAL" {{ request('level') == 'CRITICAL' ? 'selected' : '' }}>
                                            CRITICAL</option>
                                        <option value="WARNING" {{ request('level') == 'WARNING' ? 'selected' : '' }}>
                                            WARNING</option>
                                        <option value="INFO" {{ request('level') == 'INFO' ? 'selected' : '' }}>INFO
                                        </option>
                                    </select>
                                    <button class="btn btn-sm btn-flat btn-primary">Filter</button>
                                </form>

                                <!-- Button group di kanan -->
                                <div class="btn-group ms-auto" role="group">
                                    <a href="{{ route('admin.system.download') }}" class="btn btn-sm btn-success">Download
                                        Log</a>
                                    <button id="clear-log-btn" class="btn btn-flat  btn-sm btn-danger">Clear Log</button>
                                </div>
                            </div>


                            @if (count($logs) > 0)
                                <pre style="max-height:400px; overflow:auto; background:#f8f9fa; padding:10px; border:1px solid #ddd;">
@foreach ($logs as $line)
{{ $line }}
@endforeach
              </pre>
                            @else
                                <p class="text-muted">Tidak ada log error.</p>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HoA3h0qPSNdp1B6q3Emq5Qg+2cVvZk8Jj6F3g/8bZLpi4gRrHcX+n1E+UuO8mGHX" crossorigin="anonymous">
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var triggerTabList = [].slice.call(document.querySelectorAll('#system_info-tab, #log_error-tab'));
            triggerTabList.forEach(function(triggerEl) {
                var tabTrigger = new bootstrap.Tab(triggerEl);
                triggerEl.addEventListener('click', function(event) {
                    event.preventDefault();
                    tabTrigger.show();
                });
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('clear-log-btn');
            const toastContainer = document.getElementById('toast-container');

            function showToast(message, type = 'success') {
                // Buat toast element
                const toastEl = document.createElement('div');
                toastEl.className = `toast align-items-center text-white bg-${type} border-0`;
                toastEl.setAttribute('role', 'alert');
                toastEl.setAttribute('aria-live', 'assertive');
                toastEl.setAttribute('aria-atomic', 'true');
                toastEl.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;
                toastContainer.appendChild(toastEl);

                // Inisialisasi dan tampilkan toast
                const toast = new bootstrap.Toast(toastEl, {
                    delay: 3000
                });
                toast.show();

                // Hapus toast dari DOM setelah disembunyikan
                toastEl.addEventListener('hidden.bs.toast', () => {
                    toastEl.remove();
                });
            }

            btn.addEventListener('click', function() {
                if (!confirm('Yakin ingin menghapus semua log?')) return;

                btn.disabled = true;
                btn.innerText = 'Clearing...';

                fetch("{{ route('admin.system.clear_log_ajax') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            showToast(data.message, 'success');

                            // Kosongkan preview log
                            const pre = document.querySelector('#log_error pre');
                            if (pre) pre.innerHTML = '';
                        } else {
                            showToast(data.message, 'danger');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        showToast('Terjadi kesalahan saat membersihkan log.', 'danger');
                    })
                    .finally(() => {
                        btn.disabled = false;
                        btn.innerText = 'Clear Log';
                    });
            });
        });
    </script>
@endpush
