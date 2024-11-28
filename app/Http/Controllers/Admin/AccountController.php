<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Accounts;
use App\Models\Employees;
use App\Models\Customers;

use App\Exports\AccountsExport;
use Maatwebsite\Excel\Facades\Excel;

class AccountController extends Controller
{
    public function index(Request $request) {
        $sortField = $request->input('sort_field', 'account_id'); // Mặc định sắp xếp theo account_id
        $sortDirection = $request->input('sort_direction', 'asc'); // Mặc định sắp xếp tăng dần

        $query = Accounts::query();
        // Kiểm tra nếu có input tìm kiếm
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            
            $query->where('account_id', 'like', '%' . $searchTerm . '%')
                ->orwhere('account_username', 'like', '%' . $searchTerm . '%')
                ->orWhere('account_role', 'like', '%' . $searchTerm . '%')
                ->orWhere('account_active', 'like', '%' . $searchTerm . '%');
        }

        if (in_array($sortField, ['account_username', 'account_role','account_active'])) {
            $query->orderByRaw("CONVERT($sortField USING utf8) COLLATE utf8_unicode_ci $sortDirection");
        } elseif ($sortField == 'account_id') {
            // Sắp xếp giá theo kiểu số
            $query->orderByRaw("CAST($sortField AS DECIMAL) $sortDirection");
        } 
        else {
            $query->orderByRaw("CONVERT(account_id USING utf8) COLLATE utf8_unicode_ci asc");
        }

        $accounts = $query->get();
        return view('admin.account.index')
        ->with('accounts', $accounts)
        ->with('sortField', $sortField)
        ->with('sortDirection', $sortDirection);
    }

    public function create()
    {
        // Lấy danh sách nhân viên và khách hàng chưa có tài khoản
        $employees = Employees::whereNull('account_id')->get();
        

        return view('admin/account/create')
            ->with('employees', $employees);
            
    }

    public function save(Request $request)
    {
        $request->validate([
            'account_username' => 'required|string|unique:accounts',
            'account_password' => 'required|string|min:8',
            //'account_role' => 'required|string',
            'user_id' => 'required|integer',
            'account_active' => 'required|in:active,locked',
        ], [
        'account_username.unique' => 'Tên tài khoản đã tồn tại. Vui lòng chọn tên khác.',
        'account_username.required' => 'Tên tài khoản không được để trống.',
        'account_password.required' => 'Mật khẩu không được để trống.',
        'account_password.min' => 'Mật khẩu phải có ít nhất :min ký tự.',
        //'account_role.required' => 'Vai trò tài khoản không được để trống.',
        'user_id.required' => 'Người dùng liên kết không được để trống.',
        
        'account_active.required' => 'Trạng thái tài khoản không được để trống.',
        'account_active.in' => 'Trạng thái tài khoản không hợp lệ.',
    ]);
        $account = new Accounts();
        $account->account_username = $request->account_username;
        $account->account_password = bcrypt($request->account_password);
        $account->account_role = 'employee';
        $account->account_active = $request->account_active;
        $account->save();

        
            $employee = Employees::find($request->user_id);
            $employee->account_id = $account->account_id;
            $employee->save();
        
        Session::flash('alert-info', 'Thêm mới thành công ^^~!!!');
        return redirect()->route('admin.account.index');
    }

    public function edit(Request $request)
    {
        $account = Accounts::find($request->account_id);

        return view('admin.account.edit')
            ->with('account', $account);
            
    }

    public function update(Request $request)
    {
        $request->validate([
            'account_username' => 'required|string',
            
            'account_active' => 'required|in:active,locked',
        ], [
            'account_username.required' => 'Tên tài khoản không được để trống.',
            
            'account_active.required' => 'Trạng thái tài khoản không được để trống.',
            'account_active.in' => 'Trạng thái tài khoản không hợp lệ.',
        ]);

        $account = Accounts::find($request->account_id);
        $account->account_username = $request->account_username;
        //$account->account_role = $request->account_role;
        $account->account_active = $request->account_active;
        $account->save();

        
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

    public function exportExcel() 
    {
        return Excel::download(new AccountsExport, 'accounts.xlsx');
    }
}