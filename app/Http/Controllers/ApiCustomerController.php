<?php

namespace App\Http\Controllers;

use App\User;
use DateTime;
use App\Models\Task;
use App\Helper\Helper;
use App\Mail\SendMail;
use App\Models\Contacts;

use App\Models\Customer;
use App\Models\Interest;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\TypeCustomer;
use Illuminate\Http\Request;
use App\Models\CustomerPhone;
use App\Models\SendMailHistory;
use App\Imports\CustomersImport;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

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
        $customers = Customer::with(['CustomerPhone', 'TypeCustomer', 'Contacts.User', 'CustomerNotes.User', 'User'])
                ->where(function($query) use ($search){
                    $query->where('name', 'like','%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%');
                })->where(function($q) use($request){
                    if(isset($request->type) && $request->type != -1 ) $q->where('type_of_customer_id', $request->type);
                })
                ->where(function($q) use($request){
                    if($request->delete != -1) $q->where('deleted', $request->delete);
                })
                ->latest()->paginate($pageSize);
        return response()->json([ 'customers' => $customers], 200);
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
        $customer = Customer::with([
            'CustomerPhone', 'TypeCustomer', 'Contacts.User','CustomerNotes.User', 
            'Interest.TypeOfProduct', 'Tasks.User', 'Tasks.TaskStatus', 
            'Tasks.TypeOfTask', 'Tasks.TaskUser',
            'SendMailHistories.User',
            'CallHistories.User'
            ])->find($id);
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
        $customer = Customer::find($id);
        $customer->fill($request->all());
        $customer->save();
        
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
            $user = User::find($request->user_id);
            if(isset($user)){
                foreach($request->listCustomer as $customer_id){
                    $customer = Customer::find($customer_id);
                    if($customer){
                        $contact = Contacts::where('customer_id', $customer_id)->first();
                        $title = 'Khách hàng';
                        $content1 = "<span class='span-name'>".$request->user('api')->name ."</span> chỉ định bạn phụ trách khách hàng <span class='span-name'>".$customer->name."</span>"; 
                        $relation = "customer";
                        $relation_id = $customer_id;
                        if(isset($contact)){
                            if($request->update){
                                $contact->user_id = $request->user_id;
                                $contact->save();
                            }
                        }else{
                            $newCt = new Contacts;
                            $newCt->user_id = $request->user_id;
                            $newCt->customer_id = $customer_id;
                            $newCt->save();
                        }
                        Helper::CreateNotification($title, $content1, $request->user_id,$relation, $relation_id);
                        
                        $content = 'Gán <span class="span-name">'. $user->name .'</span> làm người phụ trách';
                        Helper::CreateNoteOfCustomer($customer_id, $request->user('api')->id, $content);
                    }
                    
                }
            }
        return response()->json(['message' => 'Cập nhật khách hàng thành công!', 'name' => $user->name], 200);
    }

    public function update_type(Request $request){
        foreach($request->listCustomer as $customer_id){
            $customer = Customer::find($customer_id);
            if($customer){
                $type = TypeCustomer::find($request->type);
                if($type){
                    $customer->type_of_customer_id = $request->type;
                    $customer->save();
                    $content = '<span class="span-name">'. $request->user('api')->name .'</span> cập nhật quan hệ khách hàng thành <span class="span-name">' .$type->type . '</span>';
                    Helper::CreateNoteOfCustomer($customer_id, $request->user('api')->id, $content);
                }
            }
        }
    return response()->json(['message' => 'Cập nhật khách hàng thành công!'], 200);
    }

    public function multiple_delete(Request $request){
        foreach($request->listCustomer as $customer_id){
            $customer = Customer::find($customer_id);
            if($customer){
                if(!$customer->deleted){
                    $customer->deleted = 1;
                    $customer->save();
                    $content = '<span class="span-name">'. $request->user('api')->name .'</span> đã xóa khách hàng';
                    Helper::CreateNoteOfCustomer($customer_id, $request->user('api')->id, $content);
                }else{
                    $customer->delete();
                }
            }
        }
    return response()->json(['message' => 'Xóa khách hàng thành công!'], 200);
    }

    public function my_list_customer(Request $request){
        $pageSize = 20;
        $search = $request->search;
        $customers = Customer::with(['CustomerPhone', 'TypeCustomer', 'Contacts.User', 'CustomerNotes.User', 'User'])
                ->where(function($query) use ($search){
                    $query->where('name', 'like','%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%');
                })->where(function($q) use($request){
                    if(isset($request->type) && $request->type != -1 ) $q->where('type_of_customer_id', $request->type);
                })
                ->where(function($q) use($request){
                    if($request->delete != -1) $q->where('deleted', $request->delete);
                })->whereHas('Contacts', function($q) use($request){
                    return $q->where('user_id', $request->user('api')->id);
                })
                ->latest()->paginate($pageSize);

        return response()->json([ 'customers' => $customers], 200);
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

    public function get_task(Request $request, $id){
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

        return response()->json([ 
            'progress' => $progress, 
            'late' => $late, 
            'new_task' => $new, 
            'creater' => $creater,
            'finish' => $finish,
        ], 200);
    }

    public function send_mail(Request $request){
        $data = $request->content;
        $data['sender'] = $request->user('api')->name;
        $user_id = $request->user('api')->id;
        foreach($request->listCustomer as $value){
            $customer = Customer::find($value);
            if($customer){
                $email = $customer->email ?? '';
                
                $send_mail_history = new SendMailHistory();
                $send_mail_history->user_id = $user_id;
                $send_mail_history->title = $data['title'];
                $send_mail_history->content = $data['content'];
                $send_mail_history->customer_id = $value;
                $send_mail_history->to_email = $email;
                $send_mail_history->save();
                Mail::to($email)->send(new SendMail($data));
            }
        }
        return response()->json(['message' =>'Gửi thành công!'], 200);
    }

    public function create_customer_excel(Request $request){
        $fileName = $request->file('list-customer')->getRealPath();
        Excel::import(new CustomersImport, $fileName);
        return response()->json(['message' => 'Thêm mới thành công!'], 200);
    }


}
