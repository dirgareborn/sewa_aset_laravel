@extends('admin.layout.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header clearfix">
                <h3 class="card-title float-left">Departments</h3>
                <a href="{{ route('admin.departments.create') }}" class="btn btn-primary btn-sm float-right">Add
                    Department</a>
            </div>
            <div class="card-body">
                @include('admin.partials.alert')
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Parent Department</th>
                            <th>Slug</th>
                            <th>Units</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($departments as $d)
                            <tr>
                                <td>{{ $d->name }}</td>
                                <td>{{ $d->parent->name ?? '' }}</td>
                                <td>{{ $d->slug }}</td>
                                <td>{{ $d->units()->count() }}</td>
                                <td>
                                    <a href="{{ route('admin.departments.edit', $d) }}"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('admin.departments.destroy', $d) }}" method="POST"
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
                {{ $departments->links() }}
            </div>
        </div>
    </div>
@endsection
