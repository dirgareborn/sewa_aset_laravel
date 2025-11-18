<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mitra;
use Illuminate\Support\Facades\File;
use App\Http\Requests\MitraStoreRequest;
use App\Http\Requests\MitraUpdateRequest;
use Illuminate\Support\Facades\Storage;

class MitraController extends Controller
{
    public function index()
    {
        $mitra = Mitra::latest()->get();
        return view('admin.mitra.index', compact('mitra'));
    }

    public function create()
    {
        return view('admin.mitra.create');
    }

    public function store(MitraStoreRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('uploads/mitra', 'public');
            $data['logo'] = basename($path); // Simpan hanya nama file
        }

        Mitra::create($data);

        return redirect()->route('admin.mitra.index')->with('success', 'Mitra berhasil ditambahkan');
    }

    public function edit(Mitra $mitra)
    {
        return view('admin.mitra.edit', compact('mitra'));
    }

    public function update(MitraUpdateRequest $request, Mitra $mitra)
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            // Hapus file lama jika ada
            if ($mitra->logo && Storage::disk('public')->exists('uploads/mitra/'.$mitra->logo)) {
                Storage::disk('public')->delete('uploads/mitra/'.$mitra->logo);
            }

            // Simpan file baru
            $path = $request->file('logo')->store('uploads/mitra', 'public');
            $data['logo'] = basename($path);
        }

        $mitra->update($data);

        return redirect()->route('admin.mitra.index')->with('success', 'Mitra berhasil diperbarui');
    }

    public function destroy(Mitra $mitra)
    {
        // Jangan dihapus
    }

    public function toggleStatus(Mitra $mitra)
    {
        $mitra->status = !$mitra->status;
        $mitra->save();
        return back()->with('success', 'Status mitra diperbarui.');
    }

    public function updateStatus(Request $request){
        
        if($request->ajax()){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            if($data['status']=="Active"){
                $status = 0;
            }else{
                $status = 1;
            }
            Mitra::where('id', $data['mitra_id'])->update(['status'=>$status]);
            return response()->json(['status'=>$status,'mitra_id'=>$data['mitra_id']]);
        }
    }

    public function deleteMitra($id)
    {
        $mitra = Mitra::find($id);

        if ($mitra) {
            // Hapus file logo jika ada
            if ($mitra->logo && Storage::disk('public')->exists('uploads/mitra/' . $mitra->logo)) {
                Storage::disk('public')->delete('uploads/mitra/' . $mitra->logo);
            }

            // Hapus data Mitra
            $mitra->delete();

            return redirect()->back()->with('success_message', 'Data Mitra Berhasil dihapus');
        }

        return redirect()->back()->with('error_message', 'Mitra tidak ditemukan');
    }
}
