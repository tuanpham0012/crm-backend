<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Customer;
use App\Models\CustomerPhone;
use App\Models\typeCustomer;

use App\Http\Requests\ApiCreatedCustomerRequest;
use App\Http\Requests\ApiLoginRequest;

use Illuminate\Http\Request;



class ApiCustomerController extends Controller
{
    public function createCustomer(ApiCreatedCustomerRequest $request){
        $customer = new Customer();
        $customer->fill($request->all());
        $customer->customer_code = Str::orderedUuid();
        $customer->customer_status = 1;
        $customer->save();
        $phone = new CustomerPhone();
        $phone->customer_id = $customer->id; 
        $phone->phone = $request->phone ? $request->phone : 'Đang cập nhật';
        $phone->save();
        return response()->json([$customer,$request], 200);
    }

    public function updateCustomer(Request $request, $id){
        $customer = Customer::find($id)->update($request->all());
        return response()->json([$customer], 200);
    }

    public function get_list(){
        $customers = Customer::with(['CustomerPhone', 'TypeCustomer'])->latest()->get();
        return response()->json($customers, 200);
    }

    public function get_info_customer($id){
        $customer = Customer::with(['CustomerPhone', 'TypeCustomer'])->find($id);
        if($customer){
            return response()->json($customer, 200);
        }else{
            return response()->json([ 'message' => 'Không tìm thấy thông tin!'], 404);
        }
    }

    public function type_of_customer(){
        $type = typeCustomer::get();
        return response()->json($type, 200);
    }
}
