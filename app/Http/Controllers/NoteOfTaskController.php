<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\NoteOfTask;
use Illuminate\Http\Request;

class NoteOfTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $creater_id = $request->user('api')->id;
        $note = Helper::CreateNoteOfTask($request->task_id, $creater_id, $request->content);
        return response()->json(['message' => 'Success!', 'res' => $note], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\NoteOfTask  $noteOfTask
     * @return \Illuminate\Http\Response
     */
    public function show(NoteOfTask $noteOfTask)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\NoteOfTask  $noteOfTask
     * @return \Illuminate\Http\Response
     */
    public function edit(NoteOfTask $noteOfTask)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\NoteOfTask  $noteOfTask
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $note = NoteOfTask::find($id);
        $note->content = $request->content;
        $note->save();
        return response()->json(['message' => 'Chỉnh sửa thành công!', 'note' => $note], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NoteOfTask  $noteOfTask
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $note = NoteOfTask::find($id)->delete();
        if($note){
            return response()->json(['message' => 'Xóa thành công!'], 200);
        }else{
            return response()->json(['message' => 'Không tìm thấy bản ghi!'], 404);
        }
        
    }
}
