<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Employees;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EmployeesExport;

class EmployeeController extends Controller
{
    public function index(Request $request) {
        $sortField = $request->input('sort_field', 'employee_id'); // Mặc định sắp xếp theo employee_id
        $sortDirection = $request->input('sort_direction', 'asc'); // Mặc định sắp xếp tăng dần

        $query = Employees::query();
        // Kiểm tra nếu có input tìm kiếm
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            
            $query->where('employee_id', 'like', '%' . $searchTerm . '%')
                ->orWhere('employee_name', 'like', '%' . $searchTerm . '%')
                ->orWhere('employee_phone', 'like', '%' . $searchTerm . '%')
                ->orWhere('employee_email', 'like', '%' . $searchTerm . '%')
                ->orWhere('employee_address', 'like', '%' . $searchTerm . '%');
        }

        if (in_array($sortField, ['employee_name', 'employee_phone','employee_email','employee_address'])) {
           $query->orderByRaw("CONVERT($sortField USING utf8) COLLATE utf8_unicode_ci $sortDirection");
        } elseif ($sortField == 'employee_id') {
            // Sắp xếp giá theo kiểu số
            $query->orderByRaw("CAST($sortField AS DECIMAL) $sortDirection");
        } 
        else {
            $query->orderByRaw("CONVERT(employee_id USING utf8) COLLATE utf8_unicode_ci asc");
        }

        $employees = $query->get();
        return view('admin.employee.index')
        ->with('employees', $employees)
        ->with('sortField', $sortField)
        ->with('sortDirection', $sortDirection);
    }
    
    
    public function create()
    {
        return view('admin/employee/create');
    }

    public function save(Request $request)
    {
        $request->validate([
            'employee_name' => 'required|string',
            'employee_phone' => 'required|string|regex:/^(09|08|07|05|03)[0-9]{8}$/',
            'employee_email' => 'required|email',
            'employee_address' => 'required|string',
            
        ], [
            'employee_phone.regex' => 'Số điện thoại không hợp lệ',
            'employee_email.email' => 'Email không hợp lệ',
            'employee_address.required' => 'Địa chỉ không được để trống',
            'employee_name.required' => 'Tên nhân viên không được để trống',
            'employee_phone.required' => 'Số điện thoại không được để trống',
            'employee_email.required' => 'Email không được để trống',
            
        ]);
        $employee = new Employees();
        $employee->employee_name = $request->employee_name;
        $employee->employee_phone= $request->employee_phone;
        $employee->employee_email= $request->employee_email;
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
            'employee_name' => 'required|string',
            'employee_phone' => 'required|string|regex:/^(09|08|07|05|03)[0-9]{8}$/',
            'employee_email' => 'required|email',
            'employee_address' => 'required|string',
            
        ], [
            'employee_phone.regex' => 'Số điện thoại không hợp lệ',
            'employee_email.email' => 'Email không hợp lệ',
            'employee_address.required' => 'Địa chỉ không được để trống',
            'employee_name.required' => 'Tên nhân viên không được để trống',
            'employee_phone.required' => 'Số điện thoại không được để trống',
            'employee_email.required' => 'Email không được để trống',
            
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

    public function exportExcel() 
    {
        return Excel::download(new EmployeesExport, 'employees.xlsx');
    }

}