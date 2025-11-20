@extends('admin.layout.app')

@section('content')
    <section class="content">
        <div class="container-fluid">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pengaturan API Key</h3>

                    <a href="{{ route('admin.api-keys.create') }}" class="btn btn-success mb-3 float-right">
                        Tambah API Key
                    </a>
                </div>

                <div class="card-body">

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Service</th>
                                <th>Key Name</th>
                                <th>Key Value</th>
                                <th>Deskripsi</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($apiKeys as $key)
                                <tr>
                                    <td>{{ $key->id }}</td>
                                    <td>{{ ucfirst($key->service) }}</td>
                                    <td>{{ $key->key_name }}</td>
                                    <td>
                                        <span class="masked">{{ $key->masked_value }}</span>
                                        <span class="real-key d-none">{{ $key->key_value }}</span>
                                        <a href="javascript:void(0)" class="toggle-key float-right"
                                            data-key="{{ $key->key_value }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                    <td>{{ $key->description }}</td>

                                    <td>
                                        <div class="btn-group">

                                            <a href="{{ route('admin.api-keys.edit', $key->id) }}"
                                                class="btn btn-sm btn-primary btn-flat" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form action="{{ route('admin.api-keys.destroy', $key->id) }}" method="POST"
                                                style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger btn-flat"
                                                    onclick="return confirm('Yakin ingin hapus API Key ini?')"
                                                    title="Hapus">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $apiKeys->links() }}

                </div>
            </div>

        </div>
    </section>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButtons = document.querySelectorAll('.toggle-key');

            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {

                    const icon = this.querySelector('i');
                    const realKey = this.previousElementSibling; // span.real-key
                    const maskedKey = realKey.previousElementSibling; // span.masked-key

                    // Toggle visible/hidden
                    const isHidden = realKey.classList.contains('d-none');

                    if (isHidden) {
                        realKey.classList.remove('d-none');
                        maskedKey.classList.add('d-none');

                        // Ubah icon ke mata tertutup
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        realKey.classList.add('d-none');
                        maskedKey.classList.remove('d-none');

                        // Ubah icon ke mata terbuka
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            });
        });
    </script>
@endpush
