<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\User;
use App\Models\Contacts;
use App\Models\Customer;
use App\Models\TypeProduct;
use Illuminate\Support\Str;
use App\Models\typeCustomer;

use Illuminate\Http\Request;
use App\Models\CustomerNotes;
use App\Models\CustomerPhone;
use App\Models\Interest;
use PHPUnit\TextUI\Help;

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
            $customers = Customer::with(['CustomerPhone', 'TypeCustomer', 'Contacts.User', 'CustomerNotes.User'])
                ->where(function($query) use ($search){
                    $query->where('name', 'like','%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%');
                })->where('type_of_customer_id', $request->type)
                ->latest()->paginate($pageSize);
        }else{
            $customers = Customer::with(['CustomerPhone', 'TypeCustomer', 'Contacts.User', 'CustomerNotes.User'])
                ->where(function($query) use ($search){
                    $query->where('name', 'like','%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%');
                })->latest()->paginate($pageSize);
        }
        $type_of_product = TypeProduct::get();
        $type_of_customer = typeCustomer::get();

        return response()->json([ 'customers' => $customers, 'type_of_product' => $type_of_product, 'type_of_customer' => $type_of_customer], 200);
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
            'email' => 'unique:customers|email',
            'phone' => 'unique:customer_phone',
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

        Helper::CreateNoteOfCustomer($customer->id, $request->user('api')->id, 'Tạo mới khách hàng');
        
        foreach($request->interest as $value){
            Interest::create([
                'customer_id' => $customer->id,
                'type_of_product_id' => $value['type_of_product_id'],
            ]);
        }

        return response()->json(['message' => 'Thêm khách hàng thành công!'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = Customer::with(['CustomerPhone', 'TypeCustomer', 'Contacts.User','CustomerNotes.User', 'Interest.TypeOfProduct'])->find($id);
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
        $del = CustomerPhone::where('customer_id', '=', $id)->get();
        if($del){ 
            $del->each->delete();
        }
        foreach ($new_phones as $phone) {
            CustomerPhone::create($phone);
        }

        $interest = Interest::where('customer_id', '=', $request->id)->get();
        if($interest){
            $interest->each->delete();
        }
        foreach($request->interest as $value){
            Interest::create($value);
        }
        return response()->json(['message' => 'Cập nhật thông tin thành công!',$customer], 200);
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

    public function assign_sale(Request $request){
            foreach($request->selected as $customer_id){
                $contact = Contacts::where('customer_id', $customer_id)->first();
                if(isset($contact)){
                    if($request->update)
                        $contact->user_id = $request->id;
                        $contact->save();
                }else{
                    $newCt = new Contacts;
                    $newCt->user_id = $request->id;
                    $newCt->customer_id = $customer_id;
                    $newCt->save();
                }
                $user = User::find($request->id);
                $content = 'Gán <span style="font-size:1.05rem;font-weight:bold;"'.$user->name.'</span> làm người phụ trách';
                Helper::CreateNoteOfCustomer($customer_id, $request->user('api')->id, $content);
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

    public function search_customer_code(Request $request){
        $customer = Customer::where('customer_code', 'like', $request->customer_code)->first();
        if($customer){
            return response()->json($customer, 200);
        }else{
            return response()->json(['msg' => 'Không tìm thấy thông tin khách hàng'], 404);
        }
        
    }

    public function updateCustomerInterest(Request $request){
        $interest = Interest::where('customer_id', $request->customer_id)->get();
        if(isset($interest)){
            $interest->delete();
        }
        foreach($request->interest as $value){
            $i = new Interest();
            $i->customer_id = $request->customer_id;
            $i->type_of_customer_id = $value;
            $i->save();
        }
        return response()->json(['message' => 'Cập nhật thành công!'], 200);
    }
}
