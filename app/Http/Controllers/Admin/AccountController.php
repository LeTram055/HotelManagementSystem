<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Accounts;
use App\Models\Employees;
use App\Models\Customers;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Accounts::all();
        return view('admin.account.index')
            ->with('accounts', $accounts);
    }

    public function create()
    {
        // Lấy danh sách nhân viên và khách hàng chưa có tài khoản
        $employees = Employees::whereNull('account_id')->get();
        $customers = Customers::whereNull('account_id')->get();

        return view('admin/account/create')
            ->with('employees', $employees)
            ->with('customers', $customers);
    }

    public function save(Request $request)
    {
        $request->validate([
            'account_username' => 'required|string',
            'account_password' => 'required|string|min:6',
            'account_role' => 'required|string',
            'user_id' => 'required|integer',
            'account_active' => 'required|in:active,locked',
        ]);
        $account = new Accounts();
        $account->account_username = $request->account_username;
        $account->account_password = bcrypt($request->account_password);
        $account->account_role = $request->account_role;
        $account->account_active = $request->account_active;
        $account->save();

        if ($request->account_role == 'employee') {
            $employee = Employees::find($request->user_id);
            $employee->account_id = $account->account_id;
            $employee->save();
        } else if ($request->account_role == 'customer') {
            $customer = Customers::find($request->user_id);
            $customer->account_id = $account->account_id;
            $customer->save();
        }
        Session::flash('alert-info', 'Thêm mới thành công ^^~!!!');
        return redirect()->route('admin.account.index');
    }

    public function edit(Request $request)
    {
        $account = Accounts::find($request->account_id);
        
        // Lấy tất cả nhân viên chưa có tài khoản hoặc là người dùng của tài khoản hiện tại
        $employees = Employees::whereNull('account_id')
            ->orWhere('account_id', $account->account_id)
            ->get();

        // Lấy tất cả khách hàng chưa có tài khoản hoặc là người dùng của tài khoản hiện tại
        $customers = Customers::whereNull('account_id')
            ->orWhere('account_id', $account->account_id)
            ->get();

        return view('admin.account.edit')
            ->with('account', $account)
            ->with('employees', $employees)
            ->with('customers', $customers);
    }

    public function update(Request $request)
    {
        $request->validate([
            'account_username' => 'required|string',
            'account_role' => 'required|string',
            'user_id' => 'required|integer',
            'account_active' => 'required|in:active,locked',
        ]);

        $account = Accounts::find($request->account_id);
        $account->account_username = $request->account_username;
        $account->account_role = $request->account_role;
        $account->account_active = $request->account_active;
        $account->save();

        if ($request->account_role == 'employee') {
            $employee = Employees::find($request->user_id);
            $employee->account_id = $account->account_id;
            $employee->save();
        } else if ($request->account_role == 'customer') {
            $customer = Customers::find($request->user_id);
            $customer->account_id = $account->account_id;
            $customer->save();
        }
        Session::flash('alert-info', 'Cập nhật thành công ^^~!!!');
        return redirect()->route('admin.account.index');
    }

    public function destroy(Request $request)
    {
        $account = Accounts::find($request->account_id);

        if ($account->account_role == 'customer') {
            $customer = Customers::where('account_id', $account->account_id)->first();
            $customer->account_id = null;
            $customer->save();
        } else if ($account->account_role == 'employee') {
            $employee = Employees::where('account_id', $account->account_id)->first();
            $employee->account_id = null;
            $employee->save();
        }
        $account->delete();
        
        Session::flash('alert-info', 'Xóa thành công ^^~!!!');
        return redirect()->route('admin.account.index');
    }
}