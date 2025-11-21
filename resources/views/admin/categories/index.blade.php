@extends('admin.layout.app')
@section('content')
    <div class="container-fluid">
        <h4>Unit Bisnis / Categories</h4>
        <a href="{{ route('categories.create') }}" class="btn btn-primary mb-3">Tambah Unit Bisnis</a>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Departemen</th>
                    <th>Parent</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $cat)
                    <tr>
                        <td>{{ $cat->name }}</td>
                        <td>{{ $cat->organization->name ?? '-' }}</td>
                        <td>{{ $cat->parent->name ?? '-' }}</td>
                        <td>{{ $cat->status ? 'Active' : 'Inactive' }}</td>
                        <td>
                            <a href="{{ route('categories.edit', $cat->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Hapus unit bisnis ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
