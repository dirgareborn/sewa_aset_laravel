<?php 
use App\Models\AdminRole;
use Illuminate\Support\Facades\Auth;

function hasAccess($module, $permission)
{
    $user = Auth::guard('admin')->user();

    // Jika type admin → semua akses dibuka
    if ($user && $user->type === 'admin') {
        return true;
    }

    $role = AdminRole::where('module', $module)->first();

    // Jika role tidak ditemukan → tidak punya akses
    if (!$role) {
        return false;
    }

    // Jika full access
    if ($role->full_access == 1) {
        return true;
    }

    // Akses biasa
    return $role->{$permission . '_access'} == 1;
}

function hasFullAccess($module)
{
    $user = Auth::guard('admin')->user();

    // Type admin → full akses otomatis
    if ($user && $user->type === 'admin') {
        return true;
    }

    $role = AdminRole::where('module', $module)->first();

    return $role?->full_access == 1;
}