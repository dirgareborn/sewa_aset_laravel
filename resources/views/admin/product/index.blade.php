@extends('admin.layout.app')
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header clearfix">
                <h3 class="card-title float-left">Products</h3>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm float-right">Add Product</a>
            </div>
            <div class="card-body">
                @include('admin.partials.alert')
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Unit</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Active</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $p)
                            <tr>
                                <td>{{ $p->name }}</td>
                                <td>{{ $p->unit->name ?? '-' }} ({{ $p->unit->department->name ?? '-' }})</td>
                                <td>{{ $p->price }}</td>
                                <td>{{ $p->stock }}</td>
                                <td>{{ $p->status ? 'Yes' : 'No' }}</td>
                                <td>
                                    <a href="{{ route('admin.products.edit', $p) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('admin.products.destroy', $p) }}" method="POST"
                                        style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm"
                                            onclick="return confirm('Delete?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection
