@extends('admin.layout.app')

@section('content')
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card clearfix">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0">Daftar Mitra</h5>
                <a href="{{ route('admin.mitra.create') }}" class="btn btn-primary btn-sm">Tambah Mitra</a>
            </div>

            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Logo</th>
                            <th>Nama</th>
                            <th>Status</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mitra as $m)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <img src="{{ asset('storage/uploads/mitra/'.$m->logo) }}" 
                                alt="{{ $m->nama }}" width="70">
                            </td>
                            <td>{{ $m->nama }}</td>
                            <td>
                               <span id="status-{{ $m->id }}" class="badge {{ $m->status ? 'bg-success' : 'bg-secondary' }}">
        {{ $m->status ? 'Aktif' : 'Nonaktif' }}
    </span>
                            </td>
                            <td>
                               <!-- Edit -->
                               <a href="{{ route('admin.mitra.edit', $m->id) }}" title="Edit">
                                <i class="fas fa-edit text-info"></i>
                            </a>

<!-- Status Toggle -->
@if($m->status)
<a class="updateMitraStatus" id="mitra-{{ $m->id }}" mitra_id="{{ $m->id }}" href="javascript:void(0)" title="Nonaktifkan">
    <i class="fas fa-toggle-on text-success" status="Active"></i>
</a>
@else
<a class="updateMitraStatus" id="mitra-{{ $m->id }}" mitra_id="{{ $m->id }}" href="javascript:void(0)" style="color:grey" title="Aktifkan">
    <i class="fas fa-toggle-off" status="Inactive"></i>
</a>
@endif

<!-- Delete -->
<a class="confirmDelete" name="{{ $m->name }}" href="javascript:void(0)" record="mitra" recordid="{{ $m->id }}" title="Hapus Mitra">
    <i class="fas fa-trash text-danger"></i>
</a>

</td>
</tr>
@empty
<tr><td colspan="4" class="text-center">Belum ada mitra</td></tr>
@endforelse
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</section>
@endsection
@push('scripts')
@include('admin.partials._swalDeleteConfirm')
@endpush