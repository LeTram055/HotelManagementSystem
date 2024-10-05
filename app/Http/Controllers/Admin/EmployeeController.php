<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Employees;

class EmployeeController extends Controller
{
    public function index() {
        $employees = Employees::all();
        return view('admin.employee.index')
        ->with('employees', $employees);
    }
    
    public function create()
    {
        return view('admin/employee/create');
    }

    public function save(Request $request)
    {
        $request->validate([
            'employee_phone' => 'required|string|regex:/^[0][0-9]{9}$/'
        ]);
        $employee = new Employees();
        $employee->employee_name = $request->employee_name;
        $employee->employee_phone= $request->employee_phone;
        $employee->employee_address= $request->employee_address;
        $employee->employee_status = $request->employee_status;
        $employee->save();
        Session::flash('alert-info', 'Thêm mới thành công ^^~!!!');
        return redirect()->route('admin.employee.index');
    }

    public function edit(Request $request)
    {
        $employee = Employees::find($request->employee_id);
        return view('admin/employee/edit')
        ->with('employee', $employee);
    }

    public function update(Request $request)
    {
        $request->validate([
            'employee_phone' => 'required|string|regex:/^[0][0-9]{9}$/'
        ]);
        
        $employee = Employees::find($request->employee_id);
        $employee->employee_name = $request->employee_name;
        $employee->employee_phone = $request->employee_phone;
        $employee->employee_address= $request->employee_address;
        $employee->employee_status = $request->employee_status;
        $employee->save();
        Session::flash('alert-info', 'Cập nhật thành công ^^~!!!');
        return redirect()->route('admin.employee.index');
    }
    
    public function destroy(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|integer|exists:employees,employee_id',
        ]);
        
        $employee = Employees::find($request->employee_id);
        $employee->destroy($request->employee_id);
        Session::flash('alert-info', 'Xóa thành công ^^~!!!');
        return redirect()->route('admin.employee.index');
    }

}