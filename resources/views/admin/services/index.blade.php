@extends('admin.layout.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card clearfix">
                        <div class="card-header clearfix">
                            @canAccess('Services', 'create')
                            <a href="{{ route('admin.services.create') }}" class="btn btn-sm btn-flat btn-info float-right">
                                <i class="fas fa-plus"></i> Tambah</a>
                            @endCanAccess
                            <h3 class="card-title">{{ $title }}</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            @include('admin.partials.alert')
                            <table id="cmspages" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Layanan</th>
                                        <th>Unit Bisnis</th>
                                        <th>Harga</th>
                                        <th>Lokasi</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($services as $service)
                                        <tr>
                                            <td>{{ $service['id'] }}</td>
                                            <td>{{ $service['name'] }}</td>

                                            <td>
                                                @if (isset($service['unit_id']))
                                                    {{ $service['unit']['name'] ?? '' }}
                                                @endif
                                            </td>
                                            <td>
                                                @currency($service['base_price'])
                                            </td>
                                            <td>
                                                @if (isset($service['location_id']))
                                                    {{ $service['location']['name'] }}
                                                @endif
                                            </td>
                                            <td>{{ date('j F Y', strtotime($service['created_at'])) }}</td>
                                            <td>
                                                <a href="{{ route('admin.services.edit', $service['id']) }}"> <i
                                                        class="fas fa-edit text-info"></i> </a>
                                                @if ($service['status'] == 1)
                                                    <a class="updateServiceStatus" id="service-{{ $service['id'] }}"
                                                        service_id="{{ $service['id'] }}" href="javascrip:void(0)"><i
                                                            class="fas fa-toggle-on" status="Active"></i></a>
                                                @else
                                                    <a class="updateServiceStatus" id="service-{{ $service['id'] }}"
                                                        service_id="{{ $service['id'] }}" href="javascrip:void(0)"
                                                        style="color:grey"><i class="fas fa-toggle-off"
                                                            status="Inactive"></i></a>
                                                @endif
                                                <a class="confirmDelete" name="{{ $service['name'] }}"
                                                    href="javascript:void(0)" record="service"
                                                    recordid="{{ $service['id'] }}" title="Hapus Kategori"> <i
                                                        class="fas fa-trash text-danger"></i> </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
