<?php

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

if (! function_exists('cartItems')) {
    /**
     * Ambil cart items per user/session
     * Gunakan cache sebentar 1 menit agar tidak berulang
     */
    function cartItems()
    {
        $user_id = Auth::id();
        $session_id = Session::get('session_id') ?? Session::getId();
        Session::put('session_id', $session_id);

        $query = Cart::with(['product', 'product.category']);

        $user_id
            ? $query->where('user_id', $user_id)
            : $query->where('session_id', $session_id);

        return $query->get();
    }
}

if (! function_exists('totalCartItems')) {
    /**
     * Hitung total cart items dari collection yang sudah ada
     */
    function totalCartItems()
    {
        return cartItems()->count();
    }
}

function getCartItems()
{
    $getCartItems = cartItems();

    return $getCartItems;
}
function getCartItemsDashboard()
{
    $getCartItemsDashboard = Cart::with('product')->get()->toArray();

    return $getCartItemsDashboard;
}

if (! function_exists('user_pending_orders')) {
    function user_pending_orders()
    {
        $user = auth()->user();
        if (! $user) {
            return 0;
        }

        return Order::where('user_id', $user->id)
            ->whereIn('order_status', ['waiting', 'approved', 'rejected', 'completed', 'cancelled'])
            ->count();
    }
}
