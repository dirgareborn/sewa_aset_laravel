@extends('admin.layout.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card clearfix">
                        <div class="card-header clearfix">
                            <a href="{{ route('admin.banks.create') }}" class="btn btn-sm btn-flat btn-info float-right"> <i
                                    class="fas fa-plus"></i> Tambah</a>
                            <h3 class="card-title">Akun Bank</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            @include('admin.partials.alert')

                            <div class="card-body p-0">
                                <table class="table table-striped mb-0">
                                    <thead class="table">
                                        <tr>
                                            <th width="40">#</th>
                                            <th>Tipe</th>
                                            <th>Layanan</th>
                                            <th>Nama Akun</th>
                                            <th>Nomor</th>
                                            <th>Icon</th>
                                            <th>Status</th>
                                            <th width="140">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($banks as $key => $bank)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>

                                                <td>
                                                    @if ($bank->type == 'qris')
                                                        <span class="badge bg-info">QRIS</span>
                                                    @else
                                                        <span class="badge bg-success">Virtual Account</span>
                                                    @endif
                                                </td>

                                                <td>{{ $bank->service->name }}</td>
                                                <td>{{ $bank->account_name }}</td>
                                                <td>{{ $bank->account_number }}</td>

                                                <td>
                                                    @if ($bank->bank_icon)
                                                        <img src="{{ asset('storage/' . $bank->bank_icon) }}" alt="icon"
                                                            height="40">
                                                    @else
                                                        <span class="text-muted">Tidak ada</span>
                                                    @endif
                                                </td>

                                                <td>
                                                    @if ($bank->status == 1)
                                                        <span class="badge bg-primary">Aktif</span>
                                                    @else
                                                        <span class="badge bg-secondary">Nonaktif</span>
                                                    @endif
                                                </td>

                                                <td>
                                                    <a href="{{ route('admin.banks.edit', $bank->id) }}"
                                                        class="btn btn-sm btn-warning">
                                                        <i class="fa fa-edit"></i>
                                                    </a>

                                                    <form action="{{ route('admin.banks.destroy', $bank->id) }}"
                                                        method="POST" style="display:inline-block;"
                                                        onsubmit="return confirm('Yakin ingin menghapus data ini?');">

                                                        @csrf
                                                        @method('DELETE')

                                                        <button class="btn btn-sm btn-danger">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>

                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-3 text-muted">
                                                    Belum ada data akun pembayaran.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="card-footer">
                                {{ $banks->links() }}
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
@push('scripts')
    @include('admin.partials._swalDeleteConfirm')
@endpush
