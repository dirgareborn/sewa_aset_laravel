@extends('admin.layout.app')
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3>Edit Unit</h3>
                <a href="{{ route('admin.units.index') }}" class="btn btn-sm btn-info float-right">Back</a>
            </div>
            <div class="card-body">
                @include('admin.partials.alert')
                <form action="{{ route('admin.units.update', $unit) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="form-group">
                        <label>Department</label>
                        <select name="department_id" class="form-control" required>
                            @foreach ($departments as $d)
                                <option value="{{ $d->id }}" {{ $unit->department_id == $d->id ? 'selected' : '' }}>
                                    {{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Parent Unit (optional)</label>
                        <select name="parent_id" class="form-control">
                            <option value="">-- none --</option>
                            @foreach ($parents as $p)
                                <option value="{{ $p->id }}" {{ $unit->parent_id == $p->id ? 'selected' : '' }}>
                                    {{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group"><label>Name</label><input name="name" class="form-control"
                            value="{{ $unit->name }}" required></div>
                    <div class="form-group"><label>Type</label><input name="type" class="form-control"
                            value="{{ $unit->type }}"></div>
                    <div class="form-group"><label>Description</label>
                        <textarea name="description" class="form-control">{{ $unit->description }}</textarea>
                    </div>
                    <div class="form-group"><label>Active</label><input type="checkbox" name="is_active"
                            {{ $unit->is_active ? 'checked' : '' }}></div>
                    <button class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection
