<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Newsletter;

class NewsletterController extends Controller
{
    public function store(Request $request)
    {
        if (! Newsletter::isSubscribed($request->email)) {
            Newsletter::subscribePending($request->email);

            return redirect('newsletter')->with('success', 'Thanks For Subscribe');
        }

        return redirect('newsletter')->with('failure', 'Sorry! You have already subscribed ');

    }
}
