<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\User;
use App\Models\Customer;
use App\Models\Contacts;
use App\Models\CustomerPhone;
use App\Models\typeCustomer;
use App\Models\CustomerNotes;

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
        $pageSize = 20;
        $search = $request->search;
        if(isset($request->type) && $request->type != -1){
            $customers = Customer::with(['CustomerPhone', 'TypeCustomer', 'Contacts.User'])
                ->where(function($query) use ($search){
                    $query->where('name', 'like','%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%');
                })->where('type_of_customer_id', $request->type)
                ->latest()->paginate($pageSize);
        }else{
            $customers = Customer::with(['CustomerPhone', 'TypeCustomer', 'Contacts.User'])
                ->where(function($query) use ($search){
                    $query->where('name', 'like','%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%');
                })->latest()->paginate($pageSize);
        }
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
        $validated = $request->validate([
            'email' => 'required|unique:customers|email',
            'phone' => 'required|unique:customer_phone',
        ]);

        $customer = new Customer();
        $customer->fill($request->all());
        $customer->customer_code = Str::orderedUuid();
        $customer->status = 1;
        $customer->save();
        
        $phone = new CustomerPhone();
        $phone->customer_id = $customer->id; 
        $phone->phone = $request->phone ? $request->phone : 'Đang cập nhật';
        $phone->save();

        $note = CustomerNotes::create([
            'user_id' => $request->user('api')->id,
            'customer_id' => $customer->id,
            'content' => 'Tạo mới khách hàng',
        ]);

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
        $customer = Customer::with(['CustomerPhone', 'TypeCustomer', 'Contacts.User','CustomerNotes.User'])->find($id);
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
        // $validated = $request->validate([
        //     'email' => 'unique:customers|email',
        //     'phone' => 'unique:customer_phone',
        // ]);
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

    public function assign_sale(Request $request){
            foreach($request->selected as $id){
                $contact = Contacts::where('customer_id', $id)->first();
                if(isset($contact)){
                    if($request->update)
                        $contact->user_id = $request->id;
                        $contact->save();
                }else{
                    $newCt = new Contacts;
                    $newCt->user_id = $request->id;
                    $newCt->customer_id = $id;
                    $newCt->save();
                }
            }
        return response()->json($msg = 'Cập nhật khách hàng thành công!', 200);
    }

    public function my_list_customer(Request $request){
        $pageSize = 20;
        if(isset($request->type) && $request->type == -1){
            $customers = Contacts::where('user_id', $request->user('api')->id)
            ->with(['Customer', 'Customer.CustomerPhone', 'Customer.TypeCustomer'])
            ->whereHas('Customer', function($q) use ($request){
                $q->where('name', 'like', '%'.$request->search.'%');
            })->latest()->paginate($pageSize);
        }else{
            $customers = Contacts::where('user_id', $request->user('api')->id)
                        ->with(['Customer', 'Customer.CustomerPhone', 'Customer.TypeCustomer'])
                        ->whereHas('Customer', function($q) use ($request){
                            $q->where('name', 'like', '%'.$request->search.'%')->where('type_of_customer_id',$request->type);
                        })->latest()->paginate($pageSize);
        }
        return response()->json($customers, 200);
    }
}
