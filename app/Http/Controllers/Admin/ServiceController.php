<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Service;
use App\Models\ServicesImage;
use App\Models\ServicePrice;
use App\Models\Unit;
use App\Models\AdminRole;
use App\Models\ServiceImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\File;
class ServiceController extends Controller
{
    public function index()
    {
        $title = 'Layanan';
        $services = Service::with(['unit.department','location'])->paginate(20);
        return view('admin.services.index', compact('services', 'title'));
    }

    public function create()
    {
        return view('admin.services.create', [
            'title' => 'Tambah Layanan',
            'units' => Unit::with('department')->get(),
            'locations' => Location::all(),
            'service' => new Service,
            'slides' => collect(),
        ]);
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'location_id' => 'nullable|exists:locations,id',
            'name' => 'required|string|max:200|unique:services,name',
            'base_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'is_price_per_type' => 'nullable|boolean',
            'customer_type.*' => 'nullable|in:umum,civitas,mahasiswa',
            'price.*' => 'nullable|numeric|min:0',
            'facility' => 'nullable|string',
        ]);

        $service = new Service;
        $service->unit_id = $request->unit_id;
        $service->location_id = $request->location_id;
        $service->name = $request->name;
        $service->description = $request->description;
        $service->base_price = $request->base_price ?? 0;
        $service->status = $request->status ? 1 : 0;
        $service->is_price_per_type = $request->boolean('is_price_per_type');
        $service->slug = Str::slug($request->name).'-'.Str::random(5);

        if ($request->facility) {
            $service->facility = collect(explode(',', $request->facility))->map(fn ($i) => ['name' => trim($i)]);
        }
        $service->save();

        // Upload slide images
        if ($request->hasFile('slides')) {
            foreach ($request->file('slides') as $img) {
                $name = 'slide-'.Str::slug($request->name).'-'.Str::random(4).'.'.$img->getClientOriginalExtension();
                $img->storeAs('uploads/services/slides', $name, 'public');
                $service->slides()->create([
                    'service_id' => $service->id,
                    'image' => $name,
                ]);
            }
        }

        // Save price per customer type
        if ($request->is_price_per_type == true) {
            foreach ($request->customer_type ?? [] as $key => $type) {
                if (! $type || empty($request->price[$key])) {
                    continue;
                }

                ServicePrice::create([
                    'service_id' => $service->id,
                    'customer_type' => $type,
                    'price' => $request->price[$key],
                ]);
            }
        }

        return redirect()->route('admin.services.index')->with('success', 'Service created');
    }

    public function edit(Service $service)
    {
        // dd($service->facility);
        return view('admin.services.edit', [
            'service' => $service,
            'units' => Unit::with('department')->get(),
            'locations' => Location::all(),
            'slides' => $service->slides,
        ]);
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'location_id' => 'nullable|exists:locations,id',
            'name' => 'required|string|max:200',
            'base_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'facility' => 'nullable|string',
            'customer_type.*' => 'nullable|in:umum,civitas,mahasiswa',
            'price.*' => 'nullable|numeric|min:0',
        ]);

        // Update service
        $service->unit_id = $request->unit_id;
        $service->location_id = $request->location_id;
        $service->name = $request->name;
        $service->description = $request->description;
        $service->base_price = $request->base_price ?? 0;
        $service->is_price_per_type = $request->boolean('is_price_per_type');
        $service->status = $request->status ? 1 : 0;

        if ($request->facility) {
            $service->facility = collect(explode(',', $request->facility))
                ->map(fn ($i) => ['name' => trim($i)]);
        }

        if ($request->hasFile('cover')) {
            Storage::disk('public')->delete('uploads/services/'.$service->image);
            $file = $request->file('cover');
            $filename = 'cover-'.Str::slug($request->name).'-'.Str::random(3).'.'.$file->getClientOriginalExtension();
            $file->storeAs('uploads/services', $filename, 'public');
            $service->image = $filename;
        }

        $service->save();

        // Replace slide images if new uploaded
        if ($request->hasFile('slides')) {
            foreach ($request->file('slides') as $img) {
                $name = 'slide-'.Str::slug($request->name).'-'.Str::random(3).'.'.$img->getClientOriginalExtension();
                $img->storeAs('uploads/services/slides', $name, 'public');
                $service->slides()->create([
                    'service_id' => $service->id,
                    'image' => $name,

                ]);
            }
        }

        // Replace price details
        ServicePrice::where('service_id', $service->id)->delete();

        if ($request->is_price_per_type == 1) {
            foreach ($request->customer_type ?? [] as $key => $type) {
                if (! $type || empty($request->price[$key])) {
                    continue;
                }

                ServicePrice::create([
                    'service_id' => $service->id,
                    'customer_type' => $type,
                    'price' => $request->price[$key],
                ]);
            }
        }

        return redirect()->route('admin.services.index')->with('success', 'Service updated');
    }
    /**
     * Update Status Service the specified resource in storage.
     */
    public function updateStatus(Request $request, Service $service)
    {
        if ($request->ajax()) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            if ($data['status'] == 'Active') {
                $status = 0;
            } else {
                $status = 1;
            }
            Service::where('id', $data['service_id'])->update(['status' => $status]);

            return response()->json(['status' => $status, 'service_id' => $data['service_id']]);
        }
    }

    public function deleteSlide($id)
    {
        $slide = ServiceImage::findOrFail($id);
        $path = 'storage/app/public/uploads/services/slides/'.$slide->image;
        if (File::exists($path)) {
            File::delete($path);
        }
        $slide->delete();

        return back()->with('success', 'Slide berhasil dihapus');
    }
}
