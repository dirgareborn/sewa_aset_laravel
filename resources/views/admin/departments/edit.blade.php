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
                        <label>Parent Department (optional)</label>
                        <select name="parent_id" class="form-control">
                            <option value="">-- none --</option>
                            @foreach ($parents as $p)
                                <option value="{{ $p->id }}"
                                    {{ old('parent_id', $department->parent_id) == $p->id ? 'selected' : '' }}>
                                    {{ $p->name }}</option>
                            @endforeach
                        </select>
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
