@extends('admin.layout.app')
@section('content')
    <div class="container-fluid">
        <h4>Unit Bisnis / Categories</h4>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary mb-3">Tambah Unit Bisnis</a>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Departemen</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                {!! \App\Helpers\CategoryHelper::renderTree($categories->whereNull('parent_id')) !!}
            </tbody>
        </table>
    </div>
@endsection
