<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Facilities;

class FacilityController extends Controller
{
    public function index()
    {
        $facilities = Facilities::all();
        return view('admin/facility/index')
            ->with('facilities', $facilities);
    }

    public function create()
    {
        return view('admin/facility/create');
    }

    public function save(Request $request)
    {
        $facility = new Facilities();
        $facility->facility_name = $request->facility_name;
        $facility->facility_description = $request->facility_description;
        $facility->save();
        Session::flash('alert-info', 'Thêm mới thành công ^^~!!!');
        return redirect()->route('admin.facility.index');
    }

    public function edit(Request $request)
    {
        $facility = Facilities::find($request->facility_id);
        return view('admin/facility/edit')
        ->with('facility', $facility);
    }

    public function update(Request $request)
    {
        $facility = Facilities::find($request->facility_id);
        $facility->facility_name = $request->facility_name;
        $facility->facility_description = $request->facility_description;
        $facility->save();
        Session::flash('alert-info', 'Cập nhật thành công ^^~!!!');
        return redirect()->route('admin.facility.index');
    }
    
    public function destroy(Request $request)
    {
        $request->validate([
            'facility_id' => 'required|integer|exists:facilities,facility_id',
        ]);
        
        $facility = Facilities::find($request->facility_id);
        $facility->destroy($request->facility_id);
        Session::flash('alert-info', 'Xóa thành công ^^~!!!');
        return redirect()->route('admin.facility.index');
    }
}