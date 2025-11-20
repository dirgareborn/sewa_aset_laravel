<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DatabaseController extends Controller
{
    public function index()
    {
        // Tampilkan halaman backup & restore
        $backups = Storage::files('backups');

        return view('admin.database.index', compact('backups'));
    }

    public function backup()
    {
        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');
        $now = now();
        $bulan = months_list()[$now->month] ?? 'unknown';
        $bulan = strtolower($bulan);
        $fileName = "backup_{$dbName}_{$now->day}_{$bulan}_{$now->year}_{$now->format('H_i')}.sql";
        $filePath = storage_path("app/backups/{$fileName}");
        // Buat folder jika belum ada
        if (! File::exists(storage_path('app/backups'))) {
            File::makeDirectory(storage_path('app/backups'), 0755, true);
        }

        $skipTables = ['telescope_entries', 'telescope_entries_tags', 'telescope_monitoring'];
        if (! empty($skipTables)) {
            $skipTablesString = '';
            foreach ($skipTables as $table) {
                $skipTablesString .= "--ignore-table={$dbName}.{$table} ";
            }
            $skipTablesOption = trim($skipTablesString);
        } else {
            $skipTablesOption = '';
        }
        // Gunakan mysqldump
        $command = "mysqldump --user={$dbUser} --password={$dbPass} {$skipTablesOption} {$dbName} > {$filePath}";
        $returnVar = null;
        $output = null;
        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            return back()->with('error_message', 'Backup gagal! Pastikan mysqldump tersedia di server.');
        }

        return back()->with('success_message', "Backup berhasil! File: {$fileName}");
    }

    // Restore database via web form (DB::unprepared)
    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'nullable|string',
            'upload_file' => 'nullable|file|mimes:sql,txt',
        ]);

        if ($request->hasFile('upload_file')) {
            $filePath = $request->file('upload_file')->getRealPath();
            $fileName = $request->file('upload_file')->getClientOriginalName();
        } elseif ($request->input('backup_file')) {
            $fileName = $request->input('backup_file');
            $filePath = storage_path("app/backups/{$fileName}");
            if (! file_exists($filePath)) {
                return back()->with('error_message', 'File backup tidak ditemukan.');
            }
        } else {
            return back()->with('error_message', 'Pilih file backup atau upload file terlebih dahulu.');
        }

        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');

        $command = "mysql --user={$dbUser} --password={$dbPass} {$dbName} < {$filePath}";
        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            return back()->with('error_message', 'Restore gagal! Pastikan mysql CLI tersedia di server.');
        }

        return back()->with('success_message', 'Restore berhasil!');
    }

    public function download($fileName)
    {
        $filePath = "backups/{$fileName}";

        if (! Storage::exists($filePath)) {
            return back()->with('error_message', 'File backup tidak ditemukan.');
        }

        return Storage::download($filePath);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|string',
        ]);

        $fileName = $request->input('backup_file');
        $filePath = "backups/{$fileName}";

        if (! Storage::exists($filePath)) {
            return back()->with('error_message', 'File backup tidak ditemukan.');
        }

        Storage::delete($filePath);

        return back()->with('success_message', 'File backup berhasil dihapus.');
    }
}
