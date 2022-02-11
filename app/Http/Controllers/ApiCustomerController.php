<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Customer;
use App\Models\CustomerPhone;
use App\Models\typeCustomer;

use Illuminate\Http\Request;

class ApiCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $customers = Customer::with(['CustomerPhone', 'TypeCustomer'])
            ->where('name', 'like','%'.$request->search.'%')
            ->orWhere('email', 'like', '%'.$request->search.'%')->latest()->paginate(9);
        return response()->json($customers, 200);
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
        $customer = new Customer();
        $customer->fill($request->all());
        $customer->customer_code = Str::orderedUuid();
        $customer->status = 1;
        $customer->save();
        $phone = new CustomerPhone();
        $phone->customer_id = $customer->id; 
        $phone->phone = $request->phone ? $request->phone : 'Đang cập nhật';
        $phone->save();
        return response()->json([$customer,$request], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = Customer::with(['CustomerPhone', 'TypeCustomer'])->find($id);
        if($customer){
            return response()->json($customer, 200);
        }else{
            return response()->json([ 'message' => 'Không tìm thấy thông tin!'], 404);
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
        $customer = Customer::find($id)->update($request->all());
        $new_phones = $request->customer_phone;
        $del = CustomerPhone::where('customer_id', '=', $id)->delete();
        foreach ($new_phones as $key => $phone) {
            $p = CustomerPhone::create($phone);
        }
        return response()->json([$customer], 200);
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

    public function type_of_customer(){
        $type = typeCustomer::get();
        return response()->json($type, 200);
    }
}
