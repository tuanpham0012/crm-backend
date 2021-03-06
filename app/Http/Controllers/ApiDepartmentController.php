<?php

namespace App\Http\Controllers;

use App\User;
use App\Helper\Helper;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\StaffOfDepartment;

class ApiDepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = Department::with(['StaffOfDepartment.User', 'StaffOfDepartment.Position'])->get();
        
        return response()->json($departments, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $department = new Department();
        $department->fill($request->all());
        $department->save();
        return response()->json(['message' => 'Tạo mới thành công!', 'department' => $department], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $staffs = StaffOfDepartment::with(['User', 'Departments', 'Position'])->where('department_id', $id)->orderBy('position_id')->get();
        $user = User::with(['StaffOfDepartment.departments', 'StaffOfDepartment.position'])
                ->whereDoesntHave('StaffOfDepartment', function($q) use($id){
                    return $q->where('department_id', $id);
                })->orderBy('name', 'ASC')->get();
        $department = Department::find($id);

        return response()->json(['staffs' => $staffs, 'department' => $department, 'all_user' => $user], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $department = Department::find($id)->update($request->department);

        StaffOfDepartment::where('department_id', $id)->update(['position_id' => 3]);

        StaffOfDepartment::where('department_id', $id)->where('user_id', $request->manager_id)->update(['position_id' => 1]);
        StaffOfDepartment::where('department_id', $id)->where('user_id', $request->deputy_id)->update(['position_id' => 2]);

        return response()->json(['message' => 'Cập nhật thành công!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function add_staff(Request $request){
        $check = StaffOfDepartment::where('user_id', $request->user_id)->first();
        if($check){
            $check->department_id = $request->department_id;
            $check->position_id = $request->position_id;
            $check->save();
        }else{
            $department = new StaffOfDepartment();
            $department->fill($request->all());
            $department->save();
        }
        $department = Department::find($request->department_id);
        
        $title = 'Phòng ban';
        $content = "<span class='span-name'>".$request->user('api')->name ."</span> thêm bạn vào <span class='span-name'>". $department->name ."</span>"; 
        $relation = "department";
        $relation_id = $request->department_id;
    
        Helper::CreateNotification($title, $content, $request->user_id, $relation, $relation_id);

        return response()->json(['message'=> 'Thêm thành công!','a' => $check], 200);
    }
    public function update_position(Request $request, $id){
        $position = StaffOfDepartment::find($id);
        if($position){
            if($request->position_id == 1 || $request->position_id == 2){
                $up = StaffOfDepartment::where('department_id', $position->department_id)->where('position_id', $request->position_id)->first();
                if($up){
                    $up->position_id = 3;
                    $up->save();
                }
            }
            $position->position_id = $request->position_id;
            $position->save();
            return response()->json(['message' => 'Cập nhật thành công'], 200);
        }else{
            return response()->json(['message' => 'Không tìm thấy thông tin'], 404);
        }
        
    }
    public function remove_staff($id){
        $position = StaffOfDepartment::find($id);
        $position->department_id = 0;
        $position->position_id = 0;
        $position->save();
        return response()->json(['message' => 'Xóa thành công'], 200);
    }

}
