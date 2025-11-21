@extends('admin.layout.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card clearfix">
                        <div class="card-header clearfix">
                            <a href="{{ url('admin/add-edit-category') }}" class="btn btn-sm btn-flat btn-info float-right">
                                <i class="fas fa-plus"></i> Tambah</a>
                            <h3 class="card-title">Kategori Layanan</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            @include('admin.partials.alert')
                            <table id="cmspages" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Kategori</th>
                                        <th>Kategori Utama</th>
                                        <th>URL</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                        <tr>
                                            <td>{{ $category['id'] }}</td>
                                            <td>{{ $category['category_name'] }}</td>
                                            <td>
                                                @if (isset($category['parentcategory']['category_name']))
                                                    {{ $category['parentcategory']['category_name'] }}
                                                @endif

                                            </td>
                                            <td>{{ $category['url'] }}</td>
                                            <td>{{ date('j F Y', strtotime($category['created_at'])) }}</td>
                                            <td>
                                                <a href="{{ url('admin/add-edit-category/' . $category['id']) }}"> <i
                                                        class="fas fa-edit text-info"></i> </a>
                                                @if ($category['status'] == 1)
                                                    <a class="updateCategoryStatus" id="category-{{ $category['id'] }}"
                                                        category_id="{{ $category['id'] }}" href="javascrip:void(0)"><i
                                                            class="fas fa-toggle-on" status="Active"></i></a>
                                                @else
                                                    <a class="updateCategoryStatus" id="category-{{ $category['id'] }}"
                                                        category_id="{{ $category['id'] }}" href="javascrip:void(0)"
                                                        style="color:grey"><i class="fas fa-toggle-off"
                                                            status="Inactive"></i></a>
                                                @endif
                                                <a class="confirmDelete" name="{{ $category['category_name'] }}"
                                                    href="javascript:void(0)" record="category"
                                                    recordid="{{ $category['id'] }}" title="Hapus Kategori"> <i
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
