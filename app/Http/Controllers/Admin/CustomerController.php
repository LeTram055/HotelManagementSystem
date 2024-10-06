<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Customers;

class CustomerController extends Controller
{
    public function index() {
        $customers = Customers::all();
        return view('admin.customer.index')
        ->with('customers', $customers);
    }
    
    public function create()
    {
        return view('admin/customer/create');
    }

    public function save(Request $request)
    {
        $request->validate([
            'customer_cccd' => 'required|string|regex:/^[0-9]{12}$/'
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
            'customer_cccd' => 'required|string|regex:/^[0-9]{12}$/'
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

}