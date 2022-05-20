<?php

namespace App\Http\Controllers;

use App\User;
use StaffDepartment;
use App\Helper\Helper;
use App\Models\Department;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\StaffOfDepartment;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pageSize = 20;
        $user = User::with(['StaffOfDepartment.Departments',])
        ->where(function($q) use($request){
            $q->where('name' ,'like', '%'.$request->search.'%')
            ->orWhere('email' ,'like', '%'.$request->search.'%')
            ->orWhere('phone' ,'like', '%'.$request->search.'%');
        })->whereHas('StaffOfDepartment', function($q) use($request){
            if($request->department_id != -1) $q->where('department_id', $request->department_id);
        })
        ->latest()->paginate($pageSize);
        return response()->json(['staffs' => $user], 200);

        // ->where(function($q) use($request){
        //     if($request->delete != -1) $q->where('deleted', $request->delete);
        // })
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
        $validated = $request->validate([
            'email' => 'required|email|unique:users',
            'phone' => 'required|unique:users',
        ]);

        $user = new User();
        $user->fill($request->all());
        $user->employee_code = Str::orderedUuid();
        $user->password = Hash::make('admin123');
        $user->role_id = 3;
        $user->avatar = Helper::randImage();
        $user->save();

        $department_staff = new StaffOfDepartment();
        $department_staff->department_id = $request->department_id;
        $department_staff->user_id = $user->id;
        $department_staff->position_id = $request->position_id;
        $department_staff->save();
        return response()->json(['message' => 'Thêm mới nhân viên thành công!', 'data' => $user], 200);
 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::with(['Role', 'StaffOfDepartment.departments', 'StaffOfDepartment.position'])->find($id);
        if($user){
            return response()->json($user, 200);
        }else{
            return response()->json(['message' => 'Không tìm thấy thông tin!'], 404);
        }
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
        $staff = User::find($id)->update($request->all());
        if($staff){
            return response()->json(['message' => 'Cập nhật thông tin thành công!', 'user' => $staff], 200);
        }else{
            return response()->json(['message' => 'Không tìm thấy thông tin!'], 404);
        }
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

    public function remove_multiple(Request $request){
        foreach ($request->users as $user_id) {
            $user = User::find($user_id);
            if($user){
                $user->deleted = 1;
                $user->save();
            }
        }
        return response()->json(['message' => 'Xóa thành công!'], 200);
    }

    public function update_department(Request $request){

        $department_name = Department::select('name')->where('id', $request->department_id)->first();
        foreach ($request->users as $user_id) {
            $user = StaffOfDepartment::where('user_id',$user_id)->first();
            if($user){
                $user->department_id = $request->department_id;
                $user->save();
            }
            $title = $request->user('api')->name.": Cập nhật phòng ban";
            $content = $request->user('api')->name. " đã thêm bạn vào <span class='span-name'>". $department_name ."</span>";

            Helper::CreateNotification($title, $content, $user_id, 'department', $request->department_id);

        }
        return response()->json(['message' => 'Cập nhật thành công!'], 200);
    }
}
