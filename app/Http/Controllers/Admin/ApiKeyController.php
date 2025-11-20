<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ApiKeyController extends Controller
{
    public function index()
    {
        $apiKeys = ApiKey::paginate(10);

        return view('admin.apikey.index', compact('apiKeys'));
    }

    public function create()
    {
        return view('admin.apikey.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'service' => 'required',
            'key_name' => 'required',
            'key_value' => 'required',
        ]);

        ApiKey::create($request->all());
        Cache::forget('api_keys');

        return redirect()->route('admin.api-keys.index')
            ->with('success', 'API Key berhasil ditambahkan.');
    }

    public function edit(ApiKey $apiKey)
    {
        return view('admin.apikey.edit', compact('apiKey'));
    }

    public function update(Request $request, ApiKey $apiKey)
    {
        $request->validate([
            'service' => 'required',
            'key_name' => 'required',
        ]);

        $apiKey->update($request->all());
        Cache::forget('api_keys');

        return redirect()->route('admin.api-keys.index')
            ->with('success', 'API Key berhasil diperbarui.');
    }

    public function destroy(ApiKey $apiKey)
    {
        $apiKey->delete();
        Cache::forget('api_keys');

        return redirect()->route('admin.api-keys.index')
            ->with('success', 'API Key berhasil dihapus.');
    }
}
