<?php

namespace App\Helpers;

class DumpPath
{
    public static function detect()
    {
        // Jika sudah diset di .env → langsung pakai
        if (! empty(env('DB_DUMP_PATH'))) {
            return env('DB_DUMP_PATH');
        }

        // ================= WINDOWS (Laragon, XAMPP) =================
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {

            // Cek Laragon
            $laragon = 'C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin';
            if (is_dir($laragon)) {
                return $laragon;
            }

            // Cek XAMPP
            $xampp = 'C:\xampp\mysql\bin';
            if (is_dir($xampp)) {
                return $xampp;
            }

            // Cek MySQL biasa
            $mysql = 'C:\Program Files\MySQL\MySQL Server 8.0\bin';
            if (is_dir($mysql)) {
                return $mysql;
            }
        }

        // ================= LINUX / CPANEL =================
        $commonLinuxPaths = [
            '/usr/bin',
            '/usr/local/bin',
            '/usr/mysql/bin',
            '/bin',
        ];

        foreach ($commonLinuxPaths as $path) {
            if (file_exists("$path/mysqldump")) {
                return $path;
            }
        }

        // fallback → kosong (Spatie pakai auto detect)
        return '';
    }
}
