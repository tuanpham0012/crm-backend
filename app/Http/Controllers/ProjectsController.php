<?php

namespace App\Http\Controllers;

use App\User;
use DateTime;
use App\Helper\Helper;
use App\Models\Projects;
use App\Models\Department;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProjectParticipant;

class ProjectsController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $date = new DateTime;
        Projects::where('start_at', '<=', $date)->where( 'end_at', '>=', $date)->whereNotIn('project_status_id', [3, 4])->update(['project_status_id' => 2]);
        Projects::where( 'end_at', '<', $date)->whereNotIn('project_status_id', [3, 4])->update(['project_status_id' => 3]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $pageSize = 20;
        if($request->user('api')->role_id == 1){
            $projects = Projects::with(['User:id,name', 'ProjectStatus', 'Tasks', 'ProjectParticipants.User'])->where('name', 'like', '%'.$request->search.'%')->where(function($q) use($request){
                if($request->status && $request->status != -1) $q->where('project_status_id', $request->status);
            })
            ->latest()->paginate($pageSize);
        }else{
            $projects = Projects::with(['User:id,name', 'ProjectStatus', 'Tasks', 'ProjectParticipants.User'])->where('name', 'like', '%'.$request->search.'%')->where(function($q) use($request){
                if($request->status && $request->status != -1) $q->where('project_status_id', $request->status);
            })->whereHas('ProjectParticipants', function($q) use($request){
                return $q->where('user_id', $request->user('api')->id)->where('accept', 1);
            })
            ->latest()->paginate($pageSize);
        }

        $list_project = Projects::with(['User:id,name', 'ProjectStatus', 'Tasks', 'ProjectParticipants.User'])
                ->where('name', 'like', '%'.$request->search.'%')
                ->whereIn('project_status_id', [1,2])->whereDoesntHave('ProjectParticipants', function($q) use($request){
                        return $q->where('user_id', $request->user('api')->id);
                    })->orWhereHas('ProjectParticipants', function($q) use($request){
                        return $q->where('user_id', $request->user('api')->id)->where('accept', 0);
                    })
                    ->get();
        
        return response()->json(['projects' => $projects, 'list_join' => $list_project], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $project = new Projects();
        $project->fill($request->project);
        $project->project_code = Str::random(2).Str::random(2).Str::random(2);
        $project->user_id = $request->user('api')->id;
        $project->project_status_id = 1;
        $project->save();

        foreach($request->users as $user){
            $participant = new ProjectParticipant();
            $participant->project_id = $project->id;
            $participant->user_id = $user['id'];
            $participant->accept = 1;
            $participant->save();

            $title =  'Tạo mới dự án';
            $content = "<span class='span-name'".$request->user('api')->name .'</span> đã thêm bạn vào dự án mới';

            Helper::CreateNotification($title, $content, $user['id'], 'project', $project->id);
        }

        return response()->json(['message' => 'Tạo mới thành công', 'project' => $project], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Projects  $projects
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $project = Projects::with(['User:id,name', 'ProjectStatus', 'Tasks.User:id,name', 'Tasks.TypeOfTask', 'Tasks.TaskStatus', 'ProjectParticipants.User'])
        ->find($id);


        $id_user = ProjectParticipant::select('user_id')->where('project_id', $project->id)->get();

        $id_u = array();

        foreach($id_user as $id){
            array_push($id_u, $id->user_id);
        }

        $staffs = Department::with(['StaffOfDepartment' => function($q) use ($id_u){
            return $q->whereIn('user_id', $id_u);}
            ,'StaffOfDepartment.User'])->get();
        if($project){

            return response()->json(['project' => $project, 'user' => $staffs], 200, );
        }else{
            return response()->json(['message' => 'Không tìm thấy thông tin!'], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Projects  $projects
     * @return \Illuminate\Http\Response
     */
    public function edit(Projects $projects)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Projects  $projects
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $project = Projects::find($id)->update($request->all());
        if($project){
            return response()->json(['message' => 'Cập nhật thành công'], 200);
        }else{
            return response()->json(['message' => 'Cập nhật thất bại! Có lỗi xảy ra'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Projects  $projects
     * @return \Illuminate\Http\Response
     */
    public function destroy(Projects $projects)
    {
        //
    }

    public function add_participant(Request $request){
        foreach ($request->users as $user) {
            $u = User::select('id')->find($user['id']);
            if($u){
                $participant = ProjectParticipant::where('project_id', $request->project_id)->where('user_id', $u->id)->first();
                if($participant){
                    $participant->accept = 1;
                    $participant->save();
                }else{
                    $participant = new ProjectParticipant();
                    $participant->project_id = $request->project_id;
                    $participant->user_id = $u->id;
                    $participant->accept = 1;
                    $participant->save();

                    $title =  'Dự án';
                    $content = "<span class='span-name'>". $request->user('api')->name .'</span> đã thêm bạn vào dự án';
                    Helper::CreateNotification($title, $content, $user['id'], 'project', $request->project_id);
                }
            }
        }
        return response()->json(['message' => 'Thêm thành công!'], 200);
    }

    public function confirm_participant(Request $request){
        $participant = ProjectParticipant::find($request->id);
        if($participant){
            $participant->accept = 1;
            $participant->save();
            $title =  'Dự án';
            $content = "<span class='span-name'>".$request->user('api')->name .'</span> đã xác nhận bạn vào dự án';

            Helper::CreateNotification($title, $content, $participant->user_id, 'project', $participant->project_id);
            return response()->json(['message' => 'Cập nhật thành công'], 200);
        }else{
            return response()->json(['message' => 'Không tìm thấy thông tin'], 404);
        }
    }

    public function delete_participant(Request $request){
        $participant = ProjectParticipant::with(['Project:id,name'])->find($request->id);
        if($participant){
            $user_id = $participant->user_id;
            $participant->delete();

            $title =  'Dự án';
            $content = "<span class='span-name'>".$request->user('api')->name ."</span> đã xóa bạn khỏi dự án <span class='span_name'>". $participant->project->name . "</span>f";

            Helper::CreateNotification($title, $content, $user_id, '', -1);
            return response()->json(['message' => 'Cập nhật thành công'], 200);
        }else{
            return response()->json(['message' => 'Không tìm thấy thông tin'], 404);
        }
    }

    public function join_project(Request $request){
        $participant = ProjectParticipant::where('project_id', $request->project_id)->where('user_id', $request->user('api')->id)->first();
        if(!isset($participant)){
            $join = new ProjectParticipant();
            $join->project_id = $request->project_id;
            $join->user_id = $request->user('api')->id;
            $join->accept = 0;
            $join->save();

            return response()->json(['message' => 'Xin thành công!'], 200);
        }else{
            return response()->json(['message' => 'Có lỗi xảy ra!'], 404);
        }
    }

}
