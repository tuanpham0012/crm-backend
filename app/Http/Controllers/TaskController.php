<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Task;
use App\Helper\Helper;
use App\Models\Customer;
use App\Models\TaskUser;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
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
        $task = new Task();
        $task->fill($request->task);
        $task->user_id = $request->user('api')->id;
        $task->task_status_id = 1;
        $task->save();

        Helper::CreateNoteOfTask($task->id, $request->user('api')->id, 'Tạo mới công việc');

        foreach ( $request->users as $user){
            $task_user = new TaskUser();
            $task_user->task_id = $task->id;
            $task_user->user_id = $user['id'];
            if($user['id'] == $request->user('api')->id){
                $task_user->accept = 1;
            }
            $task_user->save();

            $title =  'Tạo mới công việc';
            $content = $request->user('api')->name .' đã thêm bạn vào công việc';

            Helper::CreateNotification($title, $content, $user['id'], 'task', $task->id);
        }
    
        return response()->json([ 'message' => 'Tạo công việc thành công!','task' => $task], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        $task = $task->load(['User.StaffOfDepartment.Departments', 'Customer', 'TaskStatus', 'TypeOfTask', 'TaskUser.User.StaffOfDepartment.Departments', 'NoteOfTask.User', 'Project:id,name']);
        return response()->json($task, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $task->fill($request->all())->update();

        $task_user = TaskUser::where('task_id', $task->id)->get();
        $title = $request->user('api')->name.": Cập nhật công việc";
        $content = $request->user('api')->name. " đã cập nhật thời gian công việc";
        foreach( $task_user as $user){
            if($user->user_id != $request->user('api')->id)
                Helper::CreateNotification($title, $content, $user->user_id, 'task', $task->id);
        }

        return response()->json(['message' => 'Cập nhật thành công!','task' => $task_user], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        //
    }

    public function get_my_task(Request $request){
        $date = new DateTime;
        $id = $request->user('api')->id;

        Task::where('end', '<', $date)->whereNotIn('task_status_id', [4, 5, 6])->update(['task_status_id' => 3]);
        Task::where('start', '<=', $date)->where( 'end', '>=', $date)->whereNotIn('task_status_id', [4, 5, 6])->update(['task_status_id' => 2]);
        $new = Task::with(['User', 'TypeOfTask', 'TaskUser', 'TaskUser.User', 'TaskStatus'])
            ->where('task_status_id', '=', 1)->where('name', 'like', '%'.$request->search.'%')
            ->whereHas('TaskUser', function($q) use($id){
                    return $q->where('user_id', $id);})->latest()->get();
        $progress = Task::with(['User', 'TypeOfTask', 'TaskUser', 'TaskUser.User', 'TaskStatus'])
                    ->whereIn('task_status_id', [2, 3])->where('name', 'like', '%'.$request->search.'%')
                    ->whereHas('TaskUser', 
                        function($q) use ($id){
                            return $q->where('user_id', $id);
                    })->latest()->get();
        $late = Task::with(['User', 'TypeOfTask', 'TaskUser', 'TaskUser.User', 'TaskStatus'])
            ->where('task_status_id', '=', 3)->where('name', 'like', '%'.$request->search.'%')
            ->whereHas('TaskUser', 
                function($q) use ($id){
                    return $q->where('user_id', $id);
                    })->latest()->get();
        $finish = Task::with(['User', 'TypeOfTask', 'TaskUser', 'TaskUser.User', 'TaskStatus'])
            ->whereIn('task_status_id', [4, 5])->where('name', 'like', '%'.$request->search.'%')
            ->whereHas('TaskUser',
                function($q) use ($id){
                    return $q->where('user_id', $id);
                    })->latest()->get();
        $creater = Task::with(['User', 'TypeOfTask', 'TaskUser', 'TaskUser.User', 'TaskStatus'])
            ->where('user_id', $id)->where('name', 'like', '%'.$request->search.'%')
            ->latest()->get();
        $cancelled = Task::with(['User', 'TypeOfTask', 'TaskUser', 'TaskUser.User', 'TaskStatus'])
            ->where('task_status_id', 6)->where('name', 'like', '%'.$request->search.'%')
            ->whereHas('TaskUser',
                function($q) use ($id){
                    return $q->where('user_id', $id);
                    })->latest()->get();

        return response()->json([ 
            'progress' => $progress, 
            'late' => $late, 
            'new_task' => $new, 
            'creater' => $creater,
            'finish' => $finish,
            'cancelled' => $cancelled,
        ], 200);
    }
    public function accept_task(Request $request, $id){
        $task = TaskUser::where('task_id', $id)->where('user_id', $request->user('api')->id)->first();
        $task->accept = 1;
        $task->save();
        return response()->json(['id' => $id, 'message' => 'Nhận việc thành công!'], 200);
    }
    public function add_staff(Request $request){
        $creater = $request->user('api');

        $title = $request->user('api')->name.": Cập nhật công việc";
        $content = $request->user('api')->name. " đã thêm bạn vào công việc";

        foreach($request->users as $user){
            $check = TaskUser::where('task_id', $request->id)->where('user_id', $user['id'])->first();
            if(!isset($check)){

                $task = new TaskUser();
                $task->task_id = $request->id;
                $task->user_id = $user['id'];
                if($user['id'] == $creater->id){
                    $task->accept = 1;
                }
                $task->save();

                if($user['id'] != $request->user('api')->id){
                    Helper::CreateNotification($title, $content, $user['id'], 'task', $request->id);
                }
                Helper::CreateNoteOfTask($request->id, $creater->id, 'Thêm <span style="font-size:1.1rem;font-weight:bold;">'. $user['name'].'</span> vào công việc!');
            }
        }

        return response()->json(['id' => $request->id, 'message' => 'Thêm người thực hiện thành công!'], 200);
    }
    public function remove_staff(Request $request){
        $task = TaskUser::where('task_id', $request->task_id)->where('user_id', $request->staff_id)->first();
        if($task){

            $title = $request->user('api')->name.": Cập nhật công việc";
            $content = $request->user('api')->name. " đã xóa bạn vào công việc";
    
            if($request->staff_id != $request->user('api')->id){
                Helper::CreateNotification($title, $content, $request->staff_id, 'tasks', $task->id);
            }

            $task->delete();
            Helper::CreateNoteOfTask($request->task_id, $request->user('api')->id, 'Xóa <span style="font-size:1.1rem;font-weight:bold;">'. $request->staff_name .'</span> khỏi công việc!');
            return response()->json(['id' => $request->task_id, 'message' => 'Xóa thành công!'], 200);
        }else{
            return response()->json(['message' => 'Không tìm thấy dữ liệu!'], 404);
        }
    }
    public function update_task_status(Request $request){
        $date = new DateTime;
        $late = new DateTime;
        $task = Task::find($request->task_id);
        if($task){
            
            if($request->status_id == 4 || $request->status_id == 5){
                if($task->task_status_id == 3){
                    $task->task_status_id = 5;
                }else{
                    $task->task_status_id = 4;
                }
                $task->save();
                Helper::CreateNoteOfTask($request->task_id, $request->user('api')->id, 'Xác nhận hoàn thành công việc!');

            }else if( $request->status_id == 7){
                if( $task->end < $late){
                    $task->task_status_id = 3;
                }else if($task->start > $date){
                    $task->task_status_id = 1;
                }else{
                    $task->task_status_id = 2;
                }
                $task->save();
                Helper::CreateNoteOfTask($request->task_id, $request->user('api')->id, 'Phục hồi công việc!');
            }
            else{
                if($request->status_id == 6){
                    Helper::CreateNoteOfTask($request->task_id, $request->user('api')->id, 'Xác nhận hủy công việc!');
                }
                $task->task_status_id = $request->status_id;
                $task->save();
            }

            $task_user = TaskUser::where('task_id', $task->id)->get();
            $title = $request->user('api')->name.": Cập nhật công việc";
            $content = $request->user('api')->name. " đã cập nhật tiến độ công việc";
            foreach( $task_user as $user){
                if($user->user_id != $request->user('api')->id)
                    Helper::CreateNotification($title, $content, $user->user_id, 'task', $task->id);
            }
            return response()->json(['id' => $request->task_id, 'message' => 'Cập nhật thành công!'], 200);
        }else{
            return response()->json(['message' => 'Không tìm thấy bản ghi!'], 404);
        }
    }
    public function update_task_name(Request $request){
        $task = Task::find($request->task_id);
        if(isset($task)){
            $old = $task->name;
            $task->name = $request->name;
            $task->save();
            $note = 'Cập nhật tên công việc '.$old.' -> '.$request->name.'!';
            Helper::CreateNoteOfTask($request->task_id, $request->user('api')->id, $note);

            $task_user = TaskUser::where('task_id', $task->id)->get();
            $title = $request->user('api')->name.": Cập nhật công việc";
            $content = $request->user('api')->name. " đã cập nhật tên công việc";
            foreach(  $task_user as $user){
                if($user->user_id != $request->user('api')->id){
                    Helper::CreateNotification($title, $content, $user->user_id, 'task', $task->id);
                }
            }

            return response()->json(['id' => $task->id, 'message' => 'Cập nhật tên công việc thành công!', 'tasks' => $task], 200);
        }else{
            return response()->json(['message' => 'Không tìm thấy bản ghi!'], 404);
        }
        
    }
    public function update_task_content(Request $request){
        $task = Task::find($request->task_id);
        if(isset($task)){
            $task->content = $request->task_content;
            $task->save();
            return response()->json(['task_id' => $task->id, 'message' => 'Cập nhật nội dung công việc thành công!'], 200);
        }else{
            return response()->json(['message' => 'Không tìm thấy bản ghi!'], 404);
        }
        
    }
    public function update_customer(Request $request){
        $task = Task::find($request->task_id);
        if(isset($task)){
            if($request->customer_id == -1){
                $task->customer_id = null;
                $task->save();
                $note = 'Xóa khách hàng khỏi công việc.';
                Helper::CreateNoteOfTask($request->task_id, $request->user('api')->id, $note);
            }else{
                $customer = Customer::find($request->customer_id);
                if($customer){
                    $task->customer_id = $request->customer_id;
                    $task->save();
                    $note = 'Cập nhật khách hàng <span style="font-size:1.05rem;font-weight:bold;">'.$customer->name .'</span> vào công việc.';
                    Helper::CreateNoteOfTask($request->task_id, $request->user('api')->id, $note);
                }else{
                    return response()->json(['message' => 'Có lỗi xảy ra! Không tìm thấy khách hàng phù hợp.'], 404);
                }
            }

            $task_user = TaskUser::where('task_id', $task->id)->get();
            $title = $request->user('api')->name.": Cập nhật công việc";
            $content = $request->user('api')->name. " đã cập nhật khách hàng trong công việc";
            foreach( $task_user as $user){
                if($user->user_id != $request->user('api')->id)
                    Helper::CreateNotification($title, $content, $user->user_id, 'task', $task->id);
            }

            return response()->json(['task_id' => $task->id, 'message' => 'Success!'], 200);
        }else{
            return response()->json(['message' => 'Không tìm thấy bản ghi!'], 404);
        }
    }
}
