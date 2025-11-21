@extends('admin.layout.app')
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3>Create Unit</h3>
                <a href="{{ route('admin.units.index') }}" class="btn btn-sm btn-info float-right">Back</a>
            </div>
            <div class="card-body">
                @include('admin.partials.alert')
                <form action="{{ route('admin.units.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Department</label>
                        <select name="department_id" class="form-control" required>
                            @foreach ($departments as $d)
                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Parent Unit (optional)</label>
                        <select name="parent_id" class="form-control">
                            <option value="">-- none --</option>
                            @foreach ($parents as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->department->name ?? '' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group"><label>Name</label><input name="name" class="form-control" required></div>
                    <div class="form-group"><label>Type</label><input name="type" class="form-control"></div>
                    <div class="form-group"><label>Description</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                    <div class="form-group"><label>Active</label><input type="checkbox" name="is_active" checked></div>
                    <button class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
@endsection
