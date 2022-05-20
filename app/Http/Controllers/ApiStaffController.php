<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\StaffOfDepartment;

class ApiStaffController extends Controller
{
    public function getStaffs(){
        $user = User::with(['StaffOfDepartment.Departments',])->latest()->paginate(10);
        return response()->json($user, 200);
    }
    public function getStaffForDepartment(Request $request){
        $staffs = StaffOfDepartment::with(['departments','user'])->where('department_id', $request->id)->get();
        return response()->json($staffs, 200);
    }
}
