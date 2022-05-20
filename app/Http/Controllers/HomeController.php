<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Department;
use App\Models\ProjectStatus;
use App\Models\TaskStatus;
use App\Models\TypeOfTask;
use App\Models\TypeProduct;
use App\Models\TypeCustomer;
use Illuminate\Http\Request;

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
                                ], 200);
    }
}
