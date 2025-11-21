@extends('admin.layout.app')
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header clearfix">
                <h3 class="card-title float-left">Units</h3>
                <a href="{{ route('admin.units.create') }}" class="btn btn-primary btn-sm float-right">Add Unit</a>
            </div>
            <div class="card-body">
                @include('admin.partials.alert')
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Parent</th>
                            <th>Type</th>
                            <th>Active</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($units as $u)
                            <tr>
                                <td>{{ $u->name }}</td>
                                <td>{{ $u->department->name ?? '-' }}</td>
                                <td>{{ $u->parent->name ?? '-' }}</td>
                                <td>{{ $u->type }}</td>
                                <td>{{ $u->is_active ? 'Yes' : 'No' }}</td>
                                <td>
                                    <a href="{{ route('admin.units.edit', $u) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('admin.units.destroy', $u) }}" method="POST"
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
            </div>
        </div>
    </div>
@endsection
