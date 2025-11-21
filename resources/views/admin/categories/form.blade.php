@extends('admin.layout.app')
@section('content')
    <div class="container-fluid">
        <h4>{{ isset($category) ? 'Edit' : 'Tambah' }} Unit Bisnis</h4>
        <form
            action="{{ isset($category) ? route('admin.categories.update', $category->id) : route('admin.categories.store') }}"
            method="POST">
            @csrf
            @if (isset($category))
                @method('PUT')
            @endif

            <div class="form-group">
                <label>Nama Unit Bisnis</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $category->name ?? '') }}"
                    required>
            </div>

            <div class="form-group">
                <label>Departemen</label>
                <select name="organization_id" class="form-control" required>
                    <option value="">-- Pilih Departemen --</option>
                    @foreach ($departments as $dep)
                        <option value="{{ $dep->id }}"
                            {{ old('organization_id', $category->organization_id ?? '') == $dep->id ? 'selected' : '' }}>
                            {{ $dep->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Parent (Sub-Unit)</label>
                <select name="parent_id" class="form-control">
                    <option value="">-- Tidak Ada --</option>
                    @foreach ($parents as $p)
                        <option value="{{ $p->id }}"
                            {{ old('parent_id', $category->parent_id ?? '') == $p->id ? 'selected' : '' }}>
                            {{ $p->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="1" {{ old('status', $category->status ?? 1) == 1 ? 'selected' : '' }}>Active
                    </option>
                    <option value="0" {{ old('status', $category->status ?? 1) == 0 ? 'selected' : '' }}>Inactive
                    </option>
                </select>
            </div>

            <button class="btn btn-success">{{ isset($category) ? 'Update' : 'Simpan' }}</button>
        </form>
    </div>
@endsection
