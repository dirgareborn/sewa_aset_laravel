@extends('admin.layout.app')

@section('content')
    <section class="content">
        <div class="container-fluid">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tambah API Key</h3>
                </div>

                <div class="card-body">

                    <form action="{{ route('admin.api-keys.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label>Service</label>
                            <input type="text" name="service" class="form-control" required
                                placeholder="midtrans / google / wa">
                        </div>

                        <div class="form-group">
                            <label>Key Name</label>
                            <input type="text" name="key_name" class="form-control" required
                                placeholder="server_key / map_key">
                        </div>

                        <div class="form-group">
                            <label>Key Value</label>
                            <input type="text" name="key_value" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Deskripsi (Opsional)</label>
                            <textarea name="description" class="form-control"></textarea>
                        </div>

                        <button class="btn btn-primary">Simpan</button>
                        <a href="{{ route('admin.api-keys.index') }}" class="btn btn-secondary">Kembali</a>

                    </form>

                </div>
            </div>

        </div>
    </section>
@endsection
