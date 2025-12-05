<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccountBank;
use App\Models\AdminRole;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class AccountBankController extends Controller
{
    public function index()
    {
        Session::put('page', 'accountbanks');
        $banks = AccountBank::with('service')->paginate(10);
        // Set Admin/Subadmins Permissions
        $accountbanksModuleCount = AdminRole::where(['admin_id' => Auth::guard('admin')->user()->id, 'module' => 'accountbanksModule'])->count();
        $accountbanksModule = [];
        if (Auth::guard('admin')->user()->type == 'admin') {
            $accountbanksModule['view_access'] = 1;
            $accountbanksModule['edit_access'] = 1;
            $accountbanksModule['full_access'] = 1;
        } elseif ($accountbanksModuleCount == 0) {
            $message = 'This Featu is retriced for you!';

            return redirect('admin/dashboard')->with('error_message', $message);
        } else {
            $accountbanksModule = AdminRole::where(['admin_id' => Auth::guard('admin')->user()->id, 'module' => 'accountbanksModule'])->first()->toArray();
        }

        return view('admin.banks.index', compact('banks', 'accountbanksModule'));
    }
public function create()
    {
        return view('admin.banks.form', [
            'bank' => null,
            'services' => Service::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'service_id' => 'required|exists:services,id',
            'type' => 'required|in:qris,va',

            // VA
            'bank_name' => 'nullable|string',
            'account_name' => 'nullable|string',
            'account_number' => 'nullable|string',
            'bank_icon' => 'nullable|image|max:2048',

            // QRIS
            'qris_image' => 'nullable|image|max:2048',
            'merchant_name' => 'nullable|string',
            'merchant_id' => 'nullable|string',

            'status' => 'required|boolean',
        ]);

        if ($request->hasFile('bank_icon')) {
            $data['bank_icon'] = $request->file('bank_icon')->store('uploads/bank_icons', 'public');
        }

        if ($request->hasFile('qris_image')) {
            $data['qris_image'] = $request->file('qris_image')->store('uploads/qris', 'public');
        }

        AccountBank::create($data);

        return redirect()->route('admin.banks.index')->with('success', 'Account bank created');
    }

    public function edit(AccountBank $bank)
    {
        return view('admin.banks.form', [
            'bank' => $bank,
            'services' => Service::all()
        ]);
    }

    public function update(Request $request, AccountBank $bank)
    {
        $data = $request->validate([
            'service_id' => 'required|exists:services,id',
            'type' => 'required|in:qris,va',

            'bank_name' => 'nullable|string',
            'account_name' => 'nullable|string',
            'account_number' => 'nullable|string',
            'bank_icon' => 'nullable|image|max:2048',

            'qris_image' => 'nullable|image|max:2048',
            'merchant_name' => 'nullable|string',
            'merchant_id' => 'nullable|string',

            'status' => 'required|boolean',
        ]);

        if ($request->hasFile('bank_icon')) {
            Storage::disk('public')->delete($bank->bank_icon);
            $data['bank_icon'] = $request->file('bank_icon')->storeAs('uploads/bank_icons', 'public');
        }

        if ($request->hasFile('qris_image')) {
            Storage::disk('public')->delete($bank->qris_image);
            $data['qris_image'] = $request->file('qris_image')->storeAs('uploads/qris', 'public');
        }

        $bank->update($data);

        return redirect()->route('admin.banks.index')->with('success', 'Account bank updated');
    }

    public function destroy(AccountBank $bank)
    {
        if ($bank->bank_icon) Storage::disk('public')->delete($bank->bank_icon);
        if ($bank->qris_image) Storage::disk('public')->delete($bank->qris_image);

        $bank->delete();

        return back()->with('success', 'Account bank deleted');
    }
}