@extends('admin.layout.app')
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3>Edit Product</h3><a href="{{ route('admin.products.index') }}"
                    class="btn btn-sm btn-info float-right">Back</a>
            </div>
            <div class="card-body">
                @include('admin.partials.alert')
                <form action="{{ route('admin.products.update', $product) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="form-group">
                        <label>Unit</label>
                        <select name="unit_id" class="form-control" required>
                            @foreach ($units as $u)
                                <option value="{{ $u->id }}" {{ $product->unit_id == $u->id ? 'selected' : '' }}>
                                    {{ $u->name }} ({{ $u->department->name ?? '' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group"><label>Name</label><input name="name" class="form-control"
                            value="{{ $product->name }}" required></div>
                    <div class="form-group"><label>Price</label><input name="price" class="form-control"
                            value="{{ $product->price }}" type="number" step="0.01"></div>
                    <div class="form-group"><label>Stock</label><input name="stock" class="form-control"
                            value="{{ $product->stock }}" type="number"></div>
                    <div class="form-group"><label>Description</label>
                        <textarea name="description" class="form-control">{{ $product->description }}</textarea>
                    </div>
                    <div class="form-group"><label>Active</label><input type="checkbox" name="status"
                            {{ $product->status ? 'checked' : '' }}></div>
                    <button class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection
