<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\user;

class ApiStaffController extends Controller
{
    public function getStaffs(){
        $user = User::latest()->paginate(10);
        return response()->json($user, 200);
    }
}
