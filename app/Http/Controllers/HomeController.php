<?php

namespace App\Http\Controllers;

use App\Models\TypeCustomer;
use App\Models\TypeOfTask;
use App\Models\TypeProduct;
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

        return response()->json(['type_of_customer' => $type_of_customer, 
                                'type_of_product' => $type_of_product,
                                'type_of_task' => $type_of_task,
                                ], 200);
    }
}
