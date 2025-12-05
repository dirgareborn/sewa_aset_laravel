<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Page;
use App\Models\Employee;
use App\Models\Information;
use App\Models\Mitra;
use App\Models\Service;
use App\Models\ProfilWebsite;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

class PageController extends Controller
{
    public function getRouteKeyName()
    {
        return 'url';
    }

    public function show(Page $page)
    {

         // Dapatkan URL path request
        $page = request()->path();

        // Cari halaman berdasarkan URL & status
        $cmspageDetail = Page::where('url', $page)
            ->where('status', 'ready')
            ->firstOrFail(); // aman, otomatis error 404 jika tidak ada
        return view('front.pages.page', compact('cmspageDetail'));
        // $url = Route::getFacadeRoot()->current()->uri;
        // $pageCount = Page::where(['url' => $url, 'status' => 'Ready'])->count();
        // if ($pageCount > 0) {
        //     $cmspageDetail = Page::cmspageDetails($url);

        //     // dd($cmspageDetail);
        //     return view('front.pages.page')->with(['cmspageDetail' => $cmspageDetail]);
        // } else {
        //     abort(404);
        // }

    }

    public function index()
    {
        $homeSliderBanners = Banner::activeSlider()->get()->toArray();
        $services = Service::active()->with(['images', 'locations', 'unit'])->latest()->get()->toArray();
        // dd($products);
        $getTestimonial = Testimonial::approved()->with('user')->get()->toArray();
        $informations = Information::where('status', 'published')->latest('published_at')->paginate(9);
        $mitra = Mitra::where('status', 1)->latest()->get();
        $teams = Employee::where('status', 1)->latest()->get();
        // dd($teams);
        // dd($mitra);
        $meta_title = 'BPB UNM';
        $meta_description = 'Badan Pengembangan Bisnis Universitas Negeri Makassar (BPB UNM) adalah unit yang berfokus pada pengembangan dan pengelolaan bisnis di lingkungan Universitas Negeri Makassar untuk mendukung visi dan misi universitas dalam menciptakan manfaat bagi masyarakat luas.';

        return view('front.index')->with(compact('homeSliderBanners', 'mitra', 'services', 'getTestimonial', 'meta_title', 'informations', 'meta_description', 'teams'));
    }

    public function visiMisi()
    {
        $visimisi = ProfilWebsite::first();
        $page_title = 'Visi dan Misi BPB UNM';

        return view('front.pages.visi-misi', compact('visimisi', 'page_title'));
    }

    public function strukturOrganisasi()
    {
        $strukturOrganisasi = ProfilWebsite::first();
        $page_title = 'Struktur Organisasi';

        return view('front.pages.struktur-organisasi', compact('strukturOrganisasi', 'page_title'));

    }

    public function kontak()
    {
        $kontak = ProfilWebsite::first();
        $page_title = 'Kontak Kami';

        return view('front.pages.contact', compact('kontak', 'page_title'));
    }

    public function kirimKontak(Request $request)
    {
        // ✅ Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'subject' => 'nullable|string|max:150',
            'message' => 'required|string|max:2000',
        ], [
            'required' => ':attribute wajib diisi.',
            'email' => 'Format email tidak valid.',
            'max' => ':attribute terlalu panjang.',
        ]);

        try {
            // ✅ Kirim email menggunakan Mail::send agar bisa dikembangkan nanti
            Mail::raw(
                "📩 Pesan baru dari formulir kontak:\n\n".
                "Nama: {$validated['name']}\n".
                "Email: {$validated['email']}\n".
                ($validated['subject'] ? "Subjek: {$validated['subject']}\n" : '').
                "\nPesan:\n{$validated['message']}",
                function ($mail) {
                    $mail->to('bpbblu.unm@gmail.com') // email tujuan
                        ->subject('Pesan Baru dari Form Kontak');
                }
            );

            // ✅ (Opsional) Simpan log pesan ke database atau file log
            Log::info('Pesan kontak terkirim', $validated);

            return response()->json([
                'success' => true,
                'message' => 'Pesan berhasil dikirim! Terima kasih telah menghubungi kami.',
            ]);

        } catch (\Exception $e) {
            Log::error('Gagal mengirim pesan kontak: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengirim pesan. Silakan coba lagi nanti.',
            ], 500);
        }
    }

    public function faq()
    {
        // $kontak = ProfilWebsite::first();
        $page_title = 'Pertanyaan Umum';

        return view('front.pages.faq', compact('page_title'));
    }

    public function cookies()
    {
        // $kontak = ProfilWebsite::first();
        $page_title = 'kebijakan Privasi';

        return view('front.pages.cookies', compact('page_title'));
    }
}
