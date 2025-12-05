<?php

return [

    [
        'title' => 'Dashboard',
        'icon' => 'fas fa-th',
        'route' => 'admin.dashboard',
    ],

    [
        'title' => 'Manajemen Admin',
        'icon' => 'fas fa-user-cog',
        'submenu' => [
            [
                'title' => 'Daftar Admin',
                'route' => 'admin.subadmins',
                'permission' => 'admin',
            ],
            [
                'title' => 'Roles',
                'route' => 'admin.roles.index',
                'permission' => 'admin',
            ],
        ],
    ],

    [
        'title' => 'Master Data',
        'icon' => 'fas fa-boxes',
        'submenu' => [
            ['title' => 'Departemen', 'route' => 'admin.departments.index'],      // Diperbaiki
            ['title' => 'Unit Bisnis', 'route' => 'admin.units.index'],      // Diperbaiki
            ['title' => 'Layanan', 'route' => 'admin.services.index'],       // Benar
            ['title' => 'Banner', 'route' => 'admin.banners.index'],         // Benar
            ['title' => 'Kupon', 'route' => 'admin.coupons.index'],          // Benar
            ['title' => 'Bank', 'route' => 'admin.banks.index'],     // Diperbaiki
            ['title' => 'Karyawan', 'route' => 'admin.employees.index'],     // Diperbaiki
        ],
    ],

    [
        'title' => 'Manajemen Order',
        'icon' => 'fas fa-shopping-cart',
        'submenu' => [
            ['title' => 'Penyewa', 'route' => 'admin.customers.index'],
            ['title' => 'Bookingan', 'route' => 'admin.bookings.index'],           // Diperbaiki
            ['title' => 'Kalender Penggunaan', 'route' => 'admin.calendar.index'],
        ],
    ],

    [
        'title' => 'Kerjasama',
        'icon' => 'fas fa-handshake',
        'submenu' => [
            ['title' => 'Kerjasama', 'route' => 'admin.mitra.index'],        // Diperbaiki
            ['title' => 'Mitra', 'route' => 'admin.mitra.index'],
        ],
    ],

    [
        'title' => 'Dokumen dan Informasi',
        'header' => true,
    ],

    [
        'title' => 'Informasi',
        'icon' => 'fas fa-info-circle',
        'route' => 'admin.information.index',
    ],

    [
        'title' => 'Dokumen',
        'icon' => 'fas fa-file',
        'route' => 'admin.document.index',
    ],

    [
        'title' => 'File Manager',
        'header' => true,
    ],

    [
        'title' => 'File',
        'icon' => 'fas fa-file',
        'route' => 'admin.files.index',
    ],

    [
        'title' => 'System',
        'header' => true,
    ],

    [
        'title' => 'Pengunjung',
        'icon' => 'fas fa-users',
        'route' => 'admin.pengunjung',
    ],

    [
        'title' => 'Informasi & Log',
        'icon' => 'fas fa-info-circle',
        'route' => 'admin.system',
    ],

    [
        'title' => 'Database',
        'icon' => 'fas fa-database',
        'route' => 'admin.database',
    ],

    [
        'title' => 'Servis API Keys',
        'icon' => 'fas fa-key',
        'route' => 'admin.api-keys.index',
    ],

];
