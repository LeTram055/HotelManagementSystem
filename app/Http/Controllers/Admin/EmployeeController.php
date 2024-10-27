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
            
            $query->where('employee_name', 'like', '%' . $searchTerm . '%')
                ->orWhere('employee_phone', 'like', '%' . $searchTerm . '%')
                ->orWhere('employee_email', 'like', '%' . $searchTerm . '%')
                ->orWhere('employee_address', 'like', '%' . $searchTerm . '%');
        }
        $employees = $query->orderBy($sortField, $sortDirection)->get();
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

    public function exportExcel() 
    {
        return Excel::download(new EmployeesExport, 'employees.xlsx');
    }

}