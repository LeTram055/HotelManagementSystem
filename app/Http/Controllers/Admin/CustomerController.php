<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Customers;

use App\Exports\CustomersExport;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{   
    
    public function index(Request $request)
    {
        $sortField = $request->input('sort_field', 'customer_id'); // Mặc định sắp xếp theo customer_id
        $sortDirection = $request->input('sort_direction', 'asc'); // Mặc định sắp xếp tăng dần

        $query = Customers::query();
        // Kiểm tra nếu có input tìm kiếm
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            // Tìm kiếm theo tên, CCCD, email, địa chỉ
            $query->where('customer_id', 'like', '%' . $searchTerm . '%')
                ->orWhere('customer_name', 'like', '%' . $searchTerm . '%')
                ->orWhere('customer_cccd', 'like', '%' . $searchTerm . '%')
                ->orWhere('customer_email', 'like', '%' . $searchTerm . '%')
                ->orWhere('customer_address', 'like', '%' . $searchTerm . '%');
        }

        if (in_array($sortField, ['customer_name', 'customer_cccd','customer_email','customer_address'])) {
           $query->orderByRaw("CONVERT($sortField USING utf8) COLLATE utf8_unicode_ci $sortDirection");
        } elseif ($sortField == 'customer_id') {
            // Sắp xếp giá theo kiểu số
            $query->orderByRaw("CAST($sortField AS DECIMAL) $sortDirection");
        } 
        else {
            $query->orderByRaw("CONVERT(customer_id USING utf8) COLLATE utf8_unicode_ci asc");
        }

        $customers = $query->get();
        return view('admin.customer.index')
        ->with('customers', $customers)
        ->with('sortField', $sortField)
        ->with('sortDirection', $sortDirection);
    }
    
    public function create()
    {
        return view('admin/customer/create');
    }

    public function save(Request $request)
    {
        $request->validate([
            'customer_cccd' => 'required|string|regex:/^(09|08|07|05|03)[0-9]{8}$/',
            'customer_email' => 'required|email',
            'customer_address' => 'required|string',
            'customer_name' => 'required|string',
            
        ], [
        'customer_cccd.required' => 'CCCD không được để trống.',
        'customer_cccd.string' => 'CCCD phải là chuỗi ký tự.',
        'customer_cccd.regex' => 'CCCD phải có 12 chữ số.',
        'customer_email.required' => 'Email không được để trống.',
        'customer_email.email' => 'Email không hợp lệ.',
        'customer_address.required' => 'Địa chỉ không được để trống.',
        'customer_name.required' => 'Tên không được để trống.',
        'customer_name.string' => 'Tên phải là chuỗi ký tự.',
        
    ]);
        $customer = new Customers();
        $customer->customer_name = $request->customer_name;
        $customer->customer_cccd= $request->customer_cccd;
        $customer->customer_email = $request->customer_email;
        $customer->customer_address= $request->customer_address;
        $customer->save();
        Session::flash('alert-info', 'Thêm mới thành công ^^~!!!');
        return redirect()->route('admin.customer.index');
    }

    public function edit(Request $request)
    {
        $customer = Customers::find($request->customer_id);
        return view('admin/customer/edit')
        ->with('customer', $customer);
    }

    public function update(Request $request)
    {
        $request->validate([
            'customer_cccd' => 'required|string|regex:/^(09|08|07|05|03)[0-9]{8}$/',
            'customer_email' => 'required|email',
            'customer_address' => 'required|string',
            'customer_name' => 'required|string',
            
        ], [
        'customer_cccd.required' => 'CCCD không được để trống.',
        'customer_cccd.string' => 'CCCD phải là chuỗi ký tự.',
        'customer_cccd.regex' => 'CCCD phải có 12 chữ số.',
        'customer_email.required' => 'Email không được để trống.',
        'customer_email.email' => 'Email không hợp lệ.',
        'customer_address.required' => 'Địa chỉ không được để trống.',
        'customer_name.required' => 'Tên không được để trống.',
        'customer_name.string' => 'Tên phải là chuỗi ký tự.',
        
    ]);
        
        $customer = Customers::find($request->customer_id);
        $customer->customer_name = $request->customer_name;
        $customer->customer_cccd = $request->customer_cccd;
        $customer->customer_email = $request->customer_email;
        $customer->customer_address= $request->customer_address;
        $customer->save();
        Session::flash('alert-info', 'Cập nhật thành công ^^~!!!');
        return redirect()->route('admin.customer.index');
    }
    
    public function destroy(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|integer|exists:customers,customer_id',
        ]);
        
        $customer = Customers::find($request->customer_id);
        $customer->destroy($request->customer_id);
        Session::flash('alert-info', 'Xóa thành công ^^~!!!');
        return redirect()->route('admin.customer.index');
    }

    public function exportExcel()
{
    return Excel::download(new CustomersExport, 'customers.xlsx');
}

}