<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerNotes;

class ApiCustomerNotesController extends Controller
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
        $note = CustomerNotes::create($request->all());
        return response()->json(['message' => 'Thêm ghi chú khách hàng thành công!', 'customer_id' => $note->customer_id], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $note = CustomerNotes::find($id)->update($request->all());
        $customer_id = CustomerNotes::find($id);
        if($note){
            return response()->json(['message' => 'Chỉnh sửa thành công!', 'customer_id' => $customer_id->id], 200);
        }else{
            return response()->json(['message' => 'Không tìm thấy bản ghi!'], 404);
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
            $note = CustomerNotes::find($id)->delete();
            if($note){
                return response()->json(['message' => 'Xóa thành công!'], 200);
            }else{
                return response()->json(['message' => 'Không tìm thấy bản ghi!'], 404);
            }
    }
}
