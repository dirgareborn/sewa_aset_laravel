@extends('admin.layout.app')
@push('styles')
    <style>
        /* Animasi gerakan ikon ke bawah */
        @keyframes downloadMove {
            0% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(6px);
            }

            100% {
                transform: translateY(0);
            }
        }

        .downloading {
            animation: downloadMove 0.6s infinite;
        }

        /* Animasi gerakan ikon ke atas */
        @keyframes uploadMove {
            0% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-6px);
            }

            100% {
                transform: translateY(0);
            }
        }

        .uploading {
            animation: uploadMove 0.6s infinite;
        }
    </style>
    @section('content')
        <section class="content">
            <div class="container-fluid">

                @include('admin.partials.alert')

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Backup & Restore Database</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.database.backup') }}" method="GET" onsubmit="startBackup(this)">
                            @csrf
                            <button type="submit" id="backupBtn" class="btn btn-success d-flex align-items-center">
                                <i class="fas fa-download" id="backupIcon"></i>
                                <span id="backupText" class="ms-2">Backup Database</span>
                            </button>
                        </form>

                        <div class="mb-3">
                            <form action="{{ route('admin.database.restore') }}" method="post" enctype="multipart/form-data"
                                onsubmit="startRestore(this)">
                                @csrf
                                @method('PUT')
                                <div class="input-group">
                                    <input type="file" name="upload_file" class="form-control" accept=".sql" required>
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary" id="restoreBtn">
                                            <i class="fas fa-upload" id="restoreIcon"></i>
                                            <span id="restoreText" class="ms-2">Restore Database</span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        @if (count($backups) > 0)
                            <h5>Daftar Backup:</h5>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama File</th>
                                        <th>Ukuran</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($backups as $file)
                                        @php
                                            $fileName = basename($file);
                                            $fileSize = formatBytes(Storage::size($file));
                                        @endphp
                                        <tr>
                                            <td>{{ $fileName }}</td>
                                            <td>{{ $fileSize }}</td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <!-- Restore -->
                                                    <form action="{{ route('admin.database.restore') }}" method="POST"
                                                        class="d-inline"
                                                        onsubmit="return confirm('Restore akan menimpa database saat ini. Lanjutkan?');">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="backup_file" value="{{ $fileName }}">
                                                        <button type="submit"
                                                            class="btn btn-outline-warning btn-flat d-flex align-items-center justify-content-center p-1">
                                                            <i class="fas fa-upload"></i>
                                                        </button>
                                                    </form>

                                                    <!-- Download -->
                                                    <form action="{{ route('admin.database.download', $fileName) }}"
                                                        method="GET" class="d-inline">
                                                        <button type="submit"
                                                            class="btn btn-info btn-flat d-flex align-items-center justify-content-center p-1">
                                                            <i class="fas fa-download"></i>
                                                        </button>
                                                    </form>

                                                    <!-- Delete -->
                                                    <form action="{{ route('admin.database.delete') }}" method="POST"
                                                        class="d-inline"
                                                        onsubmit="return confirm('Apakah yakin ingin menghapus backup ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="backup_file" value="{{ $fileName }}">
                                                        <button type="submit"
                                                            class="btn btn-outline-danger btn-flat d-flex align-items-center justify-content-center p-1">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>



                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>

            </div>
        </section>
    @endsection
    @push('scripts')
        <script>
            function startBackup(form) {
                var icon = document.getElementById('backupIcon');
                var text = document.getElementById('backupText');

                // Tambahkan class animasi
                icon.classList.add('downloading');
                text.textContent = 'Sedang membackup...';

                // Disable tombol
                form.querySelector('button').disabled = true;

                return true; // submit form normal
            }

            function startRestore(form) {
                var icon = document.getElementById('restoreIcon');
                var text = document.getElementById('restoreText');

                // Tambahkan class animasi
                icon.classList.add('uploading');
                text.textContent = 'Sedang merestore...';

                // Disable tombol
                form.querySelector('button').disabled = true;

                return true; // submit form normal
            }
        </script>

    @endpush
