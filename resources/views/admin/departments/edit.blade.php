@extends('admin.layout.app')
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3>Edit Department</h3>
                <a href="{{ route('admin.departments.index') }}" class="btn btn-sm btn-info float-right">Back</a>
            </div>
            <div class="card-body">
                @include('admin.partials.alert')
                <form action="{{ route('admin.departments.update', $department) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="form-group">
                        <label>Name</label>
                        <input name="name" class="form-control" value="{{ old('name', $department->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control">{{ old('description', $department->description) }}</textarea>
                    </div>
                    <button class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection
