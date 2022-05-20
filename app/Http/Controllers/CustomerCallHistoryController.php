<?php

namespace App\Http\Controllers;

use App\Models\CustomerCallHistory;
use Illuminate\Http\Request;

class CustomerCallHistoryController extends Controller
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
        $contact = new CustomerCallHistory();
        $contact->fill($request->all());
        $contact->user_id = $request->user('api')->id;
        $contact->call_status_id = 1;
        $contact->save();

        return response()->json(['message' => 'Success', 'contact' => $contact], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CustomerCallHistory  $customerCallHistory
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerCallHistory $customerCallHistory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CustomerCallHistory  $customerCallHistory
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerCallHistory $customerCallHistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CustomerCallHistory  $customerCallHistory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerCallHistory $customerCallHistory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomerCallHistory  $customerCallHistory
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerCallHistory $customerCallHistory)
    {
        //
    }
}
