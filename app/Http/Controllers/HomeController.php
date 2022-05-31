<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Customer;
use App\Models\Position;
use App\Models\Department;
use App\Models\TaskStatus;
use App\Models\TypeOfTask;
use App\Models\TypeProduct;
use App\Models\TypeCustomer;
use Illuminate\Http\Request;
use App\Models\ProjectStatus;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function get_base_data(){
        $type_of_customer = TypeCustomer::get();
        $type_of_product = TypeProduct::get();
        $type_of_task = TypeOfTask::get();
        $task_status = TaskStatus::get();
        $project_status = ProjectStatus::get();
        $positions = Position::get();
        $staff = Department::with(['StaffOfDepartment' => function($q){
            $q->whereHas('User', function($query){
                $query->whereIn('role_id', [2,3]);
            });
        }, 'StaffOfDepartment.User'])->get();
        $customers = Customer::get();

        return response()->json(['type_of_customer' => $type_of_customer, 
                                'type_of_product' => $type_of_product,
                                'type_of_task' => $type_of_task,
                                'staff' => $staff,
                                'customers' => $customers,
                                'project_status' => $project_status,
                                'task_status' => $task_status,
                                'positions' => $positions,
                                ], 200);
    }

    public function home_data(){
        $id = Auth::user()->id;
        $customer = Customer::with(['TypeCustomer', 'User:id,name'])->where('contact_id',  $id)
        ->latest()->take(10)->get();

        $task = Task::with(['User', 'TypeOfTask', 'TaskUser', 'TaskUser.User', 'TaskStatus'])
                ->whereIn('task_status_id', [2, 3])->whereHas('TaskUser', 
                    function($q) use ($id){
                        return $q->where('user_id', $id)->where('accept', 0);
                })->latest()->get();
        return response()->json(['customers' => $customer, 'tasks' => $task], 200);
    }
}
