<?php

namespace App\Http\Controllers;

use App\Models\CustomerReports;
use Illuminate\Http\Request;

class CustomerReportsController extends Controller
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
        $care = new CustomerReports();
        $care->fill($request->all());
        $care->user_id = $request->user('api')->id;
        $care->save();

        return response()->json(['message' => 'Tạo thành công!','care' => $care], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CustomerReports  $customerReports
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerReports $customerReports)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CustomerReports  $customerReports
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerReports $customerReports)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CustomerReports  $customerReports
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $care = CustomerReports::find($id)->update($request->all());
        return response()->json(['message' => 'Cập nhật thành công!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomerReports  $customerReports
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerReports $customerReports)
    {
        //
    }
}
