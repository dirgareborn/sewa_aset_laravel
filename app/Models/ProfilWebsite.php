<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilWebsite extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = [
        'maps',
        'alamat',
        'kode_pos',
        'telepon',
        'email',
        'file_struktur_organisasi',
        'file_logo',
        'sambutan',
        'socialmedia',
        'visi',
        'misi',
    ];

    protected $cast = [
        'socialmedia' => 'array',
    ];

    protected function socialmedia(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }
}
