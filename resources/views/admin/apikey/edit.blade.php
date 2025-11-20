@extends('admin.layout.app')

@section('content')
    <section class="content">
        <div class="container-fluid">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit API Key</h3>
                </div>

                <div class="card-body">

                    <form action="{{ route('admin.api-keys.update', $apiKey->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Service</label>
                            <input type="text" name="service" value="{{ $apiKey->service }}" class="form-control"
                                required>
                        </div>

                        <div class="form-group">
                            <label>Key Name</label>
                            <input type="text" name="key_name" value="{{ $apiKey->key_name }}" class="form-control"
                                required>
                        </div>

                        <div class="form-group">
                            <label>Key Value</label>
                            <input type="text" name="key_value" value="{{ $apiKey->key_value }}" class="form-control"
                                required>
                        </div>

                        <div class="form-group">
                            <label>Deskripsi (Opsional)</label>
                            <textarea name="description" class="form-control">{{ $apiKey->description }}</textarea>
                        </div>

                        <button class="btn btn-primary">Update</button>
                        <a href="{{ route('admin.api-keys.index') }}" class="btn btn-secondary">Kembali</a>

                    </form>

                </div>
            </div>

        </div>
    </section>
@endsection
